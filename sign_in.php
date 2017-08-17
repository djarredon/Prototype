<?php 
/*
	This page is for signing in to a user profile.
	Passwords aren't implemented yet, so this is basically just to test processes
	like adding/removing friends/enemies, and creating, taking, and completing tasks.
 */
include 'ddb.php';
include 'functions.php';

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
		$pwd = hash0($_POST['username'], $_POST['password1']);

		$sth = $connection->prepare("select username, user_id 
				from worldzer0.player 
				where lower(username)=lower(:user)
				and pwd=:pwd");
		$sth->execute(array(':user'=>$_POST['username'], ':pwd'=>$pwd));
		$row = $sth->fetch();
				
		// if username and password are valid, set session.
		if ($row != False) {
			$_SESSION['username'] = $row['username'];
			$_SESSION['user_id'] = $row['user_id'];

			// redirect to user page
			header('Location: https://web.cecs.pdx.edu/~arredon/world0/world0.php');
		}
		else {
			unset($_POST);
			header('Location: https://web.cecs.pdx.edu/~arredon/world0/sign_in.php');
		}

	}
	// else, provide submission boxes for username (and, eventually, password).
	else {
		echo "<form action=\"/~arredon/world0/sign_in.php\" method=\"post\">
			Username (case-sensitive): <input name=\"username\" placeholder=\"username\" type=\"text\"
			value=\"$_POST[username]\">
			<br><br>
			Password (case-sensitive): <input type=\"password\" name=\"password\" 
				placeholder=\"password\">
			<br><br>
			<input name=\"submit\" type=\"submit\" value=\"Login\">
			</form>";
			
	}
}
// if not set, do nothing
