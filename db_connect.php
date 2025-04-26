<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$database = "cp476a";

// Create MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

// Checks connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Succesfully Connected";
}
?>
