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
    $conn->begin_transaction();

    try {
        // First, get the item data
        $select_sql = "SELECT * FROM about_slider WHERE id = ?";
        $stmt = mysqli_prepare($conn, $select_sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $slider = mysqli_fetch_assoc($result);

        if ($slider) {
            // Store in deleted items table
            $store_sql = "INSERT INTO about_slider_deleted_items (original_id, image_path, alt_text, order_position, is_active) 
                         VALUES (?, ?, ?, ?, ?)";
                         
            if($store_stmt = mysqli_prepare($conn, $store_sql)){
                mysqli_stmt_bind_param($store_stmt, "issis", 
                    $slider['id'],
                    $slider['image_path'], 
                    $slider['alt_text'], 
                    $slider['order_position'],
                    $slider['is_active']
                );
                
                // Execute store statement
                mysqli_stmt_execute($store_stmt);
            }
            
            // Now delete from about_slider
            $delete_sql = "DELETE FROM about_slider WHERE id = ?";
            $stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt)){
                // Do not delete the physical file when deleting the record
                $conn->commit();
                header("location: index.php");
                exit();
            }
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

header("location: index.php");
exit();
?>
