<!--
	This page lists all users in the database, their score, and their level.
-->

<?php
include 'ddb.php';

echo "<h1>Players</h1>

<table border=\"2\">
<tr>
<td> Username </td> <td> Score </td> <td> Level </td>
</tr>";
 
    
// Get username, score, level, and url for each player
/* old code
$query = "select username, score, level, url from worldzer0.player";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
*/
$sth = $connection->prepare("select username, score, level, url from worldzer0.player");
$sth->execute();
// The table displays the users' usernames (as an html link to their page),
// their score, and their level
while($row = $sth->fetch()){
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
 
 
//pg_close($connection);
?>

</table>

