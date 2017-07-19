<html>
<body>
<?php echo file_get_contents("header.html"); ?>

<h1>Input new task information</h1>

<form action="new_task.php" method="post">
Task Title: <input type="text" name ="title"/><br><br>
Description: <input type="text" name="description" /><br><br>
Location: <input type="text" name="location" /><br><br>
Points: <input type="number" name="points" /><br><br>
Minimum Level Requirement: <input type="number" name="level_requirement" /><br><br>
 
<input type="submit" />
</form>
</body>
</html>
