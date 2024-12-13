<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $description = $link = "";
$title_err = $description_err = $image_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }
    
    // Validate description
    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";
    } else {
        $description = trim($_POST["description"]);
    }
    
    // Handle image upload
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];
    
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!array_key_exists($ext, $allowed)){
            $image_err = "Please select a valid image format (JPG, JPEG, PNG, GIF).";
        }
    
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize){
            $image_err = "Image size must be less than 5MB.";
        }
    
        if(empty($image_err)){
            $upload_dir = "../../uploads/initiatives/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image_path = "uploads/initiatives/" . $new_filename;
            } else {
                $image_err = "Failed to upload image.";
            }
        }
    } else {
        $image_err = "Please select an image.";
    }
    
    // If no errors, proceed with insertion
    if(empty($title_err) && empty($description_err) && empty($image_err)){
        // Get max order position
        $sql = "SELECT MAX(order_position) as max_pos FROM initiatives";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $new_position = ($row['max_pos'] ?? 0) + 1;
        
        $sql = "INSERT INTO initiatives (title, description, image_path, link, order_position) VALUES (?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssi", $param_title, $param_description, $param_image_path, $param_link, $param_position);
            
            $param_title = $title;
            $param_description = $description;
            $param_image_path = $image_path;
            $param_link = !empty($_POST["link"]) ? trim($_POST["link"]) : "#";
            $param_position = $new_position;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Initiative added successfully.";
                $title = $description = $link = "";
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
    <title>Add Initiative</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Use the same base styles as index.php */
        /* Add form-specific styles */
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
    position: fixed;
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
        /* Main Content */
        .content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }

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

        /* Initiatives Grid */
        .initiatives-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .initiative-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .initiative-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .initiative-content {
            padding: 15px;
        }

        .initiative-content h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .initiative-content p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .initiative-actions {
            padding: 15px;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-edit {
            background: #f1c40f;
            color: #2c3e50;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn i {
            font-size: 0.9rem;
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

            .initiatives-grid {
                grid-template-columns: 1fr;
            }
        }
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

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
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 5px;
        }

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
                <h1>Add Initiative</h1>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Initiatives
                </a>
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
                        <label>Description</label>
                        <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                        <?php if(!empty($description_err)): ?>
                            <div class="invalid-feedback"><?php echo $description_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                        <?php if(!empty($image_err)): ?>
                            <div class="invalid-feedback"><?php echo $image_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Link (Optional)</label>
                        <input type="text" name="link" class="form-control" value="<?php echo $link; ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Initiative</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
