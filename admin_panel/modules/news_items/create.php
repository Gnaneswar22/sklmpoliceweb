<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

$title = $content = $news_date = "";
$title_err = $content_err = $date_err = "";
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
    
    // Validate date
    if(empty(trim($_POST["news_date"]))){
        $date_err = "Please enter a date.";
    } else {
        $news_date = trim($_POST["news_date"]);
    }
    
    // If no errors, proceed with insertion
    if(empty($title_err) && empty($content_err) && empty($date_err)){
        $sql = "INSERT INTO news_items (title, content, news_date, is_active) VALUES (?, ?, ?, 1)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_content, $param_news_date);
            
            $param_title = $title;
            $param_content = $content;
            $param_news_date = $news_date;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "News item added successfully.";
                $title = $content = $news_date = "";
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
    <title>Add News Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
       :root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --accent-color: #4895ef;
    --background-color: #f8f9fa;
    --card-bg: #ffffff;
    --text-dark: #2d3436;
    --text-light: #636e72;
    --border-color: #e9ecef;
    --success-color: #4CAF50;
    --danger-color: #f44336;
    --warning-color: #ff9800;
}
/* Sidebar Styles */
.sidebar {
    width: 280px;
    background: #ffffff;
    color: #333;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    transition: all 0.3s ease;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.logo-container {
    padding: 20px;
    text-align: center;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.logo {
    max-width: 140px;
    height: auto;
}

.user-info {
    padding: 20px;
    text-align: center;
    background: #fff;
    border-bottom: 1px solid #e9ecef;
}

.user-info h3 {
    font-size: 1rem;
    color: #333;
    margin-bottom: 10px;
    font-weight: 500;
}

.nav-links {
    list-style: none;
    padding: 15px 0;
}

.nav-links li {
    margin: 5px 15px;
}

.nav-links a {
    color: #333;
    text-decoration: none;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.nav-links a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
    color: #666;
}

.nav-links a:hover, 
.nav-links a.active {
    background: #f8f9fa;
    color: var(--primary-color);
}

.nav-links a:hover i,
.nav-links a.active i {
    color: var(--primary-color);
}

/* Content area adjustment */
.content {
    margin-left: 280px;
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

/* Date Time in Sidebar */
.date-time {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    color: #666;
    margin-top: 10px;
    border: 1px solid #e9ecef;
    text-align: center;
}

/* Responsive Sidebar */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .content {
        margin-left: 0;
    }

    /* Add hamburger menu for mobile */
    .menu-toggle {
        display: block;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1001;
        background: var(--primary-color);
        color: white;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
    }
}

/* Scrollbar for Sidebar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}


/* Form Container */
.form-wrapper {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
}

.form-container {
    background: var(--card-bg);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Form Header */
.form-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
}

.form-header h2 {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: 600;
}

/* Form Groups */
.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-weight: 500;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 0.95rem;
    color: var(--text-dark);
    background-color: #fff;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

/* Select Dropdown */
select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 15px;
    padding-right: 45px;
}

/* Checkbox Style */
.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-container input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Validation Styles */
.form-control.is-invalid {
    border-color: var(--danger-color);
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='0 0 12 12'%3e%3cpath d='M6 0a6 6 0 1 0 0 12A6 6 0 0 0 6 0zm0 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm0-7a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 15px;
    padding-right: 45px;
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: var(--danger-color);
    font-size: 0.85rem;
}

/* Button Group */
.button-group {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

/* Buttons */
.btn {
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--background-color);
    color: var(--text-dark);
    border: 1px solid var(--border-color);
}

.btn-secondary:hover {
    background: #e9ecef;
}

/* Help Text */
.help-text {
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 5px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

/* Loading State */
.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin: -10px 0 0 -10px;
    border: 3px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Success Message */
.alert {
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background-color: rgba(76, 175, 80, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(76, 175, 80, 0.2);
}

.alert-danger {
    background-color: rgba(244, 67, 54, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(244, 67, 54, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-wrapper {
        padding: 10px;
    }

    .form-container {
        padding: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .button-group {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-container {
    animation: fadeIn 0.3s ease-out;
}


.preview-image {
    max-width: 300px;
    height: auto;
    border-radius: 8px;
    margin-top: 10px;
    border: 1px solid var(--border-color);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-container input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

        /* Main Content */
        .content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }

        /* Module Header */
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

    

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        /* Buttons */
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
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

      
        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        
        /* News Items Table */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .table th {
            background: white;
            color: #2c3e50;
            font-weight: 600;
        }

      

        .btn-edit {
            background: #ffc107;
            color: #000;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                transform: translateX(-100%);
            }

            .content {
                margin-left: 0;
            }

            .module-header {
                flex-direction: column;
                gap: 15px;
            }

            .table-container {
                overflow-x: auto;
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
        </nav>
        <main class="content">
            <div class="module-header">
                <h1>Add News Item</h1>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to News Items
                </a>
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
                        <label>Date</label>
                        <input type="date" name="news_date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $news_date; ?>">
                        <?php if(!empty($date_err)): ?>
                            <div class="invalid-feedback"><?php echo $date_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add News Item</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
