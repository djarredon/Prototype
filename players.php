<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

   This page lists all users in the database, their score, and their level.
*/

include 'ddb.php';

	//<table border=\"2\">
echo "<div class=\"container\">";
echo "<h1>Players</h1>
    <div class=\"table-responsive\">
	<table class=\"table table-condensed table-striped\">
	<thead>
	<tr>
	<td> Username </td> <td> Score </td> <td> Level </td>
	</tr>
	</thead>
	";
 
    
// Get username, score, level, and url for each player
$sth = $connection->prepare("select username, score, level 
		from world0.player
		order by score desc");
$sth->execute();
// The table displays the users' usernames (as an html link to their page),
// their score, and their level
while($row = $sth->fetch()){
    echo "<tr><td><a href=\"/~arredon/world0/user.php/?name=$row[0]\">$row[0]</td> 
	    <td> $row[1]</td> <td> $row[2]</td>\n</tr>";
}
echo "</table>
    </div>
    ";
?>
