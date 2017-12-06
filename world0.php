<?php
include 'ddb.php';

// wrap page in bootstrap container
echo "<div class=\"container\">";
echo "  <h1>World0</h1>";

if (isset($_SESSION['username']))
	echo "<h3> Welcome, $_SESSION[username]!</h3>";

echo "
	<h4>A game of daring and whimsy.</h4>
	<p>In World0, players can create tasks for other users to complete. Completing
	tasks gives users points, which then gives them levels, and, more importantly,
	bragging rights.<br>
	Gaining levels gives users access to more challenging tasks, and more rewards.<br>
	Users can join teams (not yet implemented), and tasks that they complete are given
	to their team.<br>
	</p>";
