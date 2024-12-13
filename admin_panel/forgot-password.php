<?php
require_once "includes/config.php";

$username = $username_err = "";
$success_msg = "";
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if it's the first step (username verification) or second step (password reset)
    if(isset($_POST["username"])){
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter your username.";
        } else{
            $sql = "SELECT id FROM admin_users WHERE username = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["username"]);
                
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username = trim($_POST["username"]);
                        // Start session to store username for the next step
                        session_start();
                        $_SESSION["reset_username"] = $username;
                        header("location: reset-password.php");
                        exit();
                    } else{
                        $username_err = "No account found with that username.";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/auth.css">

</head>
<body>
    <div class="container">
    <div class="logo-container">
            <img src="assets/images/logo.png" alt="Police Department Logo" class="logo">
        </div>
        <div class="form-container">
            <h2>Forgot Password</h2>
            <?php 
            if(!empty($success_msg)){
                echo '<div class="alert alert-success">' . $success_msg . '</div>';
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" 
                           class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Continue">
                </div>
                <p><a href="login.php">Back to Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
