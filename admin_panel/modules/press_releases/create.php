<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $content = $release_date = "";
$title_err = $content_err = $release_date_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }
    
    // Validate content
    if(empty(trim($_POST["content"]))){
        $content_err = "Please enter content.";
    } else {
        $content = trim($_POST["content"]);
    }
    
    // Validate release date
    if(empty(trim($_POST["release_date"]))){
        $release_date_err = "Please enter release date.";
    } else {
        $release_date = trim($_POST["release_date"]);
    }
    
    // If no errors, proceed with insertion
    if(empty($title_err) && empty($content_err) && empty($release_date_err)){
        $sql = "INSERT INTO press_releases (title, content, release_date, is_active) VALUES (?, ?, ?, 1)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_content, $param_release_date);
            
            $param_title = $title;
            $param_content = $content;
            $param_release_date = $release_date;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Press release added successfully.";
                // Clear form data after successful insertion
                $title = $content = $release_date = "";
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
    <title>Add Press Release</title>
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
                <li><a href="../about_section/"><i class="fas fa-info-circle"></i> About Section</a></li>
                <li><a href="../about_slider/"><i class="fas fa-images"></i> About Slider</a></li>
                <li><a href="../nav_menu_items/"><i class="fas fa-bars"></i> Navigation Menu</a></li>
                <li><a href="../hero_slides/"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
                <li><a href="../emergency_numbers/"><i class="fas fa-phone-alt"></i> Emergency Numbers</a></li>
                <li><a href="../news_items/"><i class="fas fa-newspaper"></i> News Items</a></li>
                <li><a href="../press_releases/" class="active"><i class="fas fa-file-alt"></i> Press Releases</a></li>
                <li><a href="../gallery/"><i class="fas fa-camera"></i> Gallery</a></li>
                <li><a href="../initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
                <li><a href="../services/"><i class="fas fa-hands-helping"></i> Services</a></li>
                <li><a href="../admin_users/"><i class="fas fa-users-cog"></i> Admin Users</a></li>
                <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        
        <main class="content">
            <div class="module-header">
                <h1>Add Press Release</h1>
                <a href="index.php" class="btn btn-secondary">Back to Press Releases</a>
            </div>

            <div class="form-container">
                <?php 
                if(!empty($success_msg)){
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                        <?php if(!empty($title_err)): ?>
                            <div class="invalid-feedback"><?php echo $title_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>"><?php echo $content; ?></textarea>
                        <?php if(!empty($content_err)): ?>
                            <div class="invalid-feedback"><?php echo $content_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Release Date</label>
                        <input type="date" name="release_date" class="form-control <?php echo (!empty($release_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $release_date; ?>">
                        <?php if(!empty($release_date_err)): ?>
                            <div class="invalid-feedback"><?php echo $release_date_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Press Release</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>