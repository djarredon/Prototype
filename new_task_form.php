<?php 
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

	This page contains input boxes for creating new tasks, and then sends 
	the input to the new_task.php page.
*/

include 'ddb.php';
echo "<div class=\"container\">";

echo "<h1>Input new task information</h1>
	<form action=\"new_task.php\" method=\"post\">
	Task Title: <input type=\"text\" name =\"title\"/><br><br>
	Description: <input type=\"text\" name=\"description\" /><br><br>
	Location: <input type=\"text\" name=\"location\" /><br><br>
	Points: <input type=\"number\" name=\"points\" /><br><br>
	Minimum Level Requirement: <input type=\"number\" name=\"level_requirement\" /><br><br>
	<input type=\"submit\" />
	</form>";
