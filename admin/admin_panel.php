<?php
session_start();
date_default_timezone_set('Asia/Kolkata'); // Set timezone for India

// Database connection
$host = 'localhost';
$dbname = 'srikakulam_police';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get current time
$current_time = date('Y-m-d H:i:s');

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

// Handle registration
if (isset($_POST['register'])) {
    $newUsername = $_POST['new_username'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$newUsername]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Username already exists";
        } else {
            // Create new user
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'editor')");
                $stmt->execute([$newUsername, $hashedPassword, $email]);
                $success = "Account created successfully! Please login.";
            } catch (PDOException $e) {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}

// Handle forgot password
if (isset($_POST['forgot_password'])) {
    $email = $_POST['forgot_email'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $success = "Password reset instructions have been sent to your email.";
    } else {
        $error = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Srikakulam Police</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Your existing CSS styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .current-time {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #666;
            position: relative;
        }

        .tab-button.active {
            color: #4CAF50;
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #4CAF50;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #45a049;
        }

        .error {
            color: #f44336;
            padding: 10px;
            margin: 10px 0;
            background: #ffebee;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            color: #4CAF50;
            padding: 10px;
            margin: 10px 0;
            background: #e8f5e9;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="current-time">
            <i class="far fa-clock"></i> <?php echo $current_time; ?>
        </div>

        <div class="tabs">
            <button class="tab-button active" onclick="openTab('login')">Login</button>
            <button class="tab-button" onclick="openTab('register')">Create New</button>
            <button class="tab-button" onclick="openTab('forgot')">Forgot Password</button>
        </div>

        <!-- Login Form -->
        <div id="login" class="tab-content active">
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
        </div>

        <!-- Register Form -->
        <div id="register" class="tab-content">
            <form method="POST">
                <div class="form-group">
                    <label for="new_username">Username</label>
                    <input type="text" id="new_username" name="new_username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="register" class="btn">Create Account</button>
            </form>
        </div>

        <!-- Forgot Password Form -->
        <div id="forgot" class="tab-content">
            <form method="POST">
                <div class="form-group">
                    <label for="forgot_email">Email</label>
                    <input type="email" id="forgot_email" name="forgot_email" required>
                </div>
                <button type="submit" name="forgot_password" class="btn">Reset Password</button>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
    </div>

    <script>
    function openTab(tabName) {
        // Hide all tab contents
        var tabContents = document.getElementsByClassName('tab-content');
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove('active');
        }

        // Remove active class from all tabs
        var tabs = document.getElementsByClassName('tab-button');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove('active');
        }

        // Show the selected tab content and activate the tab
        document.getElementById(tabName).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    // Update time every second
    function updateTime() {
        const now = new Date();
        const timeString = now.getFullYear() + '-' + 
                          String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                          String(now.getDate()).padStart(2, '0') + ' ' + 
                          String(now.getHours()).padStart(2, '0') + ':' + 
                          String(now.getMinutes()).padStart(2, '0') + ':' + 
                          String(now.getSeconds()).padStart(2, '0');
        document.querySelector('.current-time').innerHTML = 
            '<i class="far fa-clock"></i> ' + timeString;
    }

    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
    </script>
</body>
</html>
