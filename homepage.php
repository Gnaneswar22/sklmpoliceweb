<?php
require_once 'config.php';

// Function to safely query database
function safeQuery($conn, $query) {
    try {
        $result = $conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Fetch navigation items
$nav_query = "SELECT * FROM nav_menu_items WHERE parent_id IS NULL ORDER BY order_position";
$main_menu_items = safeQuery($conn, $nav_query);

// Fetch hero slides
$slides_query = "SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY order_position";
$hero_slides = safeQuery($conn, $slides_query);

// Fetch emergency numbers
$emergency_query = "SELECT * FROM emergency_numbers WHERE is_active = 1";
$emergency_numbers = safeQuery($conn, $emergency_query);

// Fetch services
$services_query = "SELECT * FROM services WHERE is_active = 1 ORDER BY order_position";
$services = safeQuery($conn, $services_query);

// Fetch news
$news_query = "SELECT * FROM news WHERE is_active = 1 ORDER BY publish_date DESC LIMIT 3";
$news_items = safeQuery($conn, $news_query);

// Fetch press releases
$press_query = "SELECT * FROM press_releases WHERE is_active = 1 ORDER BY release_date DESC LIMIT 3";
$press_releases = safeQuery($conn, $press_query);

// Fetch gallery items
$gallery_query = "SELECT * FROM gallery WHERE is_active = 1 ORDER BY order_position LIMIT 6";
$gallery_items = safeQuery($conn, $gallery_query);

// Fetch initiatives
$initiatives_query = "SELECT * FROM initiatives WHERE is_active = 1 ORDER BY order_position";
$initiatives = safeQuery($conn, $initiatives_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Srikakulam Police Department</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="homestyle.css">
</head>
<body>



    <section class="header-section">
        <header class="header">
            <div class="logo-container">
                <img src="images/Appolice.png" alt="Left Logo" class="logo">
            </div>
            <h1 class="header-title">Srikakulam Police Department</h1>
            <div class="logo-container">
                <img src="images/Sklmlogo.png" alt="Right Logo" class="logo">
            </div>
            <div class="menu-toggle" id="menuToggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </header>

        <?php
require_once 'config.php';

// Function to get menu items
function getMenuItems($conn, $parent_id = null) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM nav_menu_items 
            WHERE parent_id IS :parent_id 
            AND is_active = 1 
            ORDER BY order_position
        ");
        $stmt->execute(['parent_id' => $parent_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}


// Get main menu items
$main_menu_items = getMenuItems($conn);
?>

<nav class="navbar">
    <ul class="nav-list" id="nav-list">
        <li><a href="#home">Home</a></li>
        <li class="dropdown-parent">
            <a href="#about">About Us</a>
            <ul class="dropdown">
                <li><a href="history.html">History</a></li>
                <li><a href="#">Organisation Chart</a></li>
                <li><a href="#">Awards</a></li>
            </ul>
        </li>
        <li class="dropdown-parent">
            <a href="#wings">Wings</a>
            <ul class="dropdown">
                <li><a href="law.html">Law & Order</a></li>
                <li><a href="traffic.html">Traffic</a></li>
                <li><a href="tel:100">Dial 100</a></li>
                <li><a href="#">AHTU</a></li>
            </ul>
        </li>
        <li class="dropdown-parent">
            <a href="#services">Services</a>
            <ul class="dropdown">
                <li><a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp">Lost Report</a></li>
                <li><a href="fir.html">View FIR</a></li>
                <li><a href="domestic.html">Domestic Violence</a></li>
                <li><a href="accident.html">Accident Analysis</a></li>
                <li><a href="https://services.india.gov.in/service/detail/apply-online-for-use-of-loud-speakers-1">Loud Speaker Permission</a></li>
            </ul>
        </li>
        <li><a href="contacts.html">Contact Us</a></li>
        
        <!-- Additional items from database -->
        <?php foreach ($main_menu_items as $item): 
            // Skip items that are already in static menu
            if (in_array($item['title'], ['Home', 'About Us', 'Wings', 'Services', 'Contact Us'])) continue;
            
            $submenu_items = getMenuItems($conn, $item['id']);
            $has_submenu = !empty($submenu_items);
        ?>
            <li <?php if ($has_submenu) echo 'class="dropdown-parent"'; ?>>
                <a href="<?php echo htmlspecialchars($item['link']); ?>">
                    <?php echo htmlspecialchars($item['title']); ?>
                </a>
                
                <?php if ($has_submenu): ?>
                    <ul class="dropdown">
                        <?php foreach ($submenu_items as $submenu): ?>
                            <li>
                                <a href="<?php echo htmlspecialchars($submenu['link']); ?>">
                                    <?php echo htmlspecialchars($submenu['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

    </section>
    <!-- Hero Section -->
    <?php
require_once 'config.php';

// Function to get hero slides
function getHeroSlides($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM hero_slides 
            WHERE is_active = 1 
            ORDER BY order_position
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

// Fetch slides from the database
$hero_slides = getHeroSlides($conn);
?>

<section class="hero-section">
    <div class="slider">
        <?php if (!empty($hero_slides)): ?>
            <?php foreach ($hero_slides as $slide): ?>
                <div class="slide">
                    <img src="<?php echo htmlspecialchars($slide['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($slide['alt_text']); ?>">
                    <div class="dark-overlay"></div>
                    <div class="slide-content content-overlay">
                        <div class="title-wrapper">
                            <h1 class="title"><?php echo htmlspecialchars($slide['title']); ?></h1>
                        </div>
                        <div class="subtitle-wrapper">
                            <p class="subtitle">
                                <?php 
                                // Replace {span} and {/span} with proper <span> tags
                                $formatted_subtitle = str_replace(
                                    ['{span}', '{/span}'], 
                                    ['<span>', '</span>'], 
                                    $slide['subtitle']
                                );
                                echo $formatted_subtitle; 
                                ?>
                            </p>
                        </div>
                        <p class="tagline"><?php echo htmlspecialchars($slide['tagline']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback content if no slides are found in the database -->
            <div class="slide">
                <img src="images/img.jpg" alt="Srikakulam Police Station">
                <div class="dark-overlay"></div>
                <div class="slide-content content-overlay">
                    <div class="title-wrapper">
                        <h1 class="title">Srikakulam Police Station</h1>
                    </div>
                    <div class="subtitle-wrapper">
                        <p class="subtitle">Serving <span>With Pride</span> Since 1956</p>
                    </div>
                    <p class="tagline">PROTECT • SERVE • UNITE</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="nav-buttons">
        <button class="nav-btn prev-btn">❮</button>
        <button class="nav-btn next-btn">❯</button>
    </div>

    <div class="slide-indicators"></div>
</section>
<?php
require_once 'config.php';

// Function to fetch emergency numbers from the database
function getEmergencyNumbers($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM emergency_numbers 
            WHERE is_active = 1 
            ORDER BY id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Get emergency numbers
$emergency_numbers = getEmergencyNumbers($conn);
?>

<div class="emergency-helpline">
    <div class="emergency-marquee">
        <div class="police-car">🚓</div>
        <div class="emergency-chain">
            <div class="helpline-content">
                <span class="emergency-label">EMERGENCY</span>
                <div class="helpline-numbers">
                    <?php foreach ($emergency_numbers as $number): ?>
                        <a href="tel:<?php echo htmlspecialchars($number['phone_number']); ?>" class="helpline-number">
                            <span class="phone-icon"><?php echo htmlspecialchars($number['icon']); ?></span>
                            <?php echo htmlspecialchars($number['service_name']); ?>: 
                            <?php echo htmlspecialchars($number['phone_number']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Services Section -->
    <section class="services">
        <h2>Services for Citizens</h2>
        <div class="service-items">
            <a href="#" class="service-item">
                <img src="images/police-station.png" alt="Police Station" class="service-icon">
                <h3>POLICE STATION</h3>
                <p>Locate your nearest police station</p>
            </a>

            <a href="#" class="service-item">
                <img src="images/emergency.png" alt="Emergency Contacts" class="service-icon">
                <h3>Emergency Contacts</h3>
                <p>Dial-100/101/108/181</p>
            </a>

            <a href="#" class="service-item">
                <img src="images/cyber.png" alt="Cyber Cell" class="service-icon">
                <h3>CYBER CELL</h3>
                <p>Report cyber crimes</p>
            </a>

            <a href="#" class="service-item">
                <img src="images/ceir.png" alt="CEIR" class="service-icon">
                <h3>CEIR</h3>
                <p>Block/unblock lost mobiles</p>
            </a>

            <a href="#" class="service-item">
                <img src="images/echalana.png" alt="e Challana" class="service-icon">
                <h3>e Challana</h3>
                <p>Digital Traffic Enforcement</p>
            </a>
        </div>
    </section>
    <!--about section-->
    <?php
require_once 'config.php';

// Function to get about section content
function getAboutContent($conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM about_section WHERE is_active = 1 LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return null;
    }
}

// Function to get slider images
function getSliderImages($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM about_slider 
            WHERE is_active = 1 
            ORDER BY order_position
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

// Get content and images
$about_content = getAboutContent($conn);
$slider_images = getSliderImages($conn);
?>

<section class="about-us">
    <div class="about-text">
        <h2><?php echo htmlspecialchars($about_content['title'] ?? 'About Srikakulam Police'); ?></h2>
        <p class="tagline"><?php echo htmlspecialchars($about_content['tagline'] ?? 'Srikakulam Police - Service with Pride'); ?></p>
        <p><?php echo htmlspecialchars($about_content['description'] ?? 'Description coming soon...'); ?></p>
    </div>
    
    <div class="about-slider">
        <div class="slides">
            <?php foreach ($slider_images as $index => $image): ?>
                <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($image['alt_text']); ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="slider-nav">
            <?php foreach ($slider_images as $index => $image): ?>
                <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

    
<?php
require_once 'config.php';

// Function to get latest news
function getLatestNews($conn, $limit = 3) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM news_items 
            WHERE is_active = 1 
            ORDER BY news_date DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

// Function to get press releases
function getPressReleases($conn, $limit = 3) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM press_releases 
            WHERE is_active = 1 
            ORDER BY release_date DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

// Get latest news and press releases
$news_items = getLatestNews($conn);
$press_releases = getPressReleases($conn);
?>

<div class="news-press-wrapper">
    <!-- Latest News Section -->
    <div class="news-container">
        <h2 class="section-title">Latest News</h2>
        <div class="news-ticker">
            <ul class="news-list">
                <?php foreach ($news_items as $news): ?>
                    <li class="news-item" onclick="showNewsPopup(<?php echo $news['id']; ?>, 'news')">
                        <span class="news-date">
                            <?php echo date('M d, Y', strtotime($news['news_date'])); ?>
                        </span>
                        <span class="news-title">
                            <?php echo htmlspecialchars($news['title']); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Press Releases Section -->
    <div class="press-container">
        <h2 class="section-title">Press Releases</h2>
        <div class="press-ticker">
            <ul class="press-list">
                <?php foreach ($press_releases as $press): ?>
                    <li class="press-item" onclick="showNewsPopup(<?php echo $press['id']; ?>, 'press')">
                        <span class="press-date">
                            <?php echo date('M d, Y', strtotime($press['release_date'])); ?>
                        </span>
                        <span class="press-title">
                            <?php echo htmlspecialchars($press['title']); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Popup Box -->
<div id="newsPopup" class="news-popup" style="display: none;">
    <div class="popup-content">
        <span class="close-button" onclick="closePopup()">&times;</span>
        <h3 id="popupTitle"></h3>
        <p id="popupDate"></p>
        <div id="popupContent"></div>
    </div>
</div>

<script>
function showNewsPopup(id, type) {
    fetch(`get_news_details.php?id=${id}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('popupTitle').textContent = data.title;
            document.getElementById('popupDate').textContent = 
                new Date(data.date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            document.getElementById('popupContent').innerHTML = data.content;
            document.getElementById('newsPopup').style.display = 'block';
        });
}

function closePopup() {
    document.getElementById('newsPopup').style.display = 'none';
}

// Close popup when clicking outside
window.onclick = function(event) {
    const popup = document.getElementById('newsPopup');
    if (event.target === popup) {
        closePopup();
    }
}
</script>



    <!-- Gallery Section -->
    <?php
require_once 'config.php';

// Function to get gallery items
function getGalleryItems($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM gallery 
            WHERE is_active = 1 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

// Fetch gallery items
$gallery_items = getGalleryItems($conn);
?>

<section class="gallery">
    <h2>Photo & Video Gallery</h2>
    <div class="gallery-container">
        <div class="gallery-grid">
            <?php foreach ($gallery_items as $item): ?>
                <div class="gallery-item" onclick="openMedia('<?php echo $item['media_path']; ?>', '<?php echo $item['media_type']; ?>', '<?php echo htmlspecialchars($item['title']); ?>')">
                    <?php if ($item['media_type'] === 'image'): ?>
                        <img src="<?php echo htmlspecialchars($item['media_path']); ?>" alt="<?php echo htmlspecialchars($item['alt_text']); ?>">
                    <?php elseif ($item['media_type'] === 'video'): ?>
                        <div class="video-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <video style="display: none;">
                            <source src="<?php echo htmlspecialchars($item['media_path']); ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="#" class="view-all-btn">View All</a>
    </div>
</section>

<!-- Modal for Image/Video -->
<div id="mediaModal" class="media-modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" onclick="closeMedia()">&times;</span>
        <div id="mediaContainer"></div>
        <h3 id="mediaTitle"></h3>
    </div>
</div>

<style>
    
/* Modal Styles */
.media-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    position: relative;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    max-width: 90%;
    max-height: 90%;
}

.modal-content img, .modal-content video {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 8px;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    color: #333;
    cursor: pointer;
    background: #fff;
    padding: 5px 10px;
    border-radius: 50%;
}
</style>

<script>
function openMedia(path, type, title) {
    const modal = document.getElementById('mediaModal');
    const mediaContainer = document.getElementById('mediaContainer');
    const mediaTitle = document.getElementById('mediaTitle');

    // Clear previous content
    mediaContainer.innerHTML = '';

    if (type === 'image') {
        const img = document.createElement('img');
        img.src = path;
        img.alt = title;
        mediaContainer.appendChild(img);
    } else if (type === 'video') {
        const video = document.createElement('video');
        video.controls = true;
        video.autoplay = true;
        const source = document.createElement('source');
        source.src = path;
        source.type = 'video/mp4';
        video.appendChild(source);
        mediaContainer.appendChild(video);
    }

    mediaTitle.textContent = title;
    modal.style.display = 'flex';
}

function closeMedia() {
    const modal = document.getElementById('mediaModal');
    modal.style.display = 'none';
}
</script>


    <!-- Initiatives Section -->
    <?php
require_once 'config.php';

// Function to get initiatives
function getInitiatives($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM initiatives 
            WHERE is_active = 1 
            ORDER BY order_position
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

$initiatives = getInitiatives($conn);
?>

<section class="initiatives">
    <h2>Our Initiatives</h2>
    <div class="carousel-container">
        <div class="carousel" id="initiativesCarousel">
            <?php foreach ($initiatives as $initiative): ?>
                <div class="carousel-card">
                    <img src="<?php echo htmlspecialchars($initiative['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($initiative['title']); ?>">
                    <h3><?php echo htmlspecialchars($initiative['title']); ?></h3>
                    <p><?php echo htmlspecialchars($initiative['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($initiative['link']); ?>">Read More</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="carousel-dots">
            <?php 
            $total_slides = ceil(count($initiatives) / 3);
            for($i = 0; $i < $total_slides; $i++): 
            ?>
                <span class="carousel-dot <?php echo $i === 0 ? 'active' : ''; ?>"></span>
            <?php endfor; ?>
        </div>
    </div>
</section>

<style>
.carousel-container {
    position: relative;
    overflow: hidden;
    padding: 20px;
}

.carousel {
    display: flex;
    transition: transform 0.5s ease;
    gap: 20px;
}

.carousel-card {
    min-width: calc(33.333% - 14px);
    flex: 0 0 calc(33.333% - 14px);
}

/* Keep your existing carousel-card styling */
</style>

<script>
// Keep your existing JavaScript initialization code
// Only add/modify these specific functions:

const carousel = document.getElementById('initiativesCarousel');
const dots = document.querySelectorAll('.carousel-dot');
let currentIndex = 0;

function updateCarousel(index) {
    const slideWidth = (100 / 3); // Width of three slides
    carousel.style.transform = `translateX(-${index * slideWidth}%)`;
    
    // Update dots
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

// Add click events to dots
dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        currentIndex = index;
        updateCarousel(currentIndex);
    });
});

// Optional: Auto-slide
setInterval(() => {
    currentIndex = (currentIndex + 1) % Math.ceil(carousel.children.length / 3);
    updateCarousel(currentIndex);
}, 5000); // Change slides every 5 seconds
</script>

<!-- Social Media Section -->
    <section class="social-media">
        <h2>Stay Connected</h2>
        <div class="social-container">
            <div class="social-accounts">
                <!-- Facebook Account -->
                <div class="social-account facebook">
                    <div class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-info">
                        <h3>Facebook</h3>
                        <p>@SrikakulamPolice</p>
                    </div>
                    <a href="#" class="social-follow">Follow</a>
                </div>

                <!-- Twitter Account -->
                <div class="social-account twitter">
                    <div class="social-icon">
                        <i class="fab fa-twitter"></i>
                    </div>
                    <div class="social-info">
                        <h3>Twitter</h3>
                        <p>@SPSrikakulam</p>
                    </div>
                    <a href="#" class="social-follow">Follow</a>
                </div>

                <!-- YouTube Account -->
                <div class="social-account youtube">
                    <div class="social-icon">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <div class="social-info">
                        <h3>YouTube</h3>
                        <p>Srikakulam Police Official</p>
                    </div>
                    <a href="#" class="social-follow">Subscribe</a>
                </div>

                <!-- Instagram Account -->
                <div class="social-account instagram">
                    <div class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="social-info">
                        <h3>Instagram</h3>
                        <p>@SrikakulamPolice</p>
                    </div>
                    <a href="#" class="social-follow">Follow</a>
                </div>
            </div>

            <!-- Social Media Feeds -->
            <div class="social-feeds">
                <!-- Facebook Feed -->
                <div class="feed-container">
                    <iframe
                        src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FSrikakulamPolice"
                        width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                        allowfullscreen="true">
                    </iframe>
                </div>

                <!-- Twitter Feed -->
                <div class="feed-container">
                    <a class="twitter-timeline" data-height="500" href="https://twitter.com/SPSrikakulam">
                        Tweets by Srikakulam Police
                    </a>
                </div>

                <!-- YouTube Feed -->
                <div class="feed-container">
                    <iframe width="100%" height="500"
                        src="https://www.youtube.com/embed?listType=user_uploads&list=SrikakulamPolice" frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-sections">
            <!-- Logo Section -->
            <div class="footer-section">
                <img src="images/sklmplc.png" alt="Police Department Logo" class="footer-logo">
            </div>
    
            <!-- Quick Links Section -->
            <div class="footer-section">
                <h3 class="footer-heading">Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
    
            <!-- Contact Info Section -->
            <div class="footer-section">
                <h3 class="footer-heading">Contact Info</h3>
                <p>Email: <a href="mailto:info@policedepartment.com">info@policedepartment.com</a></p>
                <p>Phone: <a href="tel:+1234567890">+1 234 567 890</a></p>
            </div>
        </div>
        <p class="footer-credits">© 2024 Police Department. All Rights Reserved.</p>
    </footer>  

    <local_time>
    <?php echo date('Y-m-d H:i:s'); ?> in YYYY-MM-DD HH:mm:ss format
</local_time>

  



    <script>
// Wait for DOM to be fully loaded before initializing
document.addEventListener('DOMContentLoaded', function() {
    initHeroSlider();
    initEmergencyHelpline();
    initPressTicker();
    initNavigationMenu();
    initNewsPopup();
});

// 1. Hero Section Slider Implementation
function initHeroSlider() {
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelector('.slide-indicators');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentSlide = 0;
    let slideInterval;

    function createIndicators() {
        slides.forEach((_, index) => {
            const indicator = document.createElement('div');
            indicator.classList.add('indicator');
            if (index === 0) indicator.classList.add('active');
            indicator.addEventListener('click', () => goToSlide(index));
            indicators.appendChild(indicator);
        });
    }

    function updateSlide() {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[currentSlide].classList.add('active');
        
        document.querySelectorAll('.indicator').forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlide();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        updateSlide();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlide();
        resetInterval();
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }

    function startInterval() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    // Initialize slider
    createIndicators();
    updateSlide();
    startInterval();

    // Event Listeners
    prevBtn?.addEventListener('click', () => {
        prevSlide();
        resetInterval();
    });

    nextBtn?.addEventListener('click', () => {
        nextSlide();
        resetInterval();
    });

    const heroSection = document.querySelector('.hero-section');
    heroSection?.addEventListener('mouseenter', () => clearInterval(slideInterval));
    heroSection?.addEventListener('mouseleave', () => startInterval());
}

// 2. Emergency Helpline Implementation
function initEmergencyHelpline() {
    const helplineContent = document.querySelector('.helpline-scroll');
    
    if (helplineContent) {
        helplineContent.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
        });

        helplineContent.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
        });
    }
}

// 3. Press Ticker Implementation
function initPressTicker() {
    const newsData = [
        {
            title: "Latest Police Updates - December 08, 2024",
            date: "2024-12-08",
            link: "#"
        },
        // Add more news items as needed
    ];

    const pressData = [
        {
            title: "Police Department Press Release",
            date: "2024-12-08",
            link: "#"
        },
        // Add more press items as needed
    ];

    function createTickerItem(data, isNews = true) {
        const item = document.createElement('li');
        item.className = isNews ? 'news-item' : 'press-item';
        item.onclick = () => showNews(item);

        const dateSpan = document.createElement('span');
        dateSpan.className = isNews ? 'news-date' : 'press-date';
        dateSpan.textContent = new Date(data.date).toLocaleDateString();

        const titleSpan = document.createElement('span');
        titleSpan.className = isNews ? 'news-title' : 'press-title';
        titleSpan.textContent = data.title;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'news-content';
        contentDiv.style.display = 'none';
        contentDiv.innerHTML = `<p>Detailed content for ${data.title}</p>`;

        item.appendChild(dateSpan);
        item.appendChild(titleSpan);
        item.appendChild(contentDiv);
        return item;
    }

    const newsList = document.querySelector('.news-list');
    const pressList = document.querySelector('.press-list');

    if (newsList) {
        newsData.forEach(data => newsList.appendChild(createTickerItem(data, true)));
    }
    if (pressList) {
        pressData.forEach(data => pressList.appendChild(createTickerItem(data, false)));
    }
}

// 4. Navigation Menu Implementation
function initNavigationMenu() {
    const menuToggle = document.getElementById('menuToggle');
    const navbar = document.querySelector('.navbar');
    const dropdownParents = document.querySelectorAll('.dropdown-parent');

    function toggleMenu() {
        menuToggle?.classList.toggle('active');
        navbar?.classList.toggle('active');
        document.body.style.overflow = navbar?.classList.contains('active') ? 'hidden' : '';
    }

    menuToggle?.addEventListener('click', toggleMenu);

    dropdownParents.forEach(parent => {
        const link = parent.querySelector('a');
        link?.addEventListener('click', (e) => {
            e.preventDefault();
            parent.classList.toggle('active');
            
            dropdownParents.forEach(otherParent => {
                if (otherParent !== parent) {
                    otherParent.classList.remove('active');
                }
            });
        });
    });

    // Close menu on outside click and ESC key
    document.addEventListener('click', (e) => {
        if (navbar?.classList.contains('active') && 
            !navbar.contains(e.target) && 
            !menuToggle?.contains(e.target)) {
            toggleMenu();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (navbar?.classList.contains('active')) {
                toggleMenu();
            }
            dropdownParents.forEach(parent => parent.classList.remove('active'));
        }
    });
}

// 5. News Popup Implementation
function initNewsPopup() {
    // Add popup HTML if not already present
    if (!document.getElementById('newsPopup')) {
        document.body.insertAdjacentHTML('beforeend', `
            <div id="newsPopup" class="news-popup">
                <div class="popup-content">
                    <span class="close-button" onclick="closePopup()">&times;</span>
                    <h3 id="popupTitle"></h3>
                    <p id="popupDate"></p>
                    <div id="popupContent"></div>
                </div>
            </div>
        `);
    }
}

function showNews(newsItem) {
    const popup = document.getElementById('newsPopup');
    const title = newsItem.querySelector('.news-title, .press-title')?.textContent;
    const date = newsItem.querySelector('.news-date, .press-date')?.textContent;
    const content = newsItem.querySelector('.news-content')?.innerHTML;

    if (popup && title && date && content) {
        document.getElementById('popupTitle').textContent = title;
        document.getElementById('popupDate').textContent = date;
        document.getElementById('popupContent').innerHTML = content;
        popup.style.display = 'block';
    }
}

function closePopup() {
    const popup = document.getElementById('newsPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

// Event listener for clicking outside popup
window.onclick = function(event) {
    const popup = document.getElementById('newsPopup');
    if (event.target === popup) {
        closePopup();
    }
}

// emergency car
document.addEventListener('DOMContentLoaded', function() {
    function startCarAnimation() {
        const car = document.querySelector('.police-car');
        car.style.animation = 'none';
        car.offsetHeight; // Trigger reflow
        car.style.animation = 'driveIn 5s ease-out forwards';
    }

    // Start initial animation
    startCarAnimation();

    // Restart animation when marquee completes a cycle
    const marquee = document.querySelector('marquee');
    marquee.addEventListener('finish', startCarAnimation);

    // Optional: Restart animation when marquee starts after being paused
    marquee.addEventListener('start', startCarAnimation);
});






    </script>


<script>
function updateLocalTime() {
    const now = new Date();
    const formattedTime = now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0') + ' ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
    
    document.getElementById('localTime').textContent = formattedTime + ' in YYYY-MM-DD HH:mm:ss format';
}

// Update the time every second
setInterval(updateLocalTime, 1000);

// Initial time update
updateLocalTime();
</script>

</body>
</html>