<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

	This file checks that given user has a unique username, and then adds them
	to the database
*/
include 'ddb.php';
include 'functions.php';

echo "<h1>Adding new user</h1>";
$errors = array();

if (!empty($_POST)) {
	// First, check that the username isn't already taken
	$sth = $connection->prepare("select username from worldzer0.player 
			where lower(username) = lower(:user) ");
	$sth->execute(array(':user' => $_POST['username']));
	$row = $sth->fetch();

	if ($row) {
		// invalid username
		$errors[] = "Username " . $_POST[username] . " already exists.";
	}
	if (strcmp($_POST['password1'], $_POST['password2']) != 0) {
		// while passwords don't match, ask for password
		$errors[] = "Passwords don't match.";
	}
	if (empty($errors)) {
		// hash password
		$pwd = hash0($_POST['username'], $_POST['password1']);

		$sth = $connection->prepare("INSERT INTO worldzer0.player (score, level, 
			first_name, last_name, username, pwd, profile_text)
			VALUES (0, 1, :first_name, :last_name, :username, :pwd, :profile_text)");

		if ($sth->execute(array(':first_name'=>$_POST['first_name'],
				':last_name'=>$_POST['last_name'], 
				':username'=>$_POST['username'],
				':pwd'=>$pwd,
				':profile_text'=>$_POST['profile_text']))) {
			// redirect to sign in page
			header('Location: https://web.cecs.pdx.edu/~arredon/world0/sign_in.php');
			die();
		}
		$errors[] = "Insert failed, $_POST[username] not added";
	}
}

echo "<h1>Input new user information</h1>";
if (!empty($errors)) {
	echo "<ul>";
	foreach($errors as $error) {
		echo "<li>$error</li>";
	}
	echo "</ul>";
}
echo "<form action=\"new_player.php\" method=\"post\">
	Username: <input type=\"text\" name =\"username\" value=\"$_POST[username]\"
		required/><br><br>
	Password: <input type=\"password\" name=\"password1\" value=\"$_POST[password1]\"
		required/><br><br>
	Confirm password: <input type=\"password\" name=\"password2\" value=\"$_POST[password2]\"
		required/><br><br>
	First Name: <input type=\"text\" name=\"first_name\" value=\"$_POST[first_name]\"
       		required/><br><br>
	Last Name: <input type=\"text\" name=\"last_name\" value=\"$_POST[last_name]\"
		required/><br><br>
	Something about yourself (visible to others): 
		<input type=\"text\" name=\"profile_text\" value=\"$_POST[profile_text]\"/><br><br>
	<input type=\"submit\" />
	</form>";
