
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #1a237e;
            --secondary-color: #f4f6f9;
        }

        body {
            background-color: var(--secondary-color);
            font-family: Arial, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-color);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }

        .header {
            height: var(--header-height);
            background: white;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        /* Cards */
        .section-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-5px);
        }

        .section-card .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-card .card-body {
            padding: 20px;
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--secondary-color);
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        /* Action Buttons */
        .btn-action {
            padding: 4px 8px;
            font-size: 14px;
        }

        .btn-add {
            background: var(--primary-color);
            color: white;
        }

        .btn-add:hover {
            background: #283593;
            color: white;
        }

        /* Pagination */
        .pagination {
            margin: 20px 0 0 0;
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Search Box */
        .search-box {
            max-width: 300px;
        }

        /* Status Badge */
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .status-active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background: #ffebee;
            color: #c62828;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">Admin Panel</h4>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link" href="about_section.php">
                <i class="fas fa-info-circle"></i> About Section
            </a>
            <a class="nav-link" href="about_slider.php">
                <i class="fas fa-images"></i> About Slider
            </a>
            <a class="nav-link" href="hero_slides.php">
                <i class="fas fa-desktop"></i> Hero Slides
            </a>
            <a class="nav-link" href="services.php">
                <i class="fas fa-cogs"></i> Services
            </a>
            <a class="nav-link" href="initiatives.php">
                <i class="fas fa-lightbulb"></i> Initiatives
            </a>
            <a class="nav-link" href="gallery.php">
                <i class="fas fa-photo-video"></i> Gallery
            </a>
            <a class="nav-link" href="emergency_numbers.php">
                <i class="fas fa-phone"></i> Emergency Numbers
            </a>
            <a class="nav-link" href="news_items.php">
                <i class="fas fa-newspaper"></i> News Items
            </a>
            <a class="nav-link" href="press_releases.php">
                <i class="fas fa-file-alt"></i> Press Releases
            </a>
            <a class="nav-link" href="nav_menu.php">
                <i class="fas fa-bars"></i> Navigation Menu
            </a>
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h4>Dashboard</h4>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['admin_email']); ?>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="section-card">
                    <div class="card-body text-center">
                        <i class="fas fa-images fa-2x mb-2 text-primary"></i>
                        <h5>Total Slides</h5>
                        <h3>
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as count FROM hero_slides");
                            echo $result->fetch_assoc()['count'];
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
            <!-- Add more stat cards as needed -->
        </div>

        <!-- Recent Updates Section -->
        <div class="section-card">
            <div class="card-header">
                <h5 class="mb-0">Recent Updates</h5>
                <button class="btn btn-add">
                    <i class="fas fa-plus"></i> Add New
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample row -->
                            <tr>
                                <td>1</td>
                                <td>Welcome Slide</td>
                                <td>Hero Slides</td>
                                <td>
                                    <span class="status-badge status-active">Active</span>
                                </td>
                                <td>2024-12-10 06:19:35</td>
                                <td>
                                    <button class="btn btn-warning btn-action">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-action">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
