
<?php
// This file stores the username and database name.
// Once we've moved to the new database, only this file needs to be updated.

$username = "w17ddb34";
$databasename = "w17ddb34";
$hostname = "dbclass.cs.pdx.edu";
$connection = pg_connect("host=$hostname dbname=$databasename user=$username password=$password")
    or die ("Could not connect");
?>
