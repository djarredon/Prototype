
<?php
include 'ddb.php';

echo "  <h1>World0</h1>";

if (isset($_SESSION['username']))
	echo "<h3> Welcome, $_SESSION[username]!</h3>";

echo "
	<p>Description.</p>
	<p>More stuff.</p>";

?>

