<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $description = $order_position = $current_image = "";
$title_err = $description_err = $order_err = $media_err = "";
$success_msg = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Fetch existing record
    $sql = "SELECT * FROM gallery_highlights WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                $title = $row["title"];
                $description = $row["description"];
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
    
    // Handle image upload if new file is selected
    $image_path = $current_image;
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
            $upload_dir = "../../assets/images/gallery_highlights/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            $target_file = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image_path = "assets/images/gallery_highlights/" . $new_filename;
                
                // Delete old image if exists
                if(!empty($current_image) && file_exists("../../" . $current_image)){
                    unlink("../../" . $current_image);
                }
            } else {
                $media_err = "Sorry, there was an error uploading your file.";
            }
        }
    }
    
    // Update record
    if(empty($title_err) && empty($description_err) && empty($order_err) && empty($media_err)){
        $sql = "UPDATE gallery_highlights SET title=?, description=?, image_path=?, order_position=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssii", $param_title, $param_description, $param_image_path, $param_order_position, $param_id);
            
            $param_title = $title;
            $param_description = $description;
            $param_image_path = $image_path;
            $param_order_position = $order_position;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Gallery highlight updated successfully.";
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
    <title>Edit Gallery Highlight</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Include your existing CSS */
        .current-image {
            max-width: 200px;
            margin: 10px 0;
            border-radius: 4px;
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
                <h1>Edit Gallery Highlight</h1>
                <a href="index.php" class="btn btn-secondary">Back to Gallery Highlights</a>
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

                    <?php if($current_image): ?>
                        <div class="form-group">
                            <label>Current Image</label>
                            <img src="../../<?php echo $current_image; ?>" alt="Current Image" class="current-image">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>New Image (Optional)</label>
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
                        <button type="submit" class="btn btn-primary">Update Highlight</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
