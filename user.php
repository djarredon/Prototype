<html>
<body>

<?php
echo file_get_contents("header.html");
include 'pwddb1.php'; 
include 'ddb.php';

$query = "select username, first_name, last_name, score, level, profile_text, player_id
	from worldzer0.player 
	where username='" . htmlspecialchars($_GET["name"]) .'\'';
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
$row = pg_fetch_row($result);

// Store user_id
$user_id = $row[6];

echo "<br><br><br>";
// basic info
echo "User: $row[0] <br>
	Name: $row[1] $row[2] <br>
	Level (Score): $row[4] ($row[3]) <br>
	About me: $row[5]<br>";

// Friends
echo "<div id=\"friendlist\" style=\"text-align:right\">Friends list: <br></div>";
$query = "select username, score, level
	from worldzer0.player P, worldzer0.friend F
	where F.player_one=". $user_id .
	" and P.player_id != $user_id";
$result = pg_query($connection, $query)
	or die("Query error:" . pg_last_error() . "<br>$query");
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Username </td> <td> Score </td> <td> Level </td> </tr>";
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table><br><br><br><br>";

// Tasks in progress
echo "<div id=\"friendlist\" style=\"text-align:right\">Tasks in Progress: <br></div>";
$query = "select title, points, level_requirement
	from worldzer0.task T, worldzer0.group_rel G, worldzer0.task_in_progress P
	where G.user_id=$user_id and G.group_id=P.group_id and P.task_id=T.task_id";
$result = pg_query($connection, $query)
	or die("Query error:" . pg_last_error() . "<br>$query");
echo "<table border=\"1\" style=\"float: right;\"> <tr>
	<td> Task Title </td> <td> Points </td> <td> Level Req. </td> </tr>";
while ($row=pg_fetch_row($result)) {
    echo "<tr><td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table>";


// Tasks completed
// Team(s)


pg_close($connection);
?>

</body>
</html>
