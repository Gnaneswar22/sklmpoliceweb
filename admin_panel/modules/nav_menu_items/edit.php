<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";


// Initialize variables
$id = $title = $link = $order_position = "";
$parent_id = null;
$is_active = 1;
$title_err = $link_err = $order_err = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    $sql = "SELECT * FROM nav_menu_items WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                
                $title = $row["title"];
                $parent_id = $row["parent_id"];
                $link = $row["link"];
                $order_position = $row["order_position"];
                $is_active = $row["is_active"];
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
    
    // Validate link
    if(empty(trim($_POST["link"]))){
        $link_err = "Please enter a link.";
    } else {
        $link = trim($_POST["link"]);
    }
    
    // Validate order
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }
    
    $parent_id = !empty($_POST["parent_id"]) ? trim($_POST["parent_id"]) : null;
    $is_active = isset($_POST["is_active"]) ? 1 : 0;
    
    if(empty($title_err) && empty($link_err) && empty($order_err)){
        $sql = "UPDATE nav_menu_items SET title=?, parent_id=?, link=?, order_position=?, is_active=? WHERE id=?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sissii", $param_title, $param_parent_id, $param_link, $param_order, $param_active, $param_id);
            
            $param_title = $title;
            $param_parent_id = $parent_id;
            $param_link = $link;
            $param_order = $order_position;
            $param_active = $is_active;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
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
    <title>Edit Navigation Menu Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="../../css/common.css">

</head>
<body>
    <div class="dashboard">
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
        </nav><!-- Add your sidebar navigation -->
      
        
        <div class="form-wrapper">
    <div class="form-container">
        <div class="form-header">
            <h2><?php echo isset($id) ? 'Edit' : 'Create' ?> Navigation Item</h2>
        </div>
        
        <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success_msg; ?>
        </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Add hidden input for id -->
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
    
    <div class="form-grid">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
            <?php if (!empty($title_err)): ?>
                <div class="invalid-feedback"><?php echo $title_err; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Parent Menu</label>
            <select name="parent_id" class="form-control">
                <option value="">None</option>
                <?php
                $sql = "SELECT id, title FROM nav_menu_items WHERE parent_id IS NULL AND id != ?";
                if($stmt = mysqli_prepare($conn, $sql)){
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    if(mysqli_stmt_execute($stmt)){
                        $result = mysqli_stmt_get_result($stmt);
                        while($row = mysqli_fetch_array($result)){
                            $selected = ($row['id'] == $parent_id) ? 'selected' : '';
                            echo "<option value='" . $row['id'] . "' " . $selected . ">" . htmlspecialchars($row['title']) . "</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Link</label>
        <input type="text" name="link" class="form-control <?php echo (!empty($link_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $link; ?>">
        <div class="help-text">Example: /about-us or https://example.com</div>
    </div>

    <div class="form-group">
        <label>Order Position</label>
        <input type="number" name="order_position" class="form-control <?php echo (!empty($order_err)) ? 'is-invalid' : ''; ?>" value="<?php echo isset($order_position) ? $order_position : ''; ?>">
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

    <div class="button-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
    </div>
</form>

    </div>
</div>
</body>
</html>
