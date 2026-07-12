<?php
// config.php - Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "project_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
session_start();
?>