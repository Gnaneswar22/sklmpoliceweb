<?php
require_once 'db_config.php';

$message = '';

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE reset_token = ? AND reset_expire > NOW()");
    $stmt->execute([$token]);
    
    if($stmt->rowCount() === 0) {
        $message = "Invalid or expired reset token.";
    }
} else {
    header("Location: index.php");
    exit();
}

if(isset($_POST['reset_password'])) {
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE admin_users SET password = ?, reset_token = NULL, reset_expire = NULL WHERE reset_token = ?");
        $stmt->execute([$hashed_password, $token]);
        
        $message = "Password reset successfully. You can now login.";
        header("Refresh: 3; url=index.php");
    } else {
        $message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Add your CSS here -->
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <?php if($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    </div>
</body>
</html>
