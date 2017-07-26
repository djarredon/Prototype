
<!-- 
	This file checks that given user has a unique username, and then adds them
	to the database
-->

<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Adding new user</h1>
 
<?php
include 'pwddb1.php'; 
include 'ddb.php';

// First, check that the username isn't already taken
$query = "select username from worldzer0.player where username = '$_POST[username]'";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
if (pg_num_rows($result) != 0) {
	// while invalid username, ask for username
	echo "Username " . $_POST[username] . " already exists.";
	pg_close($connection);
	?>
	<html>
	<body>
	<h1>Inputer new username</h1>

	<form action="new_player.php" method="post">
	Username: <input type="text" name ="username"/><br><br>
	 
	<input type="submit" />
	</form>
	</body>
	</html>
	<?php
}
else {
	$player_url= "/~arredon/world0/u/". str_replace(" ", "_", $_POST[username]) . ".php";
	 
	$query="INSERT INTO worldzer0.player (score, level, url, first_name, 
		last_name, username, profile_text)
		VALUES (0, 1, '$player_url','$_POST[first_name]','$_POST[last_name]',
			'$_POST[username]','$_POST[profile_text]')";
	 
	$result = pg_query($connection, $query)
	   or die("Query error:" . pg_last_error());
	   
	echo "Insert successful, '$_POST[username]' added";
	// create php page for the player
	$player_url= "u/".str_replace(" ", "_", $_POST[username]).".php";
	$handle = fopen($player_url, 'w') or die ('Cannot create file: '.$player_url);
	$contents = "<html>
<body>
<?php echo file_get_contents(\"../header.html\"); ?>

<h1>$_POST[username]</h1>
 
	<h2>Level (Score)</h2>
	$_POST[level] ($_POST[score])
	<h2>Body Text</h2>
	$_POST[profile_text]
</body>
</html>";

	fwrite($handle, $contents);
	fclose($player_url);
}
 
pg_close($connection);
?>
</body>
</html>
