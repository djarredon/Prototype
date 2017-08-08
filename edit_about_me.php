<html>
<body>

<?php
echo file_get_contents("header.html");
include 'ddb.php';


$name = htmlspecialchars($_GET["name"]);
// get old profile_text info
$query = "select profile_text from worldzer0.player 
	where username = '$name'";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
$row=pg_fetch_row($result);


echo "<br><br>";
echo "<h3>Edit $name's Profile Text</h3>";
echo "<form action=\"/~arredon/world0/new_about_me.php/?name=$name\" method=\"post\">
	<fieldset><legend>Edit $name's Profile Text</legend>
	Current: $row[0] <br>
	New: <input type=\"text\" name=\"new_about_me\"><br>
	<input type=\"submit\" /><br>";

echo "Current \"About Me\": $row[0]<br>";

pg_close($connection);
?>
</html>
</body>
