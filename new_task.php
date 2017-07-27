<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Adding new task</h1>
 
<?php
// get password for database
include 'pwddb1.php'; 
// get username, database name, and establish connection
include 'ddb.php';

// First, check that the task title isn't already taken
$query = "select title from worldzer0.task where title = '$_POST[title]'";
// result of query
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
// if there is a task with the same title, then ask for a new title until a new, unique
// title is provided
if (pg_num_rows($result) != 0) {
	// while invalid task title, ask for title 
	echo "Task " . $_POST[title] . " already exists.";
	pg_close($connection);
	?>
	<html>
	<body>
	<h1>Inputer new task title </h1>

	<form action="new_task.php" method="post">
	Task Title: <input type="text" name ="title"/><br><br>
	 
	<input type="submit" />
	</form>
	</body>
	</html>
	<?php
}
else {
	$task_url = "";
	 
	$query="INSERT INTO worldzer0.task (title, description, location, points, 
		level_requirement, task_url)
		VALUES ('$_POST[title]', '$_POST[description]', '$_POST[location]', 
			'$_POST[points]', '$_POST[level_requirement]', '$task_url')";
	 
	$result = pg_query($connection, $query)
	   or die("Query error:" . pg_last_error());
	   
	echo "Insert successful, '$_POST[title]' added\n";
}
 
pg_close($connection);
?>
</body>
</html>
