<?php 
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

	This page is for signing in to a user profile.
	Passwords aren't implemented yet, so this is basically just to test processes
	like adding/removing friends/enemies, and creating, taking, and completing tasks.
 */
include 'ddb.php';
include 'functions.php';
echo "<div class=\"container\">";

echo "<h1>Sign In</h1>";

// The way this page works is by first prompting the user for a username
// (and, eventually, a password). The page then sends this information
// back to this page and the $_POST variable is checked for the username
// (and, eventually, password) and the user is signed in if valid.

// user shouldn't be able to access this page if they are logged in already.
if (!(isset($_SESSION['username']))) {
	// if the user has already provided login info, then check against database.
	if (isset($_POST['username'])) {
		// hash password
		$hpwd = hash0($_POST['username'], $_POST['password']);

		$sth = $connection->prepare("select username, user_id, pwd
				from world0.player 
				where lower(username)=lower(:user)
				and pwd=:hpwd");
		$sth->execute(array(':user'=>$_POST['username'], ':hpwd'=>$hpwd));
		$row = $sth->fetch();

		/*  Tests query results
		print_r($row);
		echo "<br>given password: $hpwd<br>actual password: $row[pwd]<br>";
		*/
				
		// if username and password are valid, set session.
		if ($row != False) {
			$_SESSION['username'] = $row['username'];
			$_SESSION['user_id'] = $row['user_id'];

			// redirect to user page
			header('Location: /~arredon/world0/world0.php');
		}
		// else, try old hash function
		else {
			$hpwd = oldHash0($_POST['username'], $_POST['password']);

			$sth = $connection->prepare("select username, user_id, pwd
					from world0.player 
					where lower(username)=lower(:user)
					and pwd=:hpwd");
			$sth->execute(array(':user'=>$_POST['username'], ':hpwd'=>$hpwd));
			$row = $sth->fetch();

			// if username and password are valid, set session.
			if ($row != False) {
				$_SESSION['username'] = $row['username'];
				$_SESSION['user_id'] = $row['user_id'];

				// redirect to user page
				header('Location: /~arredon/world0/world0.php');
			}
			// on both failures, prompt for sign in again
			else {
				// retry sign in
				unset($_POST);
				header('Location: /~arredon/world0/sign_in.php');
			}
		}

	}
	// else, provide submission boxes for username (and, eventually, password).
	else {
		echo "<form action=\"/~arredon/world0/sign_in.php\" method=\"post\">
			Username: <input name=\"username\" 
				placeholder=\"username\" type=\"text\"
				value=\"$_POST[username]\">
			<br><br>
			Password: <input type=\"password\" name=\"password\" 
				placeholder=\"password\">
			<br><br>
			<input name=\"submit\" type=\"submit\" value=\"Login\">
			</form>";
			
	}
}
// if user is already logged in, redirect to homepage
else {
	header('Location: /~arredon/world0/world0.php');
}
