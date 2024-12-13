<?php
session_start();

$host = "localhost";
$dbname = "srikakulam_police_department";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());

}
// In your config.php or similar configuration file
define('BASE_URL', 'http://localhost/sklmpoliceweb'); // Adjust this to your actual base URL

?>
