<?php
require_once 'db_connect.php';
require_once 'recent_activity.php';

// Check admin authentication here (optional)

$activityManager = new RecentActivity($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['activity_text'] ?? '';
    $link = $_POST['activity_link'] ?? '';
    $is_important = isset($_POST['is_important']);

    if (!empty($text)) {
        if ($activityManager->addActivity($text, $link, $is_important)) {
            echo "Activity added successfully!";
        } else {
            echo "Error adding activity.";
        }
    } else {
        echo "Activity text cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Activities</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Manage Recent Activities</h1>
    <form method="index.php" action="admin.php">
        <div>
            <label for="activity_text">Activity Text:</label>
            <input type="text" id="activity_text" name="activity_text" required>
        </div>
        
        <div>
            <label for="activity_link">Link (optional):</label>
            <input type="url" id="activity_link" name="activity_link">
        </div>
        
        <div>
            <label for="is_important">Important:</label>
            <input type="checkbox" id="is_important" name="is_important">
        </div>
        
        <button type="submit">Add Activity</button>
    </form>
</body>
</html>
