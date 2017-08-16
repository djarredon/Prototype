

<?php
include 'ddb.php';

// get page owner information
$sth = $connection->prepare("select username, first_name, last_name, score,
		level, profile_text, player_id
		from worldzer0.player where lower(username)= lower(:uname)");
$sth->execute(array(':uname'=>htmlspecialchars($_GET["name"])));
$row = $sth->fetch();

// Store user_id, username
$user_id = $row[6];
$username = $row[0];

// First, Check if stored score is equal to sum of completed tasks by user
// Check for groups with this user, and check if that group has completed a task
$sth = $connection->prepare("select sum(T.points)
		from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_complete C
		where G.user_id= :user_id and G.group_id=C.group_id and C.task_id=T.task_id");
$sth->execute(array(':user_id'=>$user_id));
$s_row = $sth->fetch();

// if the total points from the user's completed tasks doesn't equal their
// score in the database, update the database.
if ($s_row[0] != $row[3]) {
	$sth = $connection->prepare("update worldzer0.player
			set score = :points
			where player_id= :user_id");
	$sth->execute(array(':points'=>$s_row[0], ':user_id'=>$user_id));
}


echo "<br><br><br>";
// basic info
// if the profile is a friend/foe of the current user, then display it as (friend)/(foe) 
// next to the profile name
if (isset($_SESSION['username'])) {
	if ($_SESSION['username'] != $username) {
		$friend = $enemy = false;
		// if this user isn't the owner of the profile,
		// display friend/foe status.
		$sth = $connection->prepare("select * from worldzer0.friend
				where player_one = (select player_id from worldzer0.player
						    where lower(username) = lower(:user))
				and player_two = (select player_id from worldzer0.player
						  where lower(username) = lower(:two))");
		$sth->execute(array(':user'=>$_SESSION['username'], ':two'=>$row[0]));
		$f_row = $sth->fetch();
		if ($f_row) {
			echo "<h2> $row[0] (friend)</h2><br>";
			// set friend bool for later use
			$friend = true;
			$enemy = false;
		}
		else {
			// else, check if enemy
			$sth = $connection->prepare("select * from worldzer0.enemy
				where player_one = (select player_id from worldzer0.player
					where lower(username) = lower(:user))
				and player_two = (select player_id from worldzer0.player
						where lower(username) = lower(:two))");
			$sth->execute(array(':user'=>$_SESSION['username'], ':two'=>$row[0]));
			$f_row = $sth->fetch();
			if ($f_row) {
				echo "<h2> $row[0] (enemy)</h2><br>";
				$enemy = true;
				$friend = false;
			}
			else
				echo "<h2> $row[0]</h2><br>";
		}
	}
	else
		echo "<h2> $row[0]</h2><br>";
}
else
	echo "<h2> $row[0]</h2><br>";
echo "Name: $row[1] $row[2] <br>
	Level (Score): $row[4] ($row[3]) <br>
	About me: $row[5]<br>";

// add a way to edit the "About me" section
// only accessible if this page is owned by the current user
if (isset($_SESSION['username'])) {
	if ($_SESSION['username'] == $username) {
		echo "<form action=\"/~arredon/world0/edit_about_me.php/?name=$row[0]\" 
			method=\"post\">
		<input type=\"Submit\" value=\"Edit\" /></form>";
	}
}

// Add/remove friend/enemy buttons
// make sure user is logged in, and that they aren't on their own page
if (isset($_SESSION['username']) and $_SESSION['username'] != $username) {
	// if not friend or enemy, then both buttons appear.
	// if friend, only "remove friend" button appears.
	// if enemy, only "remove enemy" button appears.
	if (! ($friend or $enemy)) {
		// friend handler
		if (isset($_POST['friend'])) {
			$sth = $connection->prepare("insert into worldzer0.friend 
					(player_one, player_two)
					values (:one, :two)");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			$sth = $connection->prepare("insert into worldzer0.friend 
					(player_one, player_two)
					values (:two, :one)");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			echo "$username added as friend!";
		}
		if (!(isset($_POST['friend']) or isset($_POST['enemy'])))	
			echo "<form action=\"\" method=\"post\">
				<input type=\"hidden\" name=\"friend\" value=\"1\">
				<input type=\"Submit\" value=\"Add Friend\" /></form>";

		// enemy handler
		if (isset($_POST['enemy'])) {
			$sth = $connection->prepare("insert into worldzer0.enemy
					(player_one, player_two)
					values (:one, :two)");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			$sth = $connection->prepare("insert into worldzer0.enemy
					(player_one, player_two)
					values (:two, :one)");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			echo "$username added as an enemy!";
		}
		if (!(isset($_POST['friend']) or isset($_POST['enemy'])))	
			echo "<form action=\"\" method=\"post\">
				<input type=\"hidden\" name=\"enemy\" value=\"1\">
				<input type=\"Submit\" value=\"Add Enemy\" /></form>";}
	// if already friends, add "remove friend" button
	else if ($friend) {
		if (isset($_POST['unfriend'])) {
			$sth = $connection->prepare("delete from worldzer0.friend
					where player_one = :one and player_two = :two");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			$sth = $connection->prepare("delete from worldzer0.friend
					where player_one = :two and player_two = :one");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			echo "$username is no longer your friend.";
		}
		else
			echo "<form action=\"\" method=\"post\">
				<input type=\"hidden\" name=\"unfriend\" value=\"1\">
				<input type=\"Submit\" value=\"Remove Friend\" /></form>";
	}
	// if already enemies, add "remove enemy" button
	else if ($enemy) {
		if (isset($_POST['unenemy'])) {
			$sth = $connection->prepare("delete from worldzer0.enemy
					where player_one = :one and player_two = :two");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			$sth = $connection->prepare("delete from worldzer0.enemy
					where player_one = :two and player_two = :one");
			$sth->execute(array(':one'=>$user_id, ':two'=>$_SESSION['user_id']));
			echo "$username is no longer your enemy.";
		}
		else
			echo "<form action=\"\" method=\"post\">
				<input type=\"hidden\" name=\"unenemy\" value=\"1\">
				<input type=\"Submit\" value=\"Remove enemy\" /></form>";
	}
}


// Friends list
echo "<div id=\"friendlist\"><h3>Friends list: </h3>";
$sth = $connection->prepare("select username, score, level
	      from worldzer0.friend F join worldzer0.player P
	      on P.player_id=F.player_two
	      where F.player_one= :user_id");
$sth->execute(array(':user_id'=>$user_id));

echo "<table border=\"1\"> <tr>
<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
// display friends as html links
while ($row=$sth->fetch()) {
	echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td>
	      <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table></div>";

// Enemy list
echo "<div id=\"enemy_list\"><h3>Enemy list:</h3>";
$sth = $connection->prepare("select username, score, level
	      from worldzer0.enemy E join worldzer0.player P
	      on P.player_id=E.player_two
	      where E.player_one= :user_id");
$sth->execute(array(':user_id'=>$user_id));

echo "<table border=\"1\"> <tr>
<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
// display enemies as html links
while ($row=$sth->fetch()) {
	echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td>
	      <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table></div>";

// Tasks completed
echo "<div id=\"task_completed\"><h3>Tasks Completed: <h3>";
$sth = $connection->prepare("select title, points, level_requirement
		from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_complete C
		where G.user_id= :user_id and G.group_id=C.group_id and C.task_id=T.task_id");
$sth->execute(array(':user_id'=>$user_id));

echo "<table border=\"1\"> <tr>
<td> Task Title </td> <td> Points </td> <td> Level Req. </td> </tr>";
while ($row = $sth->fetch()) {
	echo "<tr><td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table></div>";

// Tasks in progress
// Includes button to mark task "complete"
// This button should eventually lead to another page where the user can post photos
// or other evidence of their completion. 
echo "<div id=\"task_in_progress_list\"><h3>Tasks in Progress: </h3>";

$sth = $connection->prepare("select title, points, level_requirement, T.task_id
		from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_in_progress P
		where G.user_id= :user_id and G.group_id=P.group_id and P.task_id=T.task_id");
$sth->execute(array(':user_id'=>$user_id));

echo "<table border=\"1\"> <tr> 
	<td> Task Title </td> <td> Points </td> <td> Level Req. </td>
	<td> Complete Task </td> </tr>";
while ($row = $sth->fetch()) {
	echo "<tr><td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td>
		<td> $row[1]</td> <td> $row[2]</td>
		<td> <form action=\"/~arredon/world0/complete_task.php\" method=\"post\">"
		// Send username and task_title to next page for processing
		. "<input type=\"hidden\" name=\"user\" value=\"$user_id\">
		<input type=\"hidden\" name=\"task\" value=\"$row[3]\">
		<input type=\"Submit\" value=\"Complete\" /></form> </td> </tr>";
}
echo "</table></div>";

// Team(s)


?>

