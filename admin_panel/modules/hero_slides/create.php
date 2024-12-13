<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $subtitle = $order_position = $image_path = $alt_text = "";
$is_active = 1;
$title_err = $image_err = $order_err = "";
$success_msg = "";

// Process form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate image
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
        $allowed = ["jpg" => "image/jpeg", "png" => "image/png", "gif" => "image/gif"];
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];
        
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)){
            $image_err = "Invalid file format. Only JPG, PNG, and GIF are allowed.";
        }

        if($filesize > 5 * 1024 * 1024){
            $image_err = "File size exceeds 5MB.";
        }

        if(empty($image_err)){
            $upload_dir = "../../uploads/hero_slides/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            $new_filename = uniqid() . "." . $ext;
            $image_path = $upload_dir . $new_filename;
            if(!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)){
                $image_err = "Failed to upload image.";
            }
        }
    } else {
        $image_err = "Please upload an image.";
    }

    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter an order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }

    // Alt text
    $alt_text = !empty($_POST["alt_text"]) ? trim($_POST["alt_text"]) : $title;

    // Is active
    $is_active = isset($_POST["is_active"]) ? 1 : 0;

    // If no errors, insert into database
    if(empty($title_err) && empty($image_err) && empty($order_err)){
        $sql = "INSERT INTO hero_slides (title, subtitle, image_path, alt_text, order_position, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssii", $param_title, $param_subtitle, $param_image_path, $param_alt_text, $param_order, $param_active);
            
            $param_title = $title;
            $param_subtitle = !empty($_POST["subtitle"]) ? trim($_POST["subtitle"]) : null;
            $param_image_path = str_replace("../../", "", $image_path);
            $param_alt_text = $alt_text;
            $param_order = $order_position;
            $param_active = $is_active;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Slide added successfully.";
                $title = $subtitle = $order_position = $alt_text = "";
                $is_active = 1;
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
// When saving image path to database


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hero Slide</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/form.css">


</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <div class="logo-container">
                <img src="../../assets/images/logo.png" alt="Logo" class="logo">
            </div>
            <ul class="nav-links">
                <li><a href="../../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../nav_menu_items/"><i class="fas fa-bars"></i> Navigation Menu</a></li>
                <li><a href="../hero_slides/" class="active"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
                <li><a href="../about_section/"><i class="fas fa-info-circle"></i> About Section</a></li>
                <li><a href="../about_slider/"><i class="fas fa-images"></i> About Slider</a></li>
                <li><a href="../news_items/"><i class="fas fa-newspaper"></i> News Items</a></li>
                <li><a href="../press_releases/"><i class="fas fa-file-alt"></i> Press Releases</a></li>
                <li><a href="../gallery/"><i class="fas fa-camera"></i> Gallery</a></li>
                <li><a href="../initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
                <li><a href="../services/"><i class="fas fa-cogs"></i> Services</a></li>
                <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="form-wrapper">
                <div class="form-container">
                    <div class="form-header">
                        <h2>Add Hero Slide</h2>
                    </div>

                    <?php if(!empty($success_msg)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <?php if(!empty($title_err)): ?>
                                <div class="invalid-feedback"><?php echo $title_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" value="<?php echo $subtitle; ?>">
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                            <?php if(!empty($image_err)): ?>
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
                            <?php if(!empty($order_err)): ?>
                                <div class="invalid-feedback"><?php echo $order_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>>
                                Active
                            </label>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Add Slide
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
</body>
</html>
