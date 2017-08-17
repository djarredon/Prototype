<!--
	This page signs out the current user and redirects to the home page.
	This page should only be accessable by users already logged in.
-->
<?php include 'ddb.php';

echo "<h1>Log Out</h1>";

// user shouldn't be able to get here unless they're already signed in
if (isset($_SESSION['username'])) {
	// remove all session variables
	session_unset();
	
	// destroy the session
	session_destroy();

	// redirect
	header('Location: https://web.cecs.pdx.edu/~arredon/world0/world0.php');
}
