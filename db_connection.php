<?php

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "restaurant_db"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $db_name, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You are now connected to the database

?>
