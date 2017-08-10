<html>
<body>

<?php 
include 'ddb.php';

// show task information
$query = "select task_id, title, description, location, points, level_requirement, rating
	from worldzer0.task
	where title='".htmlspecialchars($_GET["title"]). '\'';
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
$row = pg_fetch_row($result);

// store task_id
$task_id = $row[0];

echo "<br><br><br>";
echo "<h2>$row[1]</h2><br>
	Description: $row[2]<br>
	Location: $row[3]<br>
	Points: $row[4]<br>
	Level Req.: $row[5]<br>
	Rating: $row[6]<br>";

// Take Task button


// show users completed
$query = "select username, score, level
	from worldzer0.player P, worldzer0.group_rel G, worldzer0.task_complete C
	where C.task_id=$task_id and C.group_id=G.group_id and G.user_id=P.player_id";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());

echo "<div id=\"users_completed_list\" style=\"text-align:right\">Users Completed: <br></div>";
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br>";


// show users in progress
$query = "select username, score, level
	from worldzer0.player P, worldzer0.group_rel G, worldzer0.task_in_progress I
	where I.task_id=$task_id and I.group_id=G.group_id and G.user_id=P.player_id";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());

echo "<div id=\"In progress list\" style=\"text-align:right\">Users in Progress: <br></div>";
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br>";



pg_close($connection);
?>

</body>
</html>
