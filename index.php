<?php
// Database configuration
$host = "localhost";     // Server name
$username = "root";      // MySQL username
$password = "";          // MySQL password
$database = "recent-activity"; // Database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the single row from recent_activity table
$sql = "SELECT activity_message FROM recent_activity ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

$activity_message = "No recent activity found."; // Default message if no row found

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $activity_message = $row['activity_message'];
}

$conn->close();
?>
