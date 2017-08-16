
<!-- 
	This file checks that given user has a unique username, and then adds them
	to the database
-->


 
<?php
include 'ddb.php';

echo "<h1>Adding new user</h1>";

// First, check that the username isn't already taken
$sth = $connection->prepare("select username from worldzer0.player 
		where lower(username) = lower(:user) ");
$sth->execute(array(':user' => $_POST['username']));
$row = $sth->fetch();

if ($row) {
	// while invalid username, ask for username
	echo "Username " . $_POST[username] . " already exists.";
	
	echo "<h1>Inputer new username</h1>
		<form action=\"new_player.php\" method=\"post\">
		Username: <input type=\"text\" name =\"username\"/><br><br>
		<input type=\"hidden\" name=\"first_name\" value=\"$_POST[first_name]\">
		<input type=\"hidden\" name=\"last_name\" value=\"$_POST[last_name]\">
		<input type=\"hidden\" name=\"profile_text\" value=\"$_POST[profile_text]\">
		<input type=\"submit\" /> </form>";
}
else {
	$sth = $connection->prepare("INSERT INTO worldzer0.player (score, level, first_name, 
		last_name, username, profile_text)
		VALUES (0, 1, :first_name, :last_name, :username, :profile_text)");

	if ($sth->execute(array(':first_name'=>$_POST['first_name'],
			':last_name'=>$_POST['last_name'], ':username'=>$_POST['username'],
			':profile_text'=>$_POST['profile_text']))) {
		// echo "Insert successful, '$_POST[username]' added";
		// redirect to home page
		header('Location: https://web.cecs.pdx.edu/~arredon/world0/sign_in.php');
	}
	else {
		echo "Insert failed, $_POST[username] not added";
	}
}
 
?>
