<?php 
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)
*/
include 'ddb.php';
echo "<div class=\"container\">";

echo "<h1>Adding new task</h1>";
 
// First, check that the task title isn't already taken.
// Get username, database name, password, and establish connection.

$sth = $connection->prepare("select title from world0.task where title = :title");
$sth->execute(array(':title' => $_POST['title']));
$row = $sth->fetch();

// if there is a task with the same title, then ask for a new title until a new, unique
// title is provided
if ($row) {
	// while invalid task title, ask for title 
	echo "Task " . $_POST[title] . " already exists.";
	echo "<h1>Inputer new task title </h1>
		<form action=\"new_task.php\" method=\"post\">
		Task Title: <input type=\"text\" name =\"title\"/><br><br>
		<input type=\"submit\" />
		</form>";
}
else {
	$sth = $connection->prepare("INSERT INTO world0.task (title, description, location,
			points, level_requirement, created_by)
			values (:title, :description, :location, :points, :level, :user_id)");

	if ($sth->execute(array(':title'=>$_POST['title'],
			':description'=>$_POST['description'], ':location'=>$_POST['location'],
			':points'=>$_POST['points'], ':level'=>$_POST['level_requirement'],
			':user_id'=>$_SESSION['user_id']))) {
		// echo "Insert successful, '$_POST[title]' added";
		// redirect to home page
		header("Location: https://web.cecs.pdx.edu/~arredon/world0/task.php/?title=$_POST[title]");
	}
	else {
		echo "Insert failed, $_POST[title] not added";
	}
}
