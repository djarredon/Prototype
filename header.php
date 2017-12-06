<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

   This file contains common links used on the website.
   This file is "included" in ddb.php, and then shared to all other pages
*/

// common links (home, player list, task list)

// Import bootstrap
echo "
	<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css\" />
	<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js\"></script>

	<!--
	<script type=\"text/javascript\" src=\"http://quark.cs.pdx.edu:8001/socket.io/socket.io.js\"></script>
	-->
	<script type=\"text/javascript\" src=\"http://babbage.cs.pdx.edu:8081/socket.io/socket.io.js\"></script>


	<meta name=\"viewport\" content=\"width=device-width, intial-scale=1, 
		max-scale=1, user-scalable=no\">
	";

// Title text
echo "<title>World0: A game of daring and whimsy.</title>";

// Navbar
// The "odd" indentation in this section is to visually line up
// the nav and divs across echo calls
echo "
	<nav class=\"navbar navbar-inverse navbar-static-top\">
		<div class=\"container\">
			<div class=\"navbar-header\">
				<button type=\"button\" class=\"navbar-toggle collapsed\"
					data-toggle=\"collapse\" data-target=\"#header-collapse\"
					aria-expanded=\"false\">
					<span class=\"sr-only\">Toggle navigation</span>
					<span class=\"icon-bar\"></span>
					<span class=\"icon-bar\"></span>
					<span class=\"icon-bar\"></span>
				</button>
				<a class=\"navbar-brand\" href=\"/~arredon/world0/world0.php\">
					World0</a>
";
if (isset($_SESSION['username'])) {
	echo "		
			<a class=\"navbar-brand navbar-right\" href=\"/~arredon/world0/user.php/?name=$_SESSION[username]\">
				$_SESSION[username]</a></li>
	";
}
echo "
			</div>
			<div class=\"collapse navbar-collapse\" id=\"header-collapse\">
			<ul class=\"nav navbar-nav\">
				<li><a href=\"/~arredon/world0/players.php\">Players</a></li>
				<li><a href=\"/~arredon/world0/tasks.php\">Tasks</a></li>
			</ul>
";

// if user is signed in
// show profile link, sign out link, and new task link
// and chat (test)
/*
			<li><a href=\"/~arredon/world0/user.php/?name=$_SESSION[username]\">
				$_SESSION[username]</a></li>
   */
if (isset($_SESSION['username'])) {
	echo "
			<ul class=\"nav navbar-nav\">
				<li><a href=\"/~arredon/world0/chat.php\">Chat (test)</a></li>
			</ul>
			<ul class=\"nav navbar-nav navbar-right\">
				<li><a href=\"/~arredon/world0/new_task_form.php\">New Task</a></li>
				<li><a href=\"/~arredon/world0/sign_out.php\">Sign Out</a></li>
			</ul>
	";
}
else {
	echo "
			<ul class=\"nav navbar-nav navbar-right\">
				<li><a href=\"/~arredon/world0/sign_in.php\">Sign In</a></li>
				<li><a href=\"/~arredon/world0/new_player.php\">Sign Up</a></li>
			</ul>
	";
}
// end navbar
echo "	</div>
	</div>
	</nav>";

//start body container
//echo "<div class=\"container\">
