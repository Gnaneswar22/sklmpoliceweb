<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // First, get the item data
        $select_sql = "SELECT * FROM services WHERE id = ?";
        $stmt = mysqli_prepare($conn, $select_sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $service = mysqli_fetch_assoc($result);

        if ($service) {
            // Store in deleted items table
            $store_sql = "INSERT INTO services_deleted (original_id, title, description, icon_path, link, order_position, is_active) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
            $store_stmt = mysqli_prepare($conn, $store_sql);
            mysqli_stmt_bind_param($store_stmt, "issssis", 
                $service['id'],
                $service['title'],
                $service['description'],
                $service['icon_path'],
                $service['link'],
                $service['order_position'],
                $service['is_active']
            );
            mysqli_stmt_execute($store_stmt);
            
            // Delete from services
            $delete_sql = "DELETE FROM services WHERE id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($delete_stmt, "i", $id);
            if(mysqli_stmt_execute($delete_stmt)){
                mysqli_commit($conn);
                header("location: index.php");
                exit();
            }
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}

header("location: index.php");
exit();
?>
