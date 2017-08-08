<html>
<body>

<?php
echo file_get_contents("header.html");
include 'ddb.php';


$name = htmlspecialchars($_GET["name"]);
// get old profile_text info
$query = "update worldzer0.player
	set profile_text='$_POST[new_about_me]'
	where username = '$name'";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
$row=pg_fetch_row($result);

echo "<br><br><h3>Success!</h3>";
echo "<a href=\"/~arredon/world0/user.php/?name=$name\">View Profile";

pg_close($connection);
?>
</html>
</body>
