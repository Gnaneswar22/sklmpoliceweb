<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

// Initialize variables
$title = $link = $order_position = "";
$parent_id = null;
$is_active = 1;
$title_err = $link_err = $order_err = "";
$success_msg = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    
    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }
    
    // Check parent_id
    $parent_id = !empty($_POST["parent_id"]) ? trim($_POST["parent_id"]) : null;
    
    // Check is_active
    $is_active = isset($_POST["is_active"]) ? 1 : 0;
    
    // If no errors, proceed with insertion
    if(empty($title_err) && empty($link_err) && empty($order_err)){
        $sql = "INSERT INTO nav_menu_items (title, parent_id, link, order_position, is_active) VALUES (?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sisii", $param_title, $param_parent_id, $param_link, $param_order, $param_active);
            
            $param_title = $title;
            $param_parent_id = $parent_id;
            $param_link = $link;
            $param_order = $order_position;
            $param_active = $is_active;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Navigation item created successfully.";
                // Clear form data after successful insertion
                $title = $link = $order_position = "";
                $parent_id = null;
                $is_active = 1;
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Get current time
$timeString = "<local_time>2024-12-12 13:18:31 in YYYY-MM-DD HH:mm:ss format</local_time>";
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
    <title>Create Navigation Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/form.css">
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <div class="logo-container">
                <img src="../../assets/images/logo.png" alt="Logo" class="logo">
            </div>
            <div class="user-info">
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h3>
                <div class="date-time">
                    <?php echo parseLocalTime($timeString); ?>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="../../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../nav_menu_items/" class="active"><i class="fas fa-bars"></i> Navigation Menu</a></li>
                <li><a href="../hero_slides/"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
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
                        <h2>Create Navigation Item</h2>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                                    $sql = "SELECT id, title FROM nav_menu_items WHERE parent_id IS NULL";
                                    if($result = mysqli_query($conn, $sql)){
                                        while($row = mysqli_fetch_array($result)){
                                            $selected = ($row['id'] == $parent_id) ? 'selected' : '';
                                            echo "<option value='" . $row['id'] . "' " . $selected . ">" . htmlspecialchars($row['title']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control <?php echo (!empty($link_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $link; ?>">
                            <?php if (!empty($link_err)): ?>
                                <div class="invalid-feedback"><?php echo $link_err; ?></div>
                            <?php endif; ?>
                            <div class="help-text">Example: /about-us or https://example.com</div>
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

                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Item
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
