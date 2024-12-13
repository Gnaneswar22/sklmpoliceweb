<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

// Begin transaction
$conn->begin_transaction();

try {
    // Get the latest deleted item
    $select_sql = "SELECT * FROM about_section_deleted_items ORDER BY deleted_at DESC LIMIT 1";
    $result = mysqli_query($conn, $select_sql);
    $deleted_item = mysqli_fetch_assoc($result);

    if ($deleted_item) {
        // Restore to about_section table
        $restore_sql = "INSERT INTO about_section (id, title, tagline, description, is_active) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $restore_sql);
        mysqli_stmt_bind_param($stmt, "isssi", 
            $deleted_item['original_id'],
            $deleted_item['title'],
            $deleted_item['tagline'],
            $deleted_item['description'],
            $deleted_item['is_active']
        );
        
        if(mysqli_stmt_execute($stmt)){
            // Remove from deleted items
            $delete_sql = "DELETE FROM about_section_deleted_items WHERE id = ?";
            $stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($stmt, "i", $deleted_item['id']);
            mysqli_stmt_execute($stmt);
            
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Item restored successfully']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No items to restore']);
        exit();
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
