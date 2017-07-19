<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Adding new task</h1>
 
<?php
$username = "w17ddb34";
include 'pwddb1.php'; // $password = "your DB password";
$databasename = "w17ddb34";
$hostname = "dbclass.cs.pdx.edu";
$connection = pg_connect("host=$hostname dbname=$databasename user=$username password=$password")
    or die ("Could not connect");

// First, check that the task title isn't already taken
$query = "select title from worldzer0.task where title = '$_POST[title]'";
$result = pg_query($connection, $query)
   or die("Query error:" . pg_last_error());
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
	// replace spaces in task title with "_"'s for url
	$task_url = "/~arredon/worldzer0/t/". str_replace(" ", "_", $_POST[title]) . ".php";
	// $task_url = "web.cecs.pdx.edu/~arredon/t/".$_POST[title];
	 
	$query="INSERT INTO worldzer0.task (title, description, location, points, 
		level_requirement, task_url)
		VALUES ('$_POST[title]', '$_POST[description]', '$_POST[location]', 
			'$_POST[points]', '$_POST[level_requirement]', '$task_url')";
	 
	$result = pg_query($connection, $query)
	   or die("Query error:" . pg_last_error());
	   
	echo "Insert successful, '$_POST[title]' added\n";
	// create php page for the task
	$task_url = "t/".str_replace(" ", "_", $_POST[title]).".php";
	// echo "<br>Task url: $task_url<br>";
	$handle = fopen($task_url, 'w') or die ('Cannot create file: '.$task_url);
	//chmod($task_url, 0777);
	//write to file?
	$contents = "<html>
<body>
<?php echo file_get_contents(\"../header.html\"); ?>

<h1>$_POST[title]</h1>
 
	<h2>Description</h2>
	$_POST[description]	
	<h2>Location</h2>
	$_POST[location]
	<h2>Points</h2>
	[[Need a way for users to vote on points]]<br>
	$_POST[points]
	<h2>Level Requirements</h2>
	$_POST[level_requirement]
</body>
</html>";

	fwrite($handle, $contents);
	fclose($task_url);
}
 
pg_close($connection);
?>
</body>
</html>
