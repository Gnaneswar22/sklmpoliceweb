<?php
require_once 'db_connect.php';

class RecentActivity {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function addActivity($text, $link = '', $is_important = false) {
        $text = $this->conn->real_escape_string($text);
        $link = $this->conn->real_escape_string($link);
        $is_important = $is_important ? 1 : 0;
    
        $sql = "INSERT INTO recent_activities (activity_text, activity_link, is_important) 
                VALUES ('$text', '$link', $is_important)";
        
        return $this->conn->query($sql);
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
