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
    $sql = "SELECT * FROM emergency_numbers WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if($row = mysqli_fetch_assoc($result)){
                // Store in deleted items table
                $store_sql = "INSERT INTO emergency_numbers_deleted (
                    original_id, service_name, phone_number, icon, is_active
                ) VALUES (?, ?, ?, ?, ?)";
                             
                if($store_stmt = mysqli_prepare($conn, $store_sql)){
                    mysqli_stmt_bind_param($store_stmt, "isssi", 
                        $id,
                        $row['service_name'], 
                        $row['phone_number'], 
                        $row['icon'],
                        $row['is_active']
                    );
                    mysqli_stmt_execute($store_stmt);
                }
                
                // Now delete from emergency_numbers
                $delete_sql = "DELETE FROM emergency_numbers WHERE id = ?";
                if($delete_stmt = mysqli_prepare($conn, $delete_sql)){
                    mysqli_stmt_bind_param($delete_stmt, "i", $id);
                    if(mysqli_stmt_execute($delete_stmt)){
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
