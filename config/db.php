<?php
$host = "localhost";
$user = "root";
$pass = "root123";
$dbname = "library_management";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>