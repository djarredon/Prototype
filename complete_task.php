
<!-- 
	This file moves a task from a player's in-progress field to their completed field
-->

<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Task Completion Page</h1>
 
<?php
include 'ddb.php';

//<input type=\"hidden\" name=\"user\" value=\"$user_id\">	user_id
//<input type=\"hidden\" name=\"task\" value=\"$row[3]\">	task_id

// Add task to "completed" field, remove from "in_progress" field
// 	check for group_id matching user_id and task_id
//	add group_id to task_complete table
//	remove group_id from in_progress table

// get group_id of user and task
$query = "select group_id from worldzer0.group_rel where user_id=$1;";
$result = pg_query_params($connection, $query, array($_POST[user]));
$row=pg_fetch_row($result);
$group_id = $row[0];
echo "Query: $query <br>";
echo "Result: $result <br>";
echo "Group ID: $group_id <br>";

// if a valid group_id was returned, do the rest
// else, close connection
if ($result != False) {
	// add group_id to task_complete table
	$query = "insert into worldzer0.task_complete (task_id, group_id)
		  values ($1, $2);";
	$result = pg_query_params($connection, $query, array($_POST[task], $group_id));

	// remove group_id from task_in_progress table 
	$query = "delete from worldzer0.task_in_progress 
		  where group_id=$1;";
	$result = pg_query_params($connection, $query, array($group_id));
	echo "<br><br><br><h3>Task Completed!</h3><br>";
}


 
pg_close($connection);
?>
</body>
</html>
