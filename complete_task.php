<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)
   This file moves a task from a player's in-progress field to their completed field
*/

include 'ddb.php';
echo "<h1>Task Completion Page</h1>";

//<input type=\"hidden\" name=\"user\" value=\"$user_id\">	user_id
//<input type=\"hidden\" name=\"task\" value=\"$row[3]\">	task_id

// Add task to "completed" field, remove from "in_progress" field
// 	check for group_id matching user_id and task_id
//	add group_id to task_complete table
//	remove group_id from in_progress table

// get group_id of user and task
$sth = $connection->prepare("select group_id 
		from world0.group_rel 
		where user_id=:user_id");
$sth->execute(array(':user_id'=>$_POST['user']));
$row = $sth->fetch();
$group_id = $row[0];

// if a valid group_id was returned, do the rest
// else, close connection
if ($row != False) {
	// add group_id to task_complete table
	$sth = $connection->prepare("insert into world0.task_complete (task_id, group_id)
		  values (:task_id, :group_id)");
	$sth->execute(array(':task_id'=>$_POST['task'], ':group_id'=>$group_id));

	// remove group_id from task_in_progress table 
	$sth = $connection->prepare("delete from world0.task_in_progress 
		  where group_id=:group_id and task_id=:task_id;");
	$sth->execute(array(':task_id'=>$_POST['task'], ':group_id'=>$group_id));
	echo "<br><br><br><h3>Task Completed!</h3><br>";
}
