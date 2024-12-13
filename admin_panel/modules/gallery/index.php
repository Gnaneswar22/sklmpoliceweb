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
    <title>Gallery Management</title>
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>

/* Main Content */
.content {
    flex: 1;
    margin-left: 380px;
    padding: 20px;
}

/* Dashboard Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.dashboard-header h1 {
    color: var(--light-text);
    font-size: 1.8rem;
    font-weight: 600;
}

/* Date Time Display */
.date-time {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 10px 20px;
    border-radius: 8px;
    font-family: 'Roboto Mono', monospace;
    font-size: 0.9rem;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    animation: glow 2s infinite alternate;
}


       
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;

        }

        .gallery-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-info {
            padding: 15px;
        }

        .gallery-info h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .gallery-actions {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #f8f9fa;
        }

        .btn-add {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-add i {
            margin-right: 5px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
        }

        .edit-btn {
            background: #ffc107;
        }

        .delete-btn {
            background: #dc3545;
        }

      /* Module Header Styles */

      /* Module Header Styles */
      .module-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.module-header h1 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin: 0;
    font-weight: 600;
}

/* Action Buttons Container */
.action-buttons {
    display: flex;
    gap: 15px;
    align-items: center;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    outline: none;
}

/* Primary Button */
.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
    transform: translateY(-1px);
}

/* Secondary Button */
.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-1px);
}

/* Icon Styles */
.btn i {
    font-size: 0.9em;
}

/* Local Time Styles */
local-time {
    display: inline-block;
    font-family: 'Courier New', monospace;
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 4px;
    color: #495057;
    font-size: 0.9rem;
    border: 1px solid #dee2e6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .module-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .action-buttons {
        width: 100%;
        justify-content: center;
    }

    .btn {
        padding: 8px 16px;
    }
}

/* Hover Effects */
.btn:active {
    transform: translateY(1px);
}

/* Optional: Add loading state for undo button */
.btn-secondary.loading {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-secondary.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Optional: Add tooltip for undo button */
.btn-secondary {
    position: relative;
}

.btn-secondary:hover::before {
    content: "Restore last deleted item";
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px 10px;
    background: #333;
    color: white;
    font-size: 0.8rem;
    border-radius: 4px;
    white-space: nowrap;
    opacity: 0.9;
    margin-bottom: 5px;
}

/* Optional: Add focus styles for accessibility */
.btn:focus {
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
    outline: none;
}

.btn-secondary:focus {
    box-shadow: 0 0 0 3px rgba(108, 117, 125, 0.3);
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
            <div class="module-header">
                <h1>Initiatives Management</h1>
                <div class="action-buttons">
        <button onclick="undoDelete()" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Undo Delete
        </button>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>
            </div>

            <div class="gallery-grid">
                <?php
                $sql = "SELECT * FROM gallery ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);

                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="gallery-item">
                        <img src="../../<?php echo htmlspecialchars($row['media_path']); ?>" 
                             alt="<?php echo htmlspecialchars($row['alt_text']); ?>">
                        <div class="gallery-info">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <span class="status-badge <?php echo $row['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                        <div class="gallery-actions">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" 
                               class="action-btn delete-btn" 
                               onclick="return confirm('Are you sure you want to delete this item?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    <?php
                }

                if(mysqli_num_rows($result) == 0) {
                    echo "<p class='no-items'>No gallery items found.</p>";
                }
                ?>
            </div>
        </main>
    </div>
       </div>
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
