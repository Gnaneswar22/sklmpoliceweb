<?php
require_once 'db_connect.php';

class RecentActivity {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getRecentActivities() {
        $sql = "SELECT activity_text, activity_link FROM recent_activities ORDER BY created_at DESC LIMIT 10";
        $result = $this->conn->query($sql);
        $activities = [];

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
        }

        return $activities;
    }
}

$activityManager = new RecentActivity($conn);
$activities = $activityManager->getRecentActivities();
?>
