<?php
require_once 'config.php';

if (isLoggedIn()) {
    // Log activity
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, 'logout')");
    $stmt->execute([$_SESSION['user_id']]);
    
    // Clear session
    session_destroy();
}

header('Location: login.php');
exit();
?>
