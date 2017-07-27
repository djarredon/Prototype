<!--
	This page lists all users in the database, their score, and their level.
-->
<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Players</h1>

<table border="2">
<tr>
<td> Username </td> <td> Score </td> <td> Level </td>
</tr>
 
<?php
include 'pwddb1.php'; 
include 'ddb.php';
    
// Get username, score, level, and url for each player
$query = "select username, score, level, url from worldzer0.player";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());

// The table displays the users' usernames (as an html link to their page),
// their score, and their level
while($row = pg_fetch_row($result)){
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
 
 
pg_close($connection);
?>

</table>

</body>
</html>
