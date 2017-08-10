
<?php
// This file stores the username and database name.
// Once we've moved to the new database, only this file needs to be updated.

/*
   World0 uses PDO for database connection management
	http://php.net/manual/en/class.pdo.php
*/

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

/* This part was specifically for postgresql
$connection = pg_connect("host=$hostname dbname=$databasename user=$username password=$password")
	or die ("Could not connect");
*/

// This is more generic, and is the only line that needs to be 
// changed if change databases.
$dsn = "pgsql:host=$hostname;dbname=$databasename";

try {
	$connection = new PDO($dsn, $dbusername, $password);
}
catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}

?>
