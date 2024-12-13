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
    $sql = "SELECT * FROM gallery WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                
                // Store in deleted items table
                $store_sql = "INSERT INTO gallery_deleted_items (title, media_path, media_type, alt_text, is_active) 
                             VALUES (?, ?, ?, ?, ?)";
                             
                if($store_stmt = mysqli_prepare($conn, $store_sql)){
                    mysqli_stmt_bind_param($store_stmt, "ssssi", 
                        $row['title'], 
                        $row['media_path'], 
                        $row['media_type'], 
                        $row['alt_text'], 
                        $row['is_active']
                    );
                    
                    // Execute store statement
                    mysqli_stmt_execute($store_stmt);
                }
                
                // Now delete from gallery
                $delete_sql = "DELETE FROM gallery WHERE id = ?";
                if($delete_stmt = mysqli_prepare($conn, $delete_sql)){
                    mysqli_stmt_bind_param($delete_stmt, "i", $id);
                    if(mysqli_stmt_execute($delete_stmt)){
                        // Do not delete the physical file when deleting the record
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
