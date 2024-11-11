<?php
$servername = "localhost"; // Your database server
$username = "root"; // Your database username (default for XAMPP is 'root')
$password = ""; // Your database password (default for XAMPP is empty)
$dbname = "srikakulam_police"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
