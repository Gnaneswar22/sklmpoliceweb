<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $tagline = $description = "";
$title_err = $tagline_err = $description_err = "";
$success_msg = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Fetch existing record
    $sql = "SELECT * FROM about_section WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                $title = $row["title"];
                $tagline = $row["tagline"];
                $description = $row["description"];
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
    
    // Validate tagline
    if(empty(trim($_POST["tagline"]))){
        $tagline_err = "Please enter a tagline.";
    } else {
        $tagline = trim($_POST["tagline"]);
    }
    
    // Validate description
    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";
    } else {
        $description = trim($_POST["description"]);
    }
    
    // Update record
    if(empty($title_err) && empty($tagline_err) && empty($description_err)){
        $sql = "UPDATE about_section SET title=?, tagline=?, description=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_title, $param_tagline, $param_description, $param_id);
            
            $param_title = $title;
            $param_tagline = $tagline;
            $param_description = $description;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "About section updated successfully.";
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
    <title>Edit About Section</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
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
            </ul><!-- Include the same sidebar navigation as in create.php -->
        </nav>
        <main class="content">
            <div class="module-header">
                <h1>Edit About Section</h1>
                <a href="index.php" class="btn btn-secondary">Back to About Sections</a>
            </div>

            <div class="form-container">
                <?php 
                if(!empty($success_msg)){
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                        <?php if(!empty($title_err)): ?>
                            <div class="invalid-feedback"><?php echo $title_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Tagline</label>
                        <input type="text" name="tagline" class="form-control <?php echo (!empty($tagline_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tagline; ?>">
                        <?php if(!empty($tagline_err)): ?>
                            <div class="invalid-feedback"><?php echo $tagline_err; ?></div>
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
                        <button type="submit" class="btn btn-primary">Update Section</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>