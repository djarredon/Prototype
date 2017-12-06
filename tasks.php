<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

   This page lists all tasks in the database, their points, their level requirement,
   their short description, and their location.
*/

// don't display 'description' column on screens smaller than 787px
include 'ddb.php';
echo "<div class=\"container\">";
echo "<h1>Tasks</h1>
    <div class=\"table-responsive\">
	<table class=\"table table-condensed\">
	<thead>
		<tr>
		<!-- Table layout -->
		<td> Task Name </td> <td> Points </td> <td> Level Required </td> 
			<td> Description </td> <td> Location </td> <td> Creator </td>
		</tr>
	</thead>
	";
 
    
// Get task title, points, level requirement, description, and location.
$sth = $connection->prepare("select T.title, T.points, T.level_requirement, T.description, T.location, 
		P.username	
		from world0.task T, world0.player P
		where T.created_by=P.user_id");
$sth->execute();

// Display information in table. The task title is a link to the url
while($row = $sth->fetch()){
    echo '<tr>';

    echo "<td><a href=\"/~arredon/world0/task.php/?title=$row[0]\">$row[0]</td>
	    <td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
	    <td>$row[4]</td> 
	    <td> <a href=\"/~arredon/world0/user.php/?name=$row[5]\">$row[5] </td> </tr>";
}
echo "</table>
    </div>
    ";
 
?>

</table>
