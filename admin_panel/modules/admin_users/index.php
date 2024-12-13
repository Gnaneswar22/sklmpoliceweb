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
    <title>Admin Users Management</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Base Styles */
        :root {
            --primary-color: #6200EA;
            --secondary-color: #00BFA5;
            --accent-color: #FF5252;
            --dark-bg: #1a1a1a;
            --light-text: #E8EAED;
            --card-bg: #2d2d2d;
            --hover-color: #7C4DFF;
            --sidebar-bg: #1E1E2D;
        }

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .user-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 20px;
        }

        .user-info {
            padding: 15px;
            text-align: center;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 2rem;
        }

        .username {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .created-date {
            color: #666;
            font-size: 0.9rem;
            margin: 10px 0;
        }

        /* Action Buttons */
        .action-btn {
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background-color: #ffc107;
            color: #000;
        }

        .edit-btn:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .gallery-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        /* Current User Indicator */
        .current-user {
            background-color: #e3f2fd;
            border: 2px solid var(--primary-color);
        }

        .current-user-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-top: 10px;
            display: inline-block;
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
            <li><a href="../services/"><i class="fas fa-hands-helping"></i> Services</a></li>
            <li><a href="../admin_users/" class="active"><i class="fas fa-users-cog"></i> Admin Users</a></li>
            <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
    <main class="content">
        <div class="module-header">
            <h1>Admin Users Management</h1>
            <div class="action-buttons">
            <button onclick="undoDelete()" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Undo Delete
        </button>
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Admin
                </a>
            </div>
        </div>

        <div class="users-grid">
            <?php
            $sql = "SELECT * FROM admin_users ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if($result) {
                while($user = mysqli_fetch_assoc($result)) {
                    $isCurrentUser = isset($_SESSION["username"]) && $_SESSION["username"] === $user["username"];
                    ?>
                    <div class="user-item <?php echo $isCurrentUser ? 'current-user' : ''; ?>">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="username">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </div>
                            <div class="created-date">
                                Created: <?php echo date('F d, Y', strtotime($user['created_at'])); ?>
                            </div>
                            <?php if($isCurrentUser): ?>
                                <span class="current-user-badge">Current User</span>
                            <?php endif; ?>
                        </div>
                        <?php if(!$isCurrentUser): ?>
                            <div class="gallery-actions">
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete.php?id=<?php echo $user['id']; ?>" 
                                   class="action-btn delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this admin user?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }

                if (mysqli_num_rows($result) == 0) {
                    echo "<p class='no-items'>No admin users found.</p>";
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            ?>
        </div>
    </main>
    
</div>
<script>
function undoDelete() {
    if(confirm('Do you want to restore the last deleted item?')) {
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
