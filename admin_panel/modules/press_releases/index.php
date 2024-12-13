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
    <title>Press Releases Management</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        

        .press-releases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .press-release-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 20px;
        }

        .press-release-info {
            padding: 15px;
        }

        .press-release-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .press-release-date {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }

        .press-release-content {
            color: #666;
            margin: 10px 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
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

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-top: 10px;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Content Area */
        .content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }

        /* Module Header */
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .module-header h1 {
            color: var(--light-text);
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
            }

            .content {
                margin-left: 0;
            }

            .press-releases-grid {
                grid-template-columns: 1fr;
            }
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
            <h1>Press Releases Management</h1>
            <div class="action-buttons">
            <button onclick="undoDelete()" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Undo Delete
        </button>
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Press Release
                </a>
            </div>
        </div>

        <div class="press-releases-grid">
            <?php
            $sql = "SELECT * FROM press_releases ORDER BY release_date DESC";
            $result = mysqli_query($conn, $sql);

            if($result) {
                while($release = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="press-release-item">
                        <div class="press-release-info">
                            <div class="press-release-title">
                                <?php echo htmlspecialchars($release['title']); ?>
                            </div>
                            <div class="press-release-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo date('F d, Y', strtotime($release['release_date'])); ?>
                            </div>
                            <div class="press-release-content">
                                <?php echo htmlspecialchars(substr($release['content'], 0, 200)) . '...'; ?>
                            </div>
                            <span class="status-badge <?php echo $release['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $release['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                        <div class="gallery-actions">
                            <a href="edit.php?id=<?php echo $release['id']; ?>" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $release['id']; ?>" 
                               class="action-btn delete-btn" 
                               onclick="return confirm('Are you sure you want to delete this press release?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    <?php
                }

                if (mysqli_num_rows($result) == 0) {
                    echo "<p class='no-items'>No press releases found.</p>";
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
