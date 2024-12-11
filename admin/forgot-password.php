<?php
session_start();
require_once 'includes/db_connection.php';

$error = '';
$success = '';
$timestamp = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $sql = "SELECT id FROM admin_users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE admin_users SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashed_password, $username);
            
            if ($update_stmt->execute()) {
                $success = "Password updated successfully! Please login.";
            } else {
                $error = "Password update failed";
            }
        } else {
            $error = "Username not found";
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
    <style>
        body {
            background: linear-gradient(135deg, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 350px;
            position: relative;
            margin-top: 30px;
        }
        .user-icon {
            width: 80px;
            height: 80px;
            background: #1a237e;
            border-radius: 50%;
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .user-icon i {
            color: white;
            font-size: 32px;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding-left: 35px;
        }
        .form-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .login-btn {
            background: #1a237e;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            font-weight: bold;
        }
        .links-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            font-size: 14px;
        }
        .links-row a {
            color: #666;
            text-decoration: none;
        }
        .links-row a:hover {
            text-decoration: underline;
        }
        .timestamp {
            position: absolute;
            bottom: -30px;
            left: 0;
            width: 100%;
            text-align: center;
            color: white;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="user-icon">
            <i class="fas fa-lock"></i>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger text-center mb-3">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-center mb-3">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="new_password" placeholder="New Password" required>
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>

            <button type="submit" class="login-btn">RESET PASSWORD</button>
            
            <div class="links-row">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
        <div class="timestamp">
            <?php echo $timestamp; ?>
        </div>
    </div>
</body>
</html>
