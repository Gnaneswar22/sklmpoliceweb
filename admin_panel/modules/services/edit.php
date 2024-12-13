<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $description = $link = $order_position = $current_icon = "";
$title_err = $description_err = $order_err = $media_err = "";
$success_msg = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Fetch existing record
    $sql = "SELECT * FROM services WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                $title = $row["title"];
                $description = $row["description"];
                $link = $row["link"];
                $order_position = $row["order_position"];
                $current_icon = $row["icon_path"];
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
    
    // Get link (optional)
    $link = !empty($_POST["link"]) ? trim($_POST["link"]) : "";
    
    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }
    
    // Handle icon upload if new file is selected
    $icon_path = $current_icon;
    if(isset($_FILES["icon"]) && $_FILES["icon"]["error"] != 4){
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["icon"]["name"];
        $filetype = $_FILES["icon"]["type"];
        $filesize = $_FILES["icon"]["size"];
    
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!array_key_exists($ext, $allowed)){
            $media_err = "Error: Please select a valid image format.";
        }
    
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize){
            $media_err = "Error: File size is larger than the allowed limit.";
        }
    
        if(empty($media_err)){
            $upload_dir = "../../assets/images/services/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file)){
                $icon_path = "assets/images/services/" . $new_filename;
                
                // Delete old icon if exists
                if(!empty($current_icon) && file_exists("../../" . $current_icon)){
                    unlink("../../" . $current_icon);
                }
            } else {
                $media_err = "Sorry, there was an error uploading your file.";
            }
        }
    }
    
    // Update record
    if(empty($title_err) && empty($description_err) && empty($order_err) && empty($media_err)){
        $sql = "UPDATE services SET title=?, description=?, icon_path=?, link=?, order_position=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssii", $param_title, $param_description, $param_icon_path, $param_link, $param_order_position, $param_id);
            
            $param_title = $title;
            $param_description = $description;
            $param_icon_path = $icon_path;
            $param_link = $link;
            $param_order_position = $order_position;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Service updated successfully.";
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
    <title>Edit Service</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Include your existing CSS */
        .current-icon {
            max-width: 100px;
            margin: 10px 0;
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
            <li><a href="../../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="../about_section/"><i class="fas fa-info-circle"></i> About Section</a></li>
            <li><a href="../about_slider/"><i class="fas fa-images"></i> About Slider</a></li>
            <li><a href="../nav_menu_items/"><i class="fas fa-bars"></i> Navigation Menu</a></li>
            <li><a href="../hero_slides/"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
            <li><a href="../emergency_numbers/"><i class="fas fa-phone-alt"></i> Emergency Numbers</a></li>
            <li><a href="../news_items/"><i class="fas fa-newspaper"></i> News Items</a></li>
            <li><a href="../press_releases/"><i class="fas fa-file-alt"></i> Press Releases</a></li>
            <li><a href="../gallery/"><i class="fas fa-camera"></i> Gallery</a></li>
            <li><a href="../initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
            <li><a href="../services/" class="active"><i class="fas fa-hands-helping"></i> Services</a></li>
            <li><a href="../admin_users/"><i class="fas fa-users-cog"></i> Admin Users</a></li>
            <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul><!-- Include your sidebar navigation -->
        </nav>
        
        <main class="content">
            <div class="module-header">
                <h1>Edit Service</h1>
                <a href="index.php" class="btn btn-secondary">Back to Services</a>
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

                    <?php if($current_icon): ?>
                        <div class="form-group">
                            <label>Current Icon</label>
                            <img src="../../<?php echo $current_icon; ?>" alt="Current Icon" class="current-icon">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>New Icon (Optional)</label>
                        <input type="file" name="icon" class="form-control <?php echo (!empty($media_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                        <?php if(!empty($media_err)): ?>
                            <div class="invalid-feedback"><?php echo $media_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Link (Optional)</label>
                        <input type="url" name="link" class="form-control" value="<?php echo $link; ?>">
                    </div>

                    <div class="form-group">
                        <label>Order Position</label>
                        <input type="number" name="order_position" class="form-control <?php echo (!empty($order_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $order_position; ?>">
                        <?php if(!empty($order_err)): ?>
                            <div class="invalid-feedback"><?php echo $order_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Service</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
