<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $description = $order_position = "";
$title_err = $description_err = $order_err = $media_err = "";
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
    
    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }
    
    // Handle image upload
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
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
            $upload_dir = "../../assets/images/gallery_highlights/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image_path = "assets/images/gallery_highlights/" . $new_filename;
            } else {
                $media_err = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $media_err = "Please select an image to upload.";
    }
    
    // If no errors, proceed with insertion
    if(empty($title_err) && empty($description_err) && empty($order_err) && empty($media_err)){
        $sql = "INSERT INTO gallery_highlights (title, description, image_path, order_position, is_active) VALUES (?, ?, ?, ?, 1)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_title, $param_description, $param_image_path, $param_order_position);
            
            $param_title = $title;
            $param_description = $description;
            $param_image_path = $image_path;
            $param_order_position = $order_position;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Gallery highlight added successfully.";
                // Clear form data after successful insertion
                $title = $description = $order_position = "";
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
    <title>Add Gallery Highlight</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Include your existing CSS */
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <!-- Include your sidebar navigation -->
        </nav>
        
        <main class="content">
            <div class="module-header">
                <h1>Add Gallery Highlight</h1>
                <a href="index.php" class="btn btn-secondary">Back to Gallery Highlights</a>
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
                        <input type="file" name="image" class="form-control <?php echo (!empty($media_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                        <?php if(!empty($media_err)): ?>
                            <div class="invalid-feedback"><?php echo $media_err; ?></div>
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
                        <button type="submit" class="btn btn-primary">Add Highlight</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>