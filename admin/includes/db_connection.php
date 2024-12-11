<?php
// Database configuration
$host = 'localhost';
$username = 'root';  // Your MySQL username
$password = '';      // Your MySQL password
$database = 'srikakulam_police_department';

// Create connection
try {
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
