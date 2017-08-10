<?php include 'ddb.php'; ?>

<h1>Adding new task</h1>
 
<?php
// First, check that the task title isn't already taken.
// Get username, database name, password, and establish connection.

$sth = $connection->prepare("select title from worldzer0.task where title = :title");
$sth->execute(array(':title' => $_POST['title']));
$row = $sth->fetch();

// if there is a task with the same title, then ask for a new title until a new, unique
// title is provided
if ($row) {
	// while invalid task title, ask for title 
	echo "Task " . $_POST[title] . " already exists.";
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
	$sth = $connection->prepare("INSERT INTO worldzer0.task (title, description, location,
			points, level_requirement)
			values (:title, :description, :location, :points, :level)");

	if ($sth->execute(array(':title'=>$_POST['title'],
			':description'=>$_POST['description'], ':location'=>$_POST['location'],
			':points'=>$_POST['points'], ':level'=>$_POST['level_requirement']))) {
		echo "Insert successful, '$_POST[title]' added";
	}
	else {
		echo "Insert failed, $_POST[title] not added";
	}
 
}
?>
