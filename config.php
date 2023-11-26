<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Database configuration
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'job_board';

// Create a database connection
$db = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
