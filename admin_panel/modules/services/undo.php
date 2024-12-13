<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

header('Content-Type: application/json');

// Begin transaction
mysqli_begin_transaction($conn);

try {
    // Get the latest deleted item
    $select_sql = "SELECT * FROM services_deleted ORDER BY deleted_at DESC LIMIT 1";
    $result = mysqli_query($conn, $select_sql);
    $deleted_item = mysqli_fetch_assoc($result);

    if ($deleted_item) {
        // Restore to services table
        $restore_sql = "INSERT INTO services (id, title, description, icon_path, link, order_position, is_active) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $restore_stmt = mysqli_prepare($conn, $restore_sql);
        mysqli_stmt_bind_param($restore_stmt, "issssis", 
            $deleted_item['original_id'],
            $deleted_item['title'],
            $deleted_item['description'],
            $deleted_item['icon_path'],
            $deleted_item['link'],
            $deleted_item['order_position'],
            $deleted_item['is_active']
        );
        
        if(mysqli_stmt_execute($restore_stmt)){
            // Remove from deleted items
            $delete_sql = "DELETE FROM services_deleted WHERE id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($delete_stmt, "i", $deleted_item['id']);
            mysqli_stmt_execute($delete_stmt);
            
            mysqli_commit($conn);
            echo json_encode(['success' => true, 'message' => 'Service restored successfully']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No items to restore']);
        exit();
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
