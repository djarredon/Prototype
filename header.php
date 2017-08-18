<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

   This file contains common links used on the website.
   This file is "included" in ddb.php, and then shared to all other pages
*/

// common links (home, player list, task list)
echo "
<a href=\"/~arredon/world0/world0.php\">Home</a>
<a href=\"/~arredon/world0/players.php\">Players</a>
<a href=\"/~arredon/world0/tasks.php\">Tasks</a>";

// if user is signed in, then add a link to their profile page,
// a sign out button, and a link to create a new task
if (isset($_SESSION['username'])) {
	echo "<a href=\"/~arredon/world0/user.php/?name=$_SESSION[username]\"
		style=\"float: right;\"> $_SESSION[username] </a><br>
		<a href=\"/~arredon/world0/sign_out.php\" style=\"float: right;\">Sign Out</a>
		<br><a href=\"/~arredon/world0/new_task_form.php\" 
		style=\"float: right;\">New Task</a>";
}
// if the user isn't signed in, provide a link to the sign in page, and the sign up page.
else {
	echo "<a href=\"/~arredon/world0/sign_in.php\" style=\"float: right;\">Sign In</a>
		<a href=\"/~arredon/world0/new_player.php\" 
		style=\"float: right; padding-right:0.25cm\">Sign Up</a>";
}

// Title text
echo "<title>World0: A game of daring and whimsy.</title>";
