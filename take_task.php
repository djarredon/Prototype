<?php
include 'ddb.php';
// $_POST['task_id'] is the task ID of the task to be taken,
// $_SESSION['username']/$_SESSION['user_id'] is the user to take the task.
// Fields should be provided for up to X friends of the user to work on the task with the 
// taker. 
$user_max = 3;	// Picked arbitrarily. Maybe this can be based off of the taker's level, or 
		// the level of the team that they belong to?

// overview
// 1. 	make sure task and user are valid
// 2. 	make sure user doesn't already have task
// 3. 	list friends of user who haven't already completed the task
// 	(can they add friends who are already working on it? No, for now).
// 4.	select users to accompany taker on task.
// 5.	find a unique group number for those users (if they don't already have one)
// 6.	create that group_rel for each user
// 7.	insert that group_id and task_id into the in_progress table

// make sure task_id and user_id are set
if (isset($_POST['task_id']) and isset($_SESSION['user_id'])) {
	$task_id = $_POST['task_id'];
	$user_id = $_SESSION['user_id'];
	
	// if the button has already been pressed.
	if (isset($_POST['group_id'])) {
		// insert into task_in_progress table
		$sth = $connection->prepare("insert into worldzer0.task_in_progress
				(group_id, task_id) values (:group_id, :task_id)");
		$sth->execute(array(':group_id'=>$_POST['group_id'], ':task_id'=>$task_id));

		if ($sth)
			echo "Insert successful!<br>";
		else
			echo "Insert failed.<br>";
	}
	else {
		// check that task_id and user_id are valid
		$sth = $connection->prepare("select * from worldzer0.task
				where task_id= :task_id");
		$sth->execute(array(':task_id'=>$task_id));
		if ($sth->fetch() == false) {
			$bad_task = true;
			break;	
		}
		$sth = $connection->prepare("select * from worldzer0.player
				where user_id = :user_id");
		$sth->execute(array(':user_id'=>$user_id));
		if ($sth->fetch() == false) {
			$bad_user = true;
			break;	
		}
		// Make sure user hasn't already taken task.
		$sth = $connection->prepare("select * from 
				worldzer0.group_rel G, worldzer0.task_in_progress T
				where G.user_id=:user_id and G.group_id=T.group_id
				and T.task_id=:task_id");
		$sth->execute(array(':user_id'=>$user_id, ':task_id'=>$task_id));
		if ($sth->fetch() != false) {
			$already_taken = true;
			break;
		}
		// Make sure user hasn't already completed task.
		$sth = $connection->prepare("select * from 
				worldzer0.group_rel G, worldzer0.task_completed T
				where G.user_id=:user_id and G.group_id=T.group_id
				and T.task_id=:task_id");
		$sth->execute(array(':user_id'=>$user_id, ':task_id'=>$task_id));
		if ($sth->fetch() != false) {
			$already_completed = true;
			break;
		}
		// list friends of user who haven't completed or taken task
		// Friends list
		echo "<div id=\"friendlist\"><h3>Available Friends: </h3>
			(These are friends who have not yet completed this task)<br>";
		$sth = $connection->prepare("select username, level, user_id 
			      from worldzer0.friend F join worldzer0.player P
			      on P.user_id=F.player_two
			      where F.player_one= :user_id");
		$sth->execute(array(':user_id'=>$user_id));

		echo "<table border=\"1\"> <tr>
		<td> Username </td> <td> Level </td> <td> Take </td> </tr>";
		while ($row=$sth->fetch()) {
			$complete = $in_progress = false;
			$f_sth = $connection->prepare("select *
					from worldzer0.group_rel G, worldzer0.task_complete C
					where C.task_id =:task_id and C.group_id=G.group_id
					and G.user_id=:user_id");
			$f_sth->execute(array(':task_id'=>$task_id, ':user_id'=>$row[2]));
			$f_row = $f_sth->fetch(PDO::FETCH_ASSOC);
			if ($f_row !== false) {
				$complete = true;
			}
			else {
				$f_sth = $connection->prepare("select *
						from worldzer0.group_rel G, 
						worldzer0.task_in_progress C
						where C.task_id =:task_id 
						and C.group_id=G.group_id
						and G.user_id=:user_id");
				$f_sth->execute(array(':task_id'=>$task_id, ':user_id'=>$row[2]));
				$f_row = $f_sth->fetch(PDO::FETCH_ASSOC);
				if ($f_row !== false) {
					$in_progress = true;
				}
			}
			if (! ($complete or $in_progress)) {
				echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">
					$row[0]</td> <td> $row[1]</td> <td> \"Take Friend\" 
					button here. </td>\n</tr>";
			}
		}
		echo "</table></div>";

		// find unique group_id for these participants.
		// search group_rel for group_id containing the user_id of every selected
		// player, and only the selected players. If such a group_id exists,
		// add it to the task_in_progress list. Else, create a new group_id for 
		// these participants.
		// RIGHT NOW, just search for group_id of the current user.
		$sth = $connection->prepare("select group_id
				from worldzer0.group_rel
				where user_id=:user_id");
		$sth->execute(array(':user_id'=>$user_id));
		$g_id = $sth->fetch();
		if ($g_id)
			echo "Group ID: $g_id[0]<br>";
		else {
			// group_id in the database auto selects next available number
			// when a new value is inserted, so only insert the user_id
			echo "Making new group";	// test line
			$sth = $connection->prepare("insert into worldzer0.group_rel
					(user_id) values (:user_id)");
			$sth->execute(array(':user_id'=>$user_id));
			
			// now, get the newly made group_id
			$sth = $connection->prepare("select group_id
					from worldzer0.group_rel
					where user_id=:user_id");
			$sth->execute(array(':user_id'=>$user_id));
			$g_id = $sth->fetch();
		}

		// Take Task button
		echo "<form action=\"\" method=\"post\">
			<input type=\"hidden\" name=\"task_id\" value=\"$task_id\">
			<input type=\"hidden\" name=\"group_id\" value=\"$g_id[0]\">
			<input type=\"Submit\" value=\"Take Task\" /></form>";

		
	}
}
else {
	echo "<h4>Invalid.</h4>";
}
if ($bad_task)
	echo "<h4>Invalid Task ID</h4>";
if ($bad_user)
	echo "<h4>Invalid User ID</h4>";
if ($already_taken)
	echo "<h4>Task already taken.</h4>";
if ($already_completed)
	echo "<h4>Task already completed.</h4>";
