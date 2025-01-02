<?php
session_start();
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch comprehensive dashboard statistics
$stats = [
    // News Stats
    'total_news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'published_news' => $pdo->query("SELECT COUNT(*) FROM news WHERE status = 'published'")->fetchColumn(),
    'draft_news' => $pdo->query("SELECT COUNT(*) FROM news WHERE status = 'draft'")->fetchColumn(),
    
    // Press Releases
    'total_releases' => $pdo->query("SELECT COUNT(*) FROM press_releases")->fetchColumn(),
    'published_releases' => $pdo->query("SELECT COUNT(*) FROM press_releases WHERE status = 'published'")->fetchColumn(),
    
    // Initiatives
    'total_initiatives' => $pdo->query("SELECT COUNT(*) FROM initiatives")->fetchColumn(),
    'active_initiatives' => $pdo->query("SELECT COUNT(*) FROM initiatives WHERE status = 'active'")->fetchColumn(),
    
    // Police Stations
    'total_stations' => $pdo->query("SELECT COUNT(*) FROM police_stations")->fetchColumn(),
    
    // Emergency Contacts
    'total_emergency' => $pdo->query("SELECT COUNT(*) FROM emergency_contacts")->fetchColumn(),
    'active_emergency' => $pdo->query("SELECT COUNT(*) FROM emergency_contacts WHERE is_active = 1")->fetchColumn(),
    
    // Gallery
    'total_images' => $pdo->query("SELECT COUNT(*) FROM gallery WHERE media_type = 'image'")->fetchColumn(),
    'total_videos' => $pdo->query("SELECT COUNT(*) FROM gallery WHERE media_type = 'video'")->fetchColumn(),
    
    // Social Media
    'social_platforms' => $pdo->query("SELECT COUNT(*) FROM social_media WHERE is_active = 1")->fetchColumn(),
    
    // Users
    'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'admin_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn(),
    'editor_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'editor'")->fetchColumn()
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Srikakulam Police Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Previous CSS styles remain the same -->
    <!-- Add this new section for dashboard overview styles -->
    <style>

        .dashboard-section {
            display: none;
            margin-top: 20px;
        }

        .dashboard-section.active {
            display: block;
        }

        .section-title {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }
        :root {
    --primary-color: #1a237e;
    --secondary-color: #283593;
    --accent-color: #3949ab;
    --text-light: #ffffff;
    --text-dark: #333333;
    --hover-color: #303f9f;
    --border-color: #e0e0e0;
    --shadow: 0 2px 5px rgba(0,0,0,0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
}

body {
    background: #f5f5f5;
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    width: 280px;
    background: var(--primary-color);
    min-height: 100vh;
    padding: 20px 0;
    color: var(--text-light);
    position: fixed;
    box-shadow: var(--shadow);
    z-index: 1000;
}

.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-header img {
    width: 80px;
    margin-bottom: 10px;
    border-radius: 50%;
}

.sidebar-header h2 {
    font-size: 1.5rem;
    margin: 10px 0;
}

.sidebar-header p {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Navigation Menu */
.nav-menu {
    padding: 20px 0;
}

.nav-item {
    padding: 12px 25px;
    display: flex;
    align-items: center;
    color: var(--text-light);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.nav-item:hover {
    background: var(--secondary-color);
    border-left: 4px solid var(--accent-color);
}

.nav-item.active {
    background: var(--secondary-color);
    border-left: 4px solid var(--accent-color);
}

.nav-item i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Main Content Area */
.main-content {
    margin-left: 280px;
    padding: 30px;
    width: calc(100% - 280px);
    transition: all 0.3s ease;
}

/* Dashboard Header */
.dashboard-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: var(--shadow);
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.dashboard-header p {
    color: #666;
    font-size: 0.9rem;
}

/* Dashboard Sections */
.dashboard-section {
    display: none;
    animation: fadeIn 0.3s ease;
}

.dashboard-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.section-title {
    color: var(--primary-color);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--accent-color);
    font-size: 1.5rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: var(--shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.stat-card h3 {
    color: var(--primary-color);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2rem;
}

.stat-card h3 i {
    color: var(--accent-color);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--accent-color);
    margin: 10px 0;
}

.stat-card p {
    color: #666;
    font-size: 0.9rem;
}

/* Responsive Design */
@media screen and (max-width: 1024px) {
    .sidebar {
        width: 240px;
    }
    
    .main-content {
        margin-left: 240px;
        width: calc(100% - 240px);
    }
}

@media screen and (max-width: 768px) {
    .sidebar {
        width: 200px;
    }
    
    .main-content {
        margin-left: 200px;
        width: calc(100% - 200px);
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media screen and (max-width: 576px) {
    .sidebar {
        width: 70px;
    }
    
    .main-content {
        margin-left: 70px;
        width: calc(100% - 70px);
        padding: 15px;
    }
    
    .sidebar-header h2,
    .sidebar-header p,
    .nav-item span {
        display: none;
    }
    
    .nav-item {
        padding: 15px;
        justify-content: center;
    }
    
    .nav-item i {
        margin: 0;
        font-size: 1.2rem;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--accent-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Additional Utility Classes */
.text-primary {
    color: var(--primary-color);
}

.text-accent {
    color: var(--accent-color);
}

.bg-primary {
    background: var(--primary-color);
}

.bg-accent {
    background: var(--accent-color);
}

/* Loading Animation */
.loading {
    position: relative;
    min-height: 200px;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 40px;
    height: 40px;
    margin: -20px 0 0 -20px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--accent-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="images/Appolice.png" alt="Police Logo">
            <h2>Admin Panel</h2>
            <p>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></p>
        </div>

        <nav class="nav-menu">
            <a href="#dashboard" class="nav-item" data-section="main">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="#news" class="nav-item" data-section="news">
                <i class="fas fa-newspaper"></i> News Management
            </a>
            <a href="#press" class="nav-item" data-section="press">
                <i class="fas fa-file-alt"></i> Press Releases
            </a>
            <a href="#initiatives" class="nav-item" data-section="initiatives">
                <i class="fas fa-lightbulb"></i> Initiatives
            </a>
            <a href="#stations" class="nav-item" data-section="stations">
                <i class="fas fa-building"></i> Police Stations
            </a>
            <a href="#emergency" class="nav-item" data-section="emergency">
                <i class="fas fa-phone-alt"></i> Emergency Contacts
            </a>
            <a href="#gallery" class="nav-item" data-section="gallery">
                <i class="fas fa-images"></i> Gallery
            </a>
            <a href="#social" class="nav-item" data-section="social">
                <i class="fas fa-share-alt"></i> Social Media
            </a>
            <a href="#users" class="nav-item" data-section="users">
                <i class="fas fa-users"></i> User Management
            </a>
            <a href="#settings" class="nav-item" data-section="settings">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div class="main-content">
        <!-- Main Dashboard -->
        <div class="dashboard-section active" id="main-dashboard">
            <div class="dashboard-header">
                <h1>Main Dashboard</h1>
                <p>Last login: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><i class="fas fa-newspaper"></i> News</h3>
                    <p class="stat-number"><?php echo $stats['published_news']; ?></p>
                    <p>Published Articles</p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-lightbulb"></i> Initiatives</h3>
                    <p class="stat-number"><?php echo $stats['active_initiatives']; ?></p>
                    <p>Active Initiatives</p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-building"></i> Stations</h3>
                    <p class="stat-number"><?php echo $stats['total_stations']; ?></p>
                    <p>Police Stations</p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-phone-alt"></i> Emergency</h3>
                    <p class="stat-number"><?php echo $stats['active_emergency']; ?></p>
                    <p>Active Contacts</p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-phone-alt"></i>Gallery</h3>
                    <p class="stat-number"><?php echo $stats['total_videos']; ?></p>
                    <p>Gallery-viedos</p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-phone-alt"></i> Gallery</h3>
                    <p class="stat-number"><?php echo $stats['total_images']; ?></p>
                    <p>Gallery-images</p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-phone-alt"></i> Emergency</h3>
                    <p class="stat-number"><?php echo $stats['active_emergency']; ?></p>
                    <p>Active Contacts</p>
                </div>
            </div>
        </div>

        <!-- News Dashboard -->
        <div class="dashboard-section" id="news-dashboard">
            <h2 class="section-title">News Management Overview</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total News</h3>
                    <p class="stat-number"><?php echo $stats['total_news']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Published</h3>
                    <p class="stat-number"><?php echo $stats['published_news']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Drafts</h3>
                    <p class="stat-number"><?php echo $stats['draft_news']; ?></p>
                </div>
            </div>
        </div>

        <!-- Press Releases Dashboard -->
        <div class="dashboard-section" id="press-dashboard">
            <h2 class="section-title">Press Releases Overview</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Releases</h3>
                    <p class="stat-number"><?php echo $stats['total_releases']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Published</h3>
                    <p class="stat-number"><?php echo $stats['published_releases']; ?></p>
                </div>
            </div>
        </div>

        <!-- Similar sections for other dashboards -->
        <!-- Add sections for Initiatives, Police Stations, Emergency Contacts, Gallery, Social Media, Users, and Settings -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation handling
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if(this.getAttribute('href') !== 'logout.php') {
                        e.preventDefault();
                        const section = this.getAttribute('data-section');
                        showDashboard(section);
                    }
                });
            });

            function showDashboard(section) {
                // Hide all sections
                document.querySelectorAll('.dashboard-section').forEach(div => {
                    div.classList.remove('active');
                });
                
                // Show selected section
                const targetSection = document.getElementById(`${section}-dashboard`);
                if(targetSection) {
                    targetSection.classList.add('active');
                }

                // Update active nav item
                navItems.forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelector(`[data-section="${section}"]`).classList.add('active');
            }
        });
    </script>
</body>
</html>
