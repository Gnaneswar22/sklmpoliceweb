<?php
session_start();
require_once 'includes/db_connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    try {
        // Check if email exists
        $check_sql = "SELECT id FROM admin_users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Email not found");
        }

        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $update_sql = "UPDATE admin_users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $token, $expiry, $email);

        if ($update_stmt->execute()) {
            // Create reset link
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password.php?token=" . $token;

            // Email content
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
            <html>
            <head>
                <title>Password Reset Request</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>You have requested to reset your password. Click the link below to reset your password:</p>
                <p><a href='{$reset_link}'>{$reset_link}</a></p>
                <p>This link will expire in 1 hour (at " . date('Y-m-d H:i:s', strtotime($expiry)) . ")</p>
                <p>If you didn't request this, please ignore this email.</p>
                <p>Time sent: " . date('Y-m-d H:i:s') . "</p>
            </body>
            </html>
            ";

            // Email headers
            $headers = array(
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: Srikakulam Police Department <noreply@srikakulampolice.com>',
                'Reply-To: noreply@srikakulampolice.com',
                'X-Mailer: PHP/' . phpversion()
            );

            // Send email
            if(mail($to, $subject, $message, implode("\r\n", $headers))) {
                $success = "Password reset instructions have been sent to your email.";
                
                // For development/testing only - remove in production
                $success .= "<br>Reset Link (for testing): <a href='{$reset_link}'>Click here</a>";
            } else {
                throw new Exception("Failed to send reset email. Please try again later.");
            }
        } else {
            throw new Exception("Error generating reset token");
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Use same CSS as forgot-password.php -->
</head>
<body>
    <div class="forgot-container">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($success); ?>
                <div class="mt-3">
                    <a href="login.php" class="btn btn-primary">Go to Login</a>
                </div>
            </div>
        <?php elseif ($valid_token): ?>
            <form action="" method="POST">
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="new_password" placeholder="New Password" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                </div>

                <button type="submit" class="reset-btn">Reset Password</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger text-center">
                Invalid or expired reset token. Please request a new password reset.
                <div class="mt-3">
                    <a href="forgot-password.php" class="btn btn-primary">Request New Reset</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
