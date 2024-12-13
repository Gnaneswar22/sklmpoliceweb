<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // First, get the item data
    $sql = "SELECT * FROM initiatives WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                
                // Store in deleted items table
                $store_sql = "INSERT INTO initiatives_deleted_items (title, description, image_path, link, order_position, is_active) 
                             VALUES (?, ?, ?, ?, ?, ?)";
                             
                if($store_stmt = mysqli_prepare($conn, $store_sql)){
                    mysqli_stmt_bind_param($store_stmt, "ssssii", 
                        $row['title'], 
                        $row['description'], 
                        $row['image_path'], 
                        $row['link'],
                        $row['order_position'],
                        $row['is_active']
                    );
                    
                    // Execute store statement
                    mysqli_stmt_execute($store_stmt);
                }
                
                // Now delete from initiatives
                $delete_sql = "DELETE FROM initiatives WHERE id = ?";
                if($delete_stmt = mysqli_prepare($conn, $delete_sql)){
                    mysqli_stmt_bind_param($delete_stmt, "i", $id);
                    if(mysqli_stmt_execute($delete_stmt)){
                        // Do not delete the physical file
                        header("location: index.php");
                        exit();
                    }
                }
            }
        }
    }
}

header("location: index.php");
exit();
?>
