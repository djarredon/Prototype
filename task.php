
<?php 
include 'ddb.php';

// show task information
$sth = $connection->prepare("select task_id, title, description, location, 
		points, level_requirement, rating, created_by
		from worldzer0.task
		where title= :title");
$sth->execute(array(':title'=> htmlspecialchars($_GET["title"])));
$row = $sth->fetch();
// get creator
$sth = $connection->prepare("select username
		from worldzer0.player
		where player_id=:user_id");
$sth->execute(array(':user_id'=>$row[7]));
$creator = $sth->fetch();

// store task_id
$task_id = $row[0];

echo "<br><br><br>";
echo "<h2>$row[1]</h2><br>
	Created by: <a href=\"/~arredon/world0/user.php/?name=$creator[0]\">$creator[0]</a><br>
	Description: $row[2]<br>
	Location: $row[3]<br>
	Points: $row[4]<br>
	Level Req.: $row[5]<br>
	Rating: $row[6]<br>";

// Take Task button
if (isset($_SESSION['user_id'])) {
	// check if this user is already working on, or completed this task
	$sth = $connection->prepare("select * 
			from worldzer0.group_rel G, worldzer0.task_complete C
			where C.task_id = :task_id and C.group_id=G.group_id
			and G.user_id= :user_id");
	$sth->execute(array(':task_id'=>$task_id, ':user_id'=>$_SESSION['user_id']));
	if ($sth->fetch()) {
		$complete = true;
	}
	else {
		$sth = $connection->prepare("select * 
				from worldzer0.group_rel G, worldzer0.task_in_progress C
				where C.task_id = :task_id and C.group_id=G.group_id
				and G.user_id= :user_id");
		$sth->execute(array(':task_id'=>$task_id, 
					':user_id'=>$_SESSION['user_id']));
		if ($sth->fetch()) {
			$in_progress = true;
		}
	}
	if (! ($complete or $in_progress)) {
		// The "take task" button goes here.
		echo "<form action=\"/~arredon/world0/take_task.php\" method=\"post\">
				<input type=\"hidden\" name=\"task_id\" value=\"$task_id\">
				<input type=\"Submit\" value=\"Take Task\" /></form>";
	}
}


// show users completed
$sth = $connection->prepare("select username, score, level
	from worldzer0.player P, worldzer0.group_rel G, worldzer0.task_complete C
	where C.task_id= :task_id and C.group_id=G.group_id and G.user_id=P.player_id");
$sth->execute(array(':task_id'=>$task_id));

echo "<div id=\"users_completed_list\" style=\"text-align:right\">Users Completed: <br></div>";
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
while ($row=$sth->fetch()) {
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br>";


// show users in progress
$sth = $connection->prepare("select username, score, level
	from worldzer0.player P, worldzer0.group_rel G, worldzer0.task_in_progress I
	where I.task_id=:task_id and I.group_id=G.group_id and G.user_id=P.player_id");
$sth->execute(array(':task_id'=>$task_id));

echo "<div id=\"In progress list\" style=\"text-align:right\">Users in Progress: <br></div>";
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
while ($row=$sth->fetch()) {
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br>";

?>

