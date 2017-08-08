<html>
<body>

<?php
echo file_get_contents("header.html");
include 'ddb.php';

$query = "select username, first_name, last_name, score, level, profile_text, player_id
	from worldzer0.player 
	where username='" . htmlspecialchars($_GET["name"]) .'\'';
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
$row = pg_fetch_row($result);

// Store user_id, username
$user_id = $row[6];
$username = $row[0];

// First, Check if stored score is equal to sum of completed tasks by user
// Check for groups with this user, and check if that group has completed a task
$score_check = "select sum(T.points)
	from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_complete C
	where G.user_id=$user_id and G.group_id=C.group_id and C.task_id=T.task_id;";
$result = pg_query($connection, $score_check);
$s_row = pg_fetch_row($result);

// if the total points from the user's completed tasks doesn't equal their
// score in the database, update the database.
if ($s_row[0] != $row[3]) {
	$score_check = "update worldzer0.player
		set score = $s_row[0]
		where player_id= $user_id";
	$result = pg_query($connection, $score_check);
}


echo "<br><br><br>";
// basic info
echo "User: $row[0] <br>
	Name: $row[1] $row[2] <br>
	Level (Score): $row[4] ($row[3]) <br>
	About me: $row[5]<br>";
// add a way to edit the "About me" section
echo "<form action=\"/~arredon/world0/edit_about_me.php/?name=$row[0]\" method=\"post\">
	<input type=\"Submit\" value=\"Edit\" /></form>";

// Friends
echo "<div id=\"friendlist\" style=\"text-align:right\">Friends list: <br></div>";
$query = "select username, score, level
	from worldzer0.friend F join worldzer0.player P
	on P.player_id=F.player_two
	where F.player_one=$user_id";
$result = pg_query($connection, $query)
	or die("Query error:" . pg_last_error() . "<br>$query");
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
// display friends as html links
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td>
	    <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br><br>";


// Tasks completed
echo "<div id=\"task_completed\" style=\"text-align:right\">Tasks Completed: <br></div>";
$query = "select title, points, level_requirement
	from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_complete C
	where G.user_id=$user_id and G.group_id=C.group_id and C.task_id=T.task_id";
$result = pg_query($connection, $query)
	or die("Query error:" . pg_last_error() . "<br>$query");
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Task Title </td> <td> Points </td> <td> Level Req. </td> </tr>";
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br><br>";


// Tasks in progress
// Includes button to mark task "complete"
// This button should eventually lead to another page where the user can post photos
// or other evidence of their completion. 
echo "<div id=\"task_in_progress_list\" style=\"text-align:right\">Tasks in Progress: <br></div>";
$query = "select title, points, level_requirement, T.task_id
	from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_in_progress P
	where G.user_id=$user_id and G.group_id=P.group_id and P.task_id=T.task_id";
$result = pg_query($connection, $query)
	or die("Query error:" . pg_last_error() . "<br>$query");
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Task Title </td> <td> Points </td> <td> Level Req. </td>
        <td> Complete Task </td> </tr>";
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td>
	    <td> $row[1]</td> <td> $row[2]</td>
	    <td> <form action=\"/~arredon/world0/complete_task.php\" method=\"post\">"
	    // Send username and task_title to next page for processing
	         . "<input type=\"hidden\" name=\"user\" value=\"$user_id\">
	         <input type=\"hidden\" name=\"task\" value=\"$row[3]\">
	   	 <input type=\"Submit\" value=\"Complete\" /></form> </td> </tr>";

}
echo "</table><br><br><br><br><br>";

// Team(s)


pg_close($connection);
?>

</body>
</html>
