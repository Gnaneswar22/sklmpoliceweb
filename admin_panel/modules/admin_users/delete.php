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
        // First, get the user data
        $select_sql = "SELECT * FROM admin_users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $select_sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Store in deleted items table
            $store_sql = "INSERT INTO admin_users_deleted (original_id, username) VALUES (?, ?)";
            $store_stmt = mysqli_prepare($conn, $store_sql);
            mysqli_stmt_bind_param($store_stmt, "is", $user['id'], $user['username']);
            mysqli_stmt_execute($store_stmt);
            
            // Delete from admin_users
            $delete_sql = "DELETE FROM admin_users WHERE id = ?";
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
