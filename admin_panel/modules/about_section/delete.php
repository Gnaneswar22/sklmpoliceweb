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
        $select_sql = "SELECT * FROM about_section WHERE id = ?";
        $stmt = mysqli_prepare($conn, $select_sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $about = mysqli_fetch_assoc($result);

        if ($about) {
            // Store in deleted items table
            $store_sql = "INSERT INTO about_section_deleted_items (original_id, title, tagline, description, is_active) 
                         VALUES (?, ?, ?, ?, ?)";
                         
            if($store_stmt = mysqli_prepare($conn, $store_sql)){
                mysqli_stmt_bind_param($store_stmt, "isssi", 
                    $about['id'],
                    $about['title'], 
                    $about['tagline'], 
                    $about['description'],
                    $about['is_active']
                );
                
                // Execute store statement
                mysqli_stmt_execute($store_stmt);
            }
            
            // Now delete from about_section
            $delete_sql = "DELETE FROM about_section WHERE id = ?";
            $stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt)){
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
