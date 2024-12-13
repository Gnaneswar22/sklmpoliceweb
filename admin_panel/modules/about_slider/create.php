<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$alt_text = $order_position = "";
$alt_text_err = $media_err = $order_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate alt text
    if(empty(trim($_POST["alt_text"]))){
        $alt_text_err = "Please enter alt text.";
    } else {
        $alt_text = trim($_POST["alt_text"]);
    }
    
    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }
    
    // Handle file upload
if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
    $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
    $filename = $_FILES["image"]["name"];
    $filetype = $_FILES["image"]["type"];
    $filesize = $_FILES["image"]["size"];

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
        $upload_dir = "../../assets/images/about_slider/"; // Changed path to assets/images
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $new_filename = uniqid() . '.' . $ext;
        $target_file = $upload_dir . $new_filename;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $image_path = "assets/images/about_slider/" . $new_filename; // Changed path format
        } else {
            $media_err = "Sorry, there was an error uploading your file.";
        }
    }
} else {
    $media_err = "Please select a file to upload.";
}

    // If no errors, proceed with insertion
    if(empty($alt_text_err) && empty($media_err) && empty($order_err)){
        $sql = "INSERT INTO about_slider (image_path, alt_text, order_position, is_active) VALUES (?, ?, ?, 1)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssi", $param_image_path, $param_alt_text, $param_order_position);
            
            $param_image_path = $image_path;
            $param_alt_text = $alt_text;
            $param_order_position = $order_position;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Slider item added successfully.";
                // Clear form data after successful insertion
                $alt_text = $order_position = "";
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
    <title>Add Slider Item</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <!-- Include your sidebar navigation -->
            <div class="logo-container">
                <img src="../../assets/images/logo.png" alt="Police Department Logo" class="logo">
            </div>
           <ul class="nav-links">
            <li><a href="../../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="../about_section/"><i class="fas fa-info-circle"></i> About Section</a></li>
            <li><a href="../about_slider/" class="active"><i class="fas fa-images"></i> About Slider</a></li>
            <li><a href="../nav_menu_items/"><i class="fas fa-bars"></i> Navigation Menu</a></li>
            <li><a href="../hero_slides/"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
            <li><a href="../emergency_numbers/"><i class="fas fa-phone-alt"></i> Emergency Numbers</a></li>
            <li><a href="../news_items/"><i class="fas fa-newspaper"></i> News Items</a></li>
            <li><a href="../press_releases/"><i class="fas fa-file-alt"></i> Press Releases</a></li>
            <li><a href="../gallery/"><i class="fas fa-camera"></i> Gallery</a></li>
            <li><a href="../initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
            <li><a href="../services/"><i class="fas fa-hands-helping"></i> Services</a></li>
            <li><a href="../admin_users/"><i class="fas fa-users-cog"></i> Admin Users</a></li>
            <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        </nav>
        
        <main class="content">
            <div class="module-header">
                <h1>Add Slider Item</h1>
                <a href="index.php" class="btn btn-secondary">Back to Slider</a>
            </div>

            <div class="form-container">
                <?php 
                if(!empty($success_msg)){
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control <?php echo (!empty($media_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                        <?php if(!empty($media_err)): ?>
                            <div class="invalid-feedback"><?php echo $media_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Alt Text</label>
                        <input type="text" name="alt_text" class="form-control <?php echo (!empty($alt_text_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $alt_text; ?>">
                        <?php if(!empty($alt_text_err)): ?>
                            <div class="invalid-feedback"><?php echo $alt_text_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Order Position</label>
                        <input type="number" name="order_position" class="form-control <?php echo (!empty($order_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $order_position; ?>">
                        <?php if(!empty($order_err)): ?>
                            <div class="invalid-feedback"><?php echo $order_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Slider Item</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
