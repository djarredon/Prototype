<!--
	This page lists all tasks in the database, their points, their level requirement,
	their short description, and their location.
-->

<?php
include 'ddb.php';
echo "<h1>Tasks</h1>

<table border=\"2\">
<tr>
<!-- Table layout -->
<td> Task Name </td> <td> Points </td> <td> Level Required </td> <td> Description </td> 
	<td> Location </td>
</tr>";
 
    
// Get task title, points, level requirement, description, location, and url.
/*
$query = "select title, points, level_requirement, description, location, task_url from worldzer0.task";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
*/
$sth = $connection->prepare("select title, points, level_requirement, description, location, task_url from worldzer0.task");
$sth->execute();

// Display information in table. The task title is a link to the url
while($row = $sth->fetch()){
    echo '<tr>';

    echo "<td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
	    <td>$row[4]</td>\n";
}
 
 
// don't need this anymore
//pg_close($connection);
?>

</table>

