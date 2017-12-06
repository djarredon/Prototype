<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)
*/
include 'ddb.php';
echo "<div class=\"container\">";

// get old profile_text info
$sth = $connection->prepare("update world0.player
	set profile_text=:new_text
	where user_id=:user_id");
$sth->execute(array(':new_text'=>$_POST['new_about_me'], 
			':user_id'=>$_SESSION['user_id']));

echo "<br><br><h3>Success!</h3>";
echo "<a href=\"/~arredon/world0/user.php/?name=$_SESSION[username]\">View Profile";
