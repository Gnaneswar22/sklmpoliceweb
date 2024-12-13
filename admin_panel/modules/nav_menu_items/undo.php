<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get the most recently deleted item
    $sql = "SELECT * FROM nav_menu_items_deleted ORDER BY deleted_at DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if($row = mysqli_fetch_assoc($result)){
        // Restore to nav_menu_items table
        $restore_sql = "INSERT INTO nav_menu_items (id, title, parent_id, link, order_position, is_active) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $restore_sql)){
            mysqli_stmt_bind_param($stmt, "isisii", 
                $row['original_id'],
                $row['title'], 
                $row['parent_id'], 
                $row['link'],
                $row['order_position'],
                $row['is_active']
            );
            
            if(mysqli_stmt_execute($stmt)){
                // Remove from deleted items
                mysqli_query($conn, "DELETE FROM nav_menu_items_deleted WHERE id = " . $row['id']);
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to restore: ' . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No items to restore']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
