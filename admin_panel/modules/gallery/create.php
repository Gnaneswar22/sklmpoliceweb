<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $alt_text = "";
$title_err = $media_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }
    
    // Handle file upload
    if(isset($_FILES["media"]) && $_FILES["media"]["error"] == 0){
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["media"]["name"];
        $filetype = $_FILES["media"]["type"];
        $filesize = $_FILES["media"]["size"];
    
        // Verify file extension
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!array_key_exists($ext, $allowed)){
            $media_err = "Error: Please select a valid image format (JPG, JPEG, PNG, GIF).";
        }
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize){
            $media_err = "Error: File size is larger than the allowed limit (5MB).";
        }
    
        if(empty($media_err)){
            // Create upload directory if it doesn't exist
            $upload_dir = "../../uploads/gallery/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)){
                $media_path = "uploads/gallery/" . $new_filename;
            } else {
                $media_err = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $media_err = "Please select a file to upload.";
    }
    
    // If no errors, proceed with insertion
    if(empty($title_err) && empty($media_err)){
        $sql = "INSERT INTO gallery (title, media_path, media_type, alt_text, is_active) VALUES (?, ?, ?, ?, 1)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssss", $param_title, $param_media_path, $param_media_type, $param_alt_text);
            
            $param_title = $title;
            $param_media_path = $media_path;
            $param_media_type = "image";
            $param_alt_text = !empty($_POST["alt_text"]) ? trim($_POST["alt_text"]) : $title;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Gallery item added successfully.";
                // Clear form data after successful insertion
                $title = $alt_text = "";
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Gallery Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Base Styles */
        :root {
    --primary-color: #6200EA;    /* Deep Purple */
    --secondary-color: #00BFA5;  /* Teal */
    --accent-color: #FF5252;     /* Red */
    --dark-bg: #1a1a1a;         /* Dark Background */
    --light-text: #E8EAED;
    --card-bg: #2d2d2d;
    --hover-color: #7C4DFF;
    --sidebar-bg: #1E1E2D;
}

/* Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--dark-bg);
    color: var(--light-text);
    line-height: 1.6;
}

/* Dashboard Layout */
.dashboard {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    width: 280px;
    background: var(--sidebar-bg);
    color: var(--light-text);
    position: absolute;
    height: 100vh;
    overflow-y: auto;
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 4px 0 10px rgba(0,0,0,0.2);
}

.logo-container {
    padding: 20px;
    text-align: center;
    background: rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.logo {
    max-width: 140px;
    height: auto;
}

.user-info {
    padding: 20px;
    text-align: center;
    background: rgba(255,255,255,0.03);
    margin: 15px;
    border-radius: 10px;
}

.user-info h3 {
    font-size: 1rem;
    color: var(--light-text);
    font-weight: 500;
}

/* Navigation Links */
.nav-links {
    list-style: none;
    padding: 15px 0;
}

.nav-links li {
    margin: 5px 15px;
}

.nav-links a {
    color: var(--light-text);
    text-decoration: none;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.nav-links a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.nav-links a:hover, .nav-links a.active {
    background: var(--primary-color);
    transform: translateX(5px);
}

/* Main Content */
.content {
    flex: 1;
    margin-left: 280px;
    padding: 20px;
}

/* Dashboard Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.dashboard-header h1 {
    color: var(--light-text);
    font-size: 1.8rem;
    font-weight: 600;
}

/* Date Time Display */
.date-time {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 10px 20px;
    border-radius: 8px;
    font-family: 'Roboto Mono', monospace;
    font-size: 0.9rem;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    animation: glow 2s infinite alternate;
}

/* Stats Container */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    padding: 10px;
}

/* Stat Cards */
.stat-card {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.stat-card i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: var(--secondary-color);
    animation: pulse 2s infinite;
}

.stat-card h3 {
    font-size: 1rem;
    color: var(--light-text);
    margin-bottom: 10px;
}

.stat-card p {
    font-size: 1.8rem;
    color: var(--accent-color);
    font-weight: 600;
}

/* Animations */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes glow {
    from {
        box-shadow: 0 0 5px rgba(98,0,234,0.2),
                    0 0 10px rgba(0,191,165,0.2);
    }
    to {
        box-shadow: 0 0 10px rgba(98,0,234,0.4),
                    0 0 20px rgba(0,191,165,0.4);
    }
}

/* Ripple Effect */
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Number Animation */
.animate-number {
    animation: numberAnimation 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

@keyframes numberAnimation {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    60% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--dark-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--hover-color);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .stats-container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 260px;
    }

    .content {
        margin-left: 0;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .stats-container {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
    }

    .stat-card {
        padding: 15px;
    }

    .stat-card i {
        font-size: 1.5rem;
    }

    .stat-card h3 {
        font-size: 0.9rem;
    }

    .stat-card p {
        font-size: 1.5rem;
    }
}
        /* Main Content Area */
        .content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }

        /* Module Header */
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .module-header h1 {
            color: #2c3e50;
            font-size: 1.8rem;
        }

        /* Form Container */
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #dce4ec;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52,152,219,0.2);
        }

        .form-control.is-invalid {
            border-color: #e74c3c;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        /* Alerts */
        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* File Upload Styling */
        input[type="file"] {
            padding: 8px;
            border: 2px dashed #dce4ec;
            background: #f8f9fa;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="file"]:hover {
            border-color: #3498db;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                transform: translateX(-100%);
            }

            .content {
                margin-left: 0;
            }

            .module-header {
                flex-direction: column;
                gap: 10px;
            }

            .form-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <div class="logo-container">
                <img src="../../assets/images/logo.png" alt="Police Department Logo" class="logo">
            </div>
            
            <ul class="nav-links">
                <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="modules/nav_menu_items/"><i class="fas fa-bars"></i> Navigation Menu</a></li>
                <li><a href="modules/hero_slides/"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
                <li><a href="modules/about_section/"><i class="fas fa-info-circle"></i> About Section</a></li>
                <li><a href="modules/about_slider/"><i class="fas fa-images"></i> About Slider</a></li>
                <li><a href="modules/emergency_numbers/"><i class="fas fa-phone"></i> Emergency Numbers</a></li>
                <li><a href="modules/news_items/"><i class="fas fa-newspaper"></i> News Items</a></li>
                <li><a href="modules/press_releases/"><i class="fas fa-file-alt"></i> Press Releases</a></li>
                <li><a href="modules/gallery/"><i class="fas fa-camera"></i> Gallery</a></li>
                <li><a href="modules/initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
                <li><a href="modules/services/"><i class="fas fa-hands-helping"></i> Services</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <main class="content">
            <div class="module-header">
                <h1>Add Gallery Item</h1>
                <a href="index.php" class="btn btn-secondary">Back to Gallery</a>
            </div>

            <div class="form-container">
                <?php 
                if(!empty($success_msg)){
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                        <?php if(!empty($title_err)): ?>
                            <div class="invalid-feedback"><?php echo $title_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="media" class="form-control <?php echo (!empty($media_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                        <?php if(!empty($media_err)): ?>
                            <div class="invalid-feedback"><?php echo $media_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Alt Text (Optional)</label>
                        <input type="text" name="alt_text" class="form-control" value="<?php echo $alt_text; ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Gallery Item</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>



    
</body>
</html>
