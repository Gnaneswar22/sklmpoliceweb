<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}
require_once "../../includes/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Slider Management</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="../../css/auth.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Base Styles */
      

        /* Include all your existing CSS */
        .slider-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .slider-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .slider-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .slider-info {
            padding: 15px;
            color: #333;
        }

        .order-position {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }

        .slider-actions {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #f8f9fa;
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
            <h1>About Slider Management</h1>
            <div class="action-buttons">
                <button onclick="undoDelete()" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Undo Delete
                </button>
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Slide
                </a>
            </div>
        </div>

        <div class="slider-grid">
            <?php
            $sql = "SELECT * FROM about_slider ORDER BY order_position ASC";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="slider-item">
                    <img src="../../<?php echo htmlspecialchars($row['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($row['alt_text']); ?>">
                    <div class="slider-info">
                        <div class="order-position">Order: <?php echo $row['order_position']; ?></div>
                        <div class="alt-text">Alt Text: <?php echo htmlspecialchars($row['alt_text']); ?></div>
                        <span class="status-badge <?php echo $row['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </div>
                    <div class="slider-actions">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" 
                           class="action-btn delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this slide?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
                <?php
            }

            if(mysqli_num_rows($result) == 0) {
                echo "<p class='no-items'>No slider items found.</p>";
            }
            ?>
        </div>
    </main>
</div>

<script>
function undoDelete() {
    if(confirm('Do you want to restore the last deleted slide?')) {
        fetch('undo.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'No items to restore');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while trying to restore the item');
        });
    }
}
</script>
</body>
</html>
