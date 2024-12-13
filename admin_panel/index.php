<?php
session_start();
require_once "includes/config.php";

// Define the time parsing function
function parseLocalTime($timeString) {
    preg_match('/<local_time>(.*?)<\/local_time>/', $timeString, $matches);
    if (isset($matches[1])) {
        return trim(explode(' in ', $matches[1])[0]);
    }
    return date('Y-m-d H:i:s');
}

// Define the timeString
$timeString = "<local_time>2024-12-11 22:24:40 in YYYY-MM-DD HH:mm:ss format</local_time>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
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

/* Main Content */
.content {
    flex: 1;
    margin-left: 280px;
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

/* Stats Container */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    padding: 10px;
}

/* Stat Cards */
.stat-card {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.stat-card i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: var(--secondary-color);
    animation: pulse 2s infinite;
}

.stat-card h3 {
    font-size: 1rem;
    color: var(--light-text);
    margin-bottom: 10px;
}

.stat-card p {
    font-size: 1.8rem;
    color: var(--accent-color);
    font-weight: 600;
}

/* Animations */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes glow {
    from {
        box-shadow: 0 0 5px rgba(98,0,234,0.2),
                    0 0 10px rgba(0,191,165,0.2);
    }
    to {
        box-shadow: 0 0 10px rgba(98,0,234,0.4),
                    0 0 20px rgba(0,191,165,0.4);
    }
}

/* Ripple Effect */
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Number Animation */
.animate-number {
    animation: numberAnimation 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

@keyframes numberAnimation {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    60% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--dark-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--hover-color);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .stats-container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 260px;
    }

    .content {
        margin-left: 0;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .stats-container {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
    }

    .stat-card {
        padding: 15px;
    }

    .stat-card i {
        font-size: 1.5rem;
    }

    .stat-card h3 {
        font-size: 0.9rem;
    }

    .stat-card p {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo-container">
                <img src="assets/images/logo.png" alt="Logo" class="logo">
            </div>
            <div class="user-info">
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h3>
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
                <li><a href="modules/gallery_highlights/" class="active"><i class="fas fa-star"></i> Gallery Highlights</a></li>
                <li><a href="modules/officers/"><i class="fas fa-user-tie"></i> Officers</a></li>
                <li><a href="modules/officers/"><i class="fas fa-phone"></i> Emergency Contacts</a></li>
                <li><a href="modules/initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
                <li><a href="modules/services/"><i class="fas fa-hands-helping"></i> Services</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="content">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <div class="date-time" id="current-time">
        <?php echo parseLocalTime($timeString); ?>
    </div>
            </div>

            <!-- Stats Container -->
            <div class="stats-container">
                <?php
                // Array of tables and their icons
                $tables = [
                    'nav_menu_items' => ['fas fa-bars', 'Navigation Items'],
                    'hero_slides' => ['fas fa-sliders-h', 'Hero Slides'],
                    'about_section' => ['fas fa-info-circle', 'About Sections'],
                    'about_slider' => ['fas fa-images', 'About Slides'],
                    'emergency_numbers' => ['fas fa-phone', 'Emergency Numbers'],
                    'news_items' => ['fas fa-newspaper', 'News Items'],
                    'press_releases' => ['fas fa-file-alt', 'Press Releases'],
                    'gallery' => ['fas fa-camera', 'Gallery Items'],
                    'initiatives' => ['fas fa-lightbulb', 'Initiatives'],
                    'services' => ['fas fa-hands-helping', 'Services']
                ];

                foreach($tables as $table => $info) {
                    $sql = "SELECT COUNT(*) as count FROM $table";
                    $result = mysqli_query($conn, $sql);
                    if($result) {
                        $row = mysqli_fetch_assoc($result);
                        ?>
                        <div class="stat-card">
                            <i class="<?php echo $info[0]; ?>"></i>
                            <h3><?php echo $info[1]; ?></h3>
                            <p><?php echo $row['count']; ?></p>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </main>
    </div>

    <script>
    // Add ripple effect to cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('click', function(e) {
            let ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Animate numbers when they come into view
    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-number');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card p').forEach(card => {
        observer.observe(card);
    });








    function updateTime() {
    const now = new Date();
    const formattedDate = now.getFullYear() + '-' + 
        String(now.getMonth() + 1).padStart(2, '0') + '-' + 
        String(now.getDate()).padStart(2, '0') + ' ' + 
        String(now.getHours()).padStart(2, '0') + ':' + 
        String(now.getMinutes()).padStart(2, '0') + ':' + 
        String(now.getSeconds()).padStart(2, '0');
    
    document.getElementById('current-time').textContent = formattedDate;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call



    </script>
</body>
</html>
