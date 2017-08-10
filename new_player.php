
<!-- 
	This file checks that given user has a unique username, and then adds them
	to the database
-->

<?php include 'ddb.php'; ?>

<h1>Adding new user</h1>
 
<?php

// First, check that the username isn't already taken
$sth = $connection->prepare("select username from worldzer0.player where username = :user ");
$sth->execute(array(':user' => $_POST['username']));
$row = $sth->fetch();

if ($row) {
	// while invalid username, ask for username
	echo "Username " . $_POST[username] . " already exists.";
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
	$sth = $connection->prepare("INSERT INTO worldzer0.player (score, level, first_name, 
		last_name, username, profile_text)
		VALUES (0, 1, :first_name, :last_name, :username, :profile_text)");

	if ($sth->execute(array(':first_name'=>$_POST['first_name'],
			':last_name'=>$_POST['last_name'], ':username'=>$_POST['username'],
			':profile_text'=>$_POST['profile_text']))) {
		echo "Insert successful, '$_POST[username]' added";
	}
	else {
		echo "Insert failed, $_POST[username] not added";
	}
}
 
?>
