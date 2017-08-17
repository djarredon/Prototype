<?php
include 'ddb.php';

// get old profile_text info
if (isset($_SESSION['user_id'])) {
	$sth = $connection->prepare("select profile_text from worldzer0.player 
		where user_id=:user_id");
	$sth->execute(array(':user_id'=>$_SESSION['user_id']));
	$row = $sth->fetch();

	echo "<br><br>";
	echo "<h3>Edit $_SESSION[username]'s Profile Text</h3>";

	echo "<form action=\"/~arredon/world0/new_about_me.php\" method=\"post\" name=\"about_me\">
		<textarea id=\"textarea\" name=\"new_about_me\" cols=\"50\" rows=\"5\">
		$row[0]</textarea>
		<input type=\"Submit\" value=\"Submit\"></form>";
}
