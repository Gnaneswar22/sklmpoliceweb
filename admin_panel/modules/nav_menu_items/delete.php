<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);

    // Check if the item exists
    $sql = "SELECT * FROM nav_menu_items WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                // Delete the record
                $delete_sql = "DELETE FROM nav_menu_items WHERE id = ?";
                if($delete_stmt = mysqli_prepare($conn, $delete_sql)){
                    mysqli_stmt_bind_param($delete_stmt, "i", $id);
                    if(mysqli_stmt_execute($delete_stmt)){
                        // Redirect back to index after successful deletion
                        header("location: index.php");
                        exit();
                    } else {
                        echo "Error: Could not execute the delete statement.";
                    }
                }
            } else {
                echo "Error: Record not found.";
            }
        } else {
            echo "Error: Could not execute the query.";
        }
    }
} else {
    echo "Error: Invalid ID.";
}

header("location: index.php");
exit();
?>
