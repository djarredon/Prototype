<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)
*/
// This file stores the username and database name.
// Once we've moved to the new database, only this file needs to be updated.

/*
   World0 uses PDO for database connection management
	http://php.net/manual/en/class.pdo.php
*/
ini_set('error_reporting', E_ALL);

// session for testing sign-in required activities (taking tasks, adding friends, etc.)
session_start();

// get header
include 'header.php';
// get database password
include 'pwddb1.php'; 

// username for database
$dbusername = "w17ddb34";
// name of databse (these happen to be the same. This won't always be the case)
$databasename = "w17ddb34";
// Website hosting the database
$hostname = "dbclass.cs.pdx.edu";



// This is more generic, and is the only line that needs to be 
// changed if change databases.
$dsn = "pgsql:host=$hostname;dbname=$databasename";

try {
	$connection = new PDO($dsn, $dbusername, $password);
}
catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}
