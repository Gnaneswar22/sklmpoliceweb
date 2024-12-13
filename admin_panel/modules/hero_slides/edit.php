<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

// Initialize variables
$title = $subtitle = $tagline = $alt_text = $current_image = "";
$order_position = 0;
$is_active = 1;
$dark_overlay = 1;
$title_err = $image_err = $order_err = "";
$success_msg = "";

// Process edit form data
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Fetch existing slide data
    $sql = "SELECT * FROM hero_slides WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                
                $title = $row["title"];
                $subtitle = $row["subtitle"];
                $tagline = $row["tagline"];
                $current_image = $row["image_path"];
                $alt_text = $row["alt_text"];
                $order_position = $row["order_position"];
                $is_active = $row["is_active"];
                $dark_overlay = $row["dark_overlay"];
            } else{
                header("location: index.php");
                exit();
            }
        }
        mysqli_stmt_close($stmt);
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["id"]);
    
    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }
    
    // Handle image upload if new image is selected
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] != 4){
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
            $upload_dir = "../../uploads/hero_slides/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image_path = "uploads/hero_slides/" . $new_filename;
                
                // Delete old image
                if(!empty($current_image) && file_exists("../../" . $current_image)){
                    unlink("../../" . $current_image);
                }
            } else {
                $image_err = "Failed to upload image.";
            }
        }
    } else {
        $image_path = $current_image;
    }
    
    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }
    
    // Get other form data
    $subtitle = trim($_POST["subtitle"]);
    $tagline = trim($_POST["tagline"]);
    $alt_text = trim($_POST["alt_text"]);
    $is_active = isset($_POST["is_active"]) ? 1 : 0;
    $dark_overlay = isset($_POST["dark_overlay"]) ? 1 : 0;
    
    // Update record if no errors
    if(empty($title_err) && empty($image_err) && empty($order_err)){
        $sql = "UPDATE hero_slides SET title=?, subtitle=?, tagline=?, image_path=?, alt_text=?, 
                order_position=?, is_active=?, dark_overlay=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssssiiis", 
                $title, 
                $subtitle, 
                $tagline, 
                $image_path, 
                $alt_text, 
                $order_position, 
                $is_active,
                $dark_overlay,
                $id
            );
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Hero slide updated successfully.";
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$timeString = "<local_time>2024-12-12 13:59:21 in YYYY-MM-DD HH:mm:ss format</local_time>";
function parseLocalTime($timeString) {
    preg_match('/<local_time>(.*?)<\/local_time>/', $timeString, $matches);
    if (isset($matches[1])) {
        return trim(explode(' in ', $matches[1])[0]);
    }
    return date('Y-m-d H:i:s');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Slide</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/form.css">
    <style>
        
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <nav class="sidebar">
            
        <div class="logo-container">
                <img src="../../assets/images/logo.png" alt="Logo" class="logo">
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
        </nav>

        <main class="content">
            <div class="form-wrapper">
                <div class="form-container">
                    <div class="form-header">
                        <h2>Edit Hero Slide</h2>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <?php if (!empty($title_err)): ?>
                                <div class="invalid-feedback"><?php echo $title_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" value="<?php echo $subtitle; ?>">
                        </div>

                        <div class="form-group">
                            <label>Tagline</label>
                            <input type="text" name="tagline" class="form-control" value="<?php echo $tagline; ?>">
                        </div>

                        <div class="form-group">
                            <label>Current Image</label>
                            <?php if(!empty($current_image)): ?>
                                <img src="../../<?php echo $current_image; ?>" alt="Current Image" class="preview-image">
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>New Image (Optional)</label>
                            <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                            <?php if (!empty($image_err)): ?>
                                <div class="invalid-feedback"><?php echo $image_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Alt Text</label>
                            <input type="text" name="alt_text" class="form-control" value="<?php echo $alt_text; ?>">
                        </div>

                        <div class="form-group">
                            <label>Order Position</label>
                            <input type="number" name="order_position" class="form-control <?php echo (!empty($order_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $order_position; ?>">
                            <?php if (!empty($order_err)): ?>
                                <div class="invalid-feedback"><?php echo $order_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>>
                                Active
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="dark_overlay" <?php echo $dark_overlay ? 'checked' : ''; ?>>
                                Dark Overlay
                            </label>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Slide
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Preview image before upload
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'preview-image';
                const container = document.querySelector('.form-group:has(input[type="file"])');
                const oldPreview = container.querySelector('.preview-image');
                if (oldPreview) {
                    oldPreview.remove();
                }
                container.appendChild(preview);
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>
