<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$alt_text = $order_position = $current_image = "";
$alt_text_err = $media_err = $order_err = "";
$success_msg = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Fetch existing record
    $sql = "SELECT * FROM about_slider WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                $alt_text = $row["alt_text"];
                $order_position = $row["order_position"];
                $current_image = $row["image_path"];
            } else{
                header("location: index.php");
                exit();
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["id"]);
    
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
    
// Handle file upload if new file is selected
if(isset($_FILES["image"]) && $_FILES["image"]["error"] != 4){
    $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
    $filename = $_FILES["image"]["name"];
    $filetype = $_FILES["image"]["type"];
    $filesize = $_FILES["image"]["size"];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if(!array_key_exists($ext, $allowed)){
        $media_err = "Error: Please select a valid image format.";
    }

    $maxsize = 5 * 1024 * 1024;
    if($filesize > $maxsize){
        $media_err = "Error: File size is larger than the allowed limit.";
    }

    if(empty($media_err)){
        $upload_dir = "../../assets/images/about_slider/"; // Changed path to assets/images
        $new_filename = uniqid() . '.' . $ext;
        $target_file = $upload_dir . $new_filename;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $image_path = "assets/images/about_slider/" . $new_filename; // Changed path format
            
            // Delete old image
            if(!empty($current_image) && file_exists("../../" . $current_image)){
                unlink("../../" . $current_image);
            }
        } else {
            $media_err = "Sorry, there was an error uploading your file.";
        }
    }
} else {
    $image_path = $current_image;
}

    // Update record
    if(empty($alt_text_err) && empty($media_err) && empty($order_err)){
        $sql = "UPDATE about_slider SET image_path=?, alt_text=?, order_position=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssii", $param_image_path, $param_alt_text, $param_order_position, $param_id);
            
            $param_image_path = $image_path;
            $param_alt_text = $alt_text;
            $param_order_position = $order_position;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Slider item updated successfully.";
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
    <title>Edit Slider Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="../../css/auth.css">
    <style>
        /* Include your existing CSS styles */
    </style>
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
        <div class="logo-container">
            <img src="../../assets/images/logo.png" alt="Police Department Logo" class="logo">
        </div>
        <ul class="nav-links">
            <li><a href="../../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="../about_section/" class="active"><i class="fas fa-info-circle"></i> About Section</a></li>
            <li><a href="../about_slider/"><i class="fas fa-images"></i> About Slider</a></li>
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
                <h1>Edit Slider Item</h1>
                <a href="index.php" class="btn btn-secondary">Back to Slider</a>
            </div>

            <div class="form-container">
                <?php 
                if(!empty($success_msg)){
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="form-group">
                        <label>Current Image</label>
                        <img src="../../<?php echo $current_image; ?>" alt="Current Image" class="current-image">
                    </div>

                    <div class="form-group">
                        <label>New Image (Optional)</label>
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
                        <button type="submit" class="btn btn-primary">Update Slider Item</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
