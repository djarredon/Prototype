<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Players</h1>

<table border="2">
<tr>
<td> Username </td> <td> Score </td> <td> Level </td> <td> Profile </td>
</tr>
 
<?php
$username = "w17ddb34";
include 'pwddb1.php'; // $password = "your DB password";
$databasename = "w17ddb34";
$hostname = "dbclass.cs.pdx.edu";
$connection = pg_connect("host=$hostname dbname=$databasename user=$username password=$password")
    or die ("Could not connect");
    
$query = "select username, score, level, url from worldzer0.player";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());

while($row = pg_fetch_row($result)){
    echo '<tr>';
    echo "<td>$row[0]</td> <td> $row[1]</td> <td> $row[2]</td> <td> $row[3]</td>\n";
    echo '</tr>';
}
 
 
pg_close($connection);
?>

</table>

</body>
</html>
