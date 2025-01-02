<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Srikakulam Police Department</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
</head>

<body>
 <section class="header-section">
        <header class="header">
            <div class="logo-container">
                <img src="images/Appolice.png" alt="Left Logo" class="logo">
            </div>
            <h1 class="header-title">  <span class="top-line">Srikakulam District</span>
                <span class="bottom-line">police</span></h1>
            <div class="logo-container">
                <img src="images/Sklmlogo.png" alt="Right Logo" class="logo">
            </div>
        </header>
   
        <nav class="navbar">
            <!-- Add menu toggle button for mobile -->
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
    
            <ul class="nav-list" id="nav-list">
                <li><a href="homepage.php">Home</a></li>
                <li class="dropdown-parent">
                    <a href="#about">About Us</a>
                    <ul class="dropdown">
                        <li><a href="history.html">History</a></li>
                        <li><a href="organisation.html">Organisation Chart</a></li>
                        <li><a href="awards.html">Awards</a></li>
                    </ul>
                </li>
                <li class="dropdown-parent">
                    <a href="#wings">Wings</a>
                    <ul class="dropdown">
                        <li><a href="law.html">Law & Order</a></li>
                        <li><a href="traffic.html">Traffic</a></li>
                        <li><a href="tel:100">Dial 100</a></li>
                        <li><a href="AHTU.html">AHTU</a></li>
                    </ul>
                </li>
                <li class="dropdown-parent">
                    <a href="#services">Services</a>
                    <ul class="dropdown">
                        <li><a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp">Block/Unblock Stolen/Lost Mobile</a></li>
                        <li><a href="fir.html">View FIR </a></li>
                        <li><a href="https://www.passportindia.gov.in/AppOnlineProject/welcomeLink">Passport Seva</a></li>
                        <li><a href="https://citizen.appolice.gov.in/jsp/userDownload.do?method=viewApprovedFiles">Download Forms</a></li>
                        <li><a href="services.html">View All</a></li>
                    </ul>
                </li>
                
                <li><a href="contacts.html">Contact Us</a></li>
            </ul>
        </nav>
    </section>
    <!-- Hero Section -->
    <?php
// Database connection
$db_config = [
    'host' => 'localhost',
    'dbname' => 'srikakulam_police',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']}", 
        $db_config['username'], 
        $db_config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch slider content with description field
    $stmt = $pdo->query("SELECT * FROM slider_content WHERE is_active = 1 ORDER BY order_num");
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $slides = [];
}
?>

<section class="slider-section">
    <div class="slider-container">
        <?php if (!empty($slides)): ?>
            <?php foreach($slides as $slide): ?>
                <?php if($slide['type'] == 'regular'): ?>
                    <div class="slide">
                        <img src="<?php echo htmlspecialchars($slide['image_url'] ?? 'images/default.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($slide['title'] ?? 'Srikakulam Police'); ?>">
                        <div class="overlay-1"></div>
                        <div class="overlay-2"></div>
                        <div class="slide-text">
                            <h1><?php echo htmlspecialchars($slide['title'] ?? 'Srikakulam Police'); ?></h1>
                            <p><?php echo htmlspecialchars($slide['description'] ?? 'Serving City Of Joy Since 1856'); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Message Slide -->
                    <div class="slide">
                        <img src="<?php echo htmlspecialchars($slide['image_url'] ?? 'images/srikakulam.jpg'); ?>" 
                             alt="srikakulam">
                        <div class="commissioner-slide">
                            <div class="message-container">
                                <div class="message-content">
                                    <div class="message-header">
                                        <h2 class="msg">Message from</h2>
                                        <h1 class="sp">Sri.K.V.Maheswara Reddy,I.P.S</h1>
                                        <h3 class="tag">Superintendent of Police, Srikakulam</h3>
                                    </div>
                                    <div class="message-text">
                                        <p class="p1">It is a great pleasure to welcome you to the Official Website of Srikakulam Police. Srikakulam Police, with an illustrious history of excellence in all aspects of policing, is committed to its responsibilities towards maintenance of law and order in the city, managing traffic, prevention and detection of crime and spearheading various citizen friendly initiatives for the people of Srikakulam.</p>
                                        <a href="message.html"><button class="read-more">Read More</button></a>
                                    </div>
                                </div>
                                <div class="commissioner-image">
                                    <img src="images/2.png" alt="Superintendent of Police">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Default slide if no data -->
            <div class="slide">
                <img src="images/sp.jpg" alt="Srikakulam Police">
                <div class="overlay-1"></div>
                <div class="overlay-2"></div>
                <div class="slide-text">
                    <h1>Srikakulam Police</h1>
                    <p>Serving City Of Joy Since 1856</p>
                </div>
            </div>
        <?php endif; ?>

        <button class="nav-button prev">&lt;</button>
        <button class="nav-button next">&gt;</button>
    </div>
</section>

<?php
// Database connection
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=srikakulam_police", 
        "root", 
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch emergency contacts
    $stmt = $pdo->query("
        SELECT * FROM emergency_contacts 
        WHERE is_active = 1 
        ORDER BY id
    ");
    $emergency_contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    // Default emergency contacts if database fails
    $emergency_contacts = [
        ['service_name' => 'Police', 'phone_number' => '100'],
        ['service_name' => 'Women', 'phone_number' => '1091'],
        ['service_name' => 'Child', 'phone_number' => '1098'],
        ['service_name' => 'Ambulance', 'phone_number' => '108'],
        ['service_name' => 'Fire', 'phone_number' => '101'],
        ['service_name' => 'Emergency', 'phone_number' => '112']
    ];
}

// Get current time
$current_time = date('Y-m-d H:i:s');
?>

<div class="emergency-helpline">
    <div class="emergency-marquee">
        <div class="police-car">🚓</div>
        <div class="emergency-chain">
            <div class="helpline-content">
                <span class="emergency-label">EMERGENCY</span>
                <div class="helpline-numbers">
                    <?php foreach($emergency_contacts as $contact): ?>
                        <a href="tel:<?php echo htmlspecialchars($contact['phone_number']); ?>" 
                           class="helpline-number">
                            <span class="phone-icon">📞</span>
                            <?php echo htmlspecialchars($contact['service_name']); ?>: 
                            <?php echo htmlspecialchars($contact['phone_number']); ?>
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
            <a href="know.html" class="service-item">
                <img src="images/police-station.png" alt="Police Station" class="service-icon">
                <h3>POLICE STATION</h3>
                <p>Locate your nearest police station</p>
            </a>

            <a href="emergency.php" class="service-item">
                <img src="images/emergency.png" alt="Emergency Contacts" class="service-icon">
                <h3>Emergency Contacts</h3>
                <p>Dial-100/101/108/181</p>
            </a>

            <a href="#" class="service-item">
                <img src="images/cyber.png" alt="Cyber Cell" class="service-icon">
                <h3>CYBER CELL</h3>
                <p>Report cyber crimes</p>
            </a>

            <a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp" class="service-item">
                <img src="images/ceir.png" alt="CEIR" class="service-icon">
                <h3>CEIR</h3>
                <p>Block/unblock lost mobiles</p>
            </a>

            <a href="https://aptonline.in/CitizenPortal/CITIZENPORTAL/ECHALLAN_AP_BILLPAY.aspx?value=Usxttk74q1GYhR2T/Be6/mE9hJz0qPHX9uyIZUOOMzdD5sX36J5o6WDI7Zoniy3T" class="service-item">
                <img src="images/echalana.png" alt="e Challana" class="service-icon">
                <h3>e Challana</h3>
                <p>Digital Traffic Enforcement</p>
            </a>
        </div>
    </section>
    <!--about section-->
    
    


    <!--news section-->
    <?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=srikakulam_police", 
        "root", 
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch latest news
    $news_query = $pdo->query("
        SELECT title, content, published_date 
        FROM news 
        WHERE status = 'published' 
        ORDER BY published_date DESC 
        LIMIT 3
    ");
    $news_items = $news_query->fetchAll(PDO::FETCH_ASSOC);

    // Fetch press releases
    $press_query = $pdo->query("
        SELECT title, published_date, pdf_url 
        FROM press_releases 
        WHERE status = 'published' 
        ORDER BY published_date DESC 
        LIMIT 3
    ");
    $press_releases = $press_query->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $news_items = $press_releases = [];
}
?>

<div class="news-press-wrapper">
    <!-- Latest News Section -->
    <div class="news-container">
        <h2 class="section-title">Latest News</h2>
        <div class="news-ticker">
            <ul class="news-list">
                <?php if (!empty($news_items)): ?>
                    <?php foreach($news_items as $news): ?>
                        <li class="news-item" data-id="<?php echo htmlspecialchars($news['id'] ?? ''); ?>"
                            onclick="showNewsPopup(this)">
                            <span class="news-date">
                                <?php echo date('M d, Y', strtotime($news['published_date'])); ?>
                            </span>
                            <span class="news-title">
                                <?php echo htmlspecialchars($news['title']); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="news-item">
                        <span class="news-date">
                            <?php echo date('M d, Y'); ?>
                        </span>
                        <span class="news-title">No news available</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Press Releases Section -->
    <div class="press-container">
        <h2 class="section-title">Press Releases</h2>
        <div class="press-ticker">
            <ul class="press-list">
                <?php if (!empty($press_releases)): ?>
                    <?php foreach($press_releases as $press): ?>
                        <li class="press-item" data-pdf="<?php echo htmlspecialchars($press['pdf_url'] ?? ''); ?>"
                            onclick="showPressPopup(this)">
                            <span class="press-date">
                                <?php echo date('M d, Y', strtotime($press['published_date'])); ?>
                            </span>
                            <span class="press-title">
                                <?php echo htmlspecialchars($press['title']); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="press-item">
                        <span class="press-date">
                            <?php echo date('M d, Y'); ?>
                        </span>
                        <span class="press-title">No press releases available</span>
                    </li>
                <?php endif; ?>
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
        <div class="popup-footer">
            <button class="know-more-btn" onclick="downloadPDF()">
                <span class="btn-text">Know More</span>
                <span class="btn-icon">
                    <i class="fas fa-file-pdf"></i>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
function showNewsPopup(element) {
    const popup = document.getElementById('newsPopup');
    const title = element.querySelector('.news-title').textContent;
    const date = element.querySelector('.news-date').textContent;
    
    document.getElementById('popupTitle').textContent = title;
    document.getElementById('popupDate').textContent = date;
    popup.style.display = 'block';
}

function showPressPopup(element) {
    const pdfUrl = element.dataset.pdf;
    if (pdfUrl) {
        window.open(pdfUrl, '_blank');
    }
}

function closePopup() {
    document.getElementById('newsPopup').style.display = 'none';
}

function downloadPDF() {
    // Add PDF download functionality here
    console.log('Downloading PDF...');
}
</script>




<?php
// Set timezone and get current time
date_default_timezone_set('Asia/Kolkata');
$current_time = date('Y-m-d H:i:s');

try {
    // Database connection
    $pdo = new PDO(
        "mysql:host=localhost;dbname=srikakulam_police", 
        "root", 
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch gallery items
    $stmt = $pdo->query("
        SELECT * FROM gallery 
        WHERE status = 'active' 
        ORDER BY display_order 
        LIMIT 6
    ");
    $gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $gallery_items = [];
}
?>



<!-- Gallery Section -->
<section class="gallery">
    <h2>Photo & Video Gallery</h2>
    <div class="gallery-container">
        <div class="gallery-grid">
            <?php if (!empty($gallery_items)): ?>
                <?php foreach($gallery_items as $item): ?>
                    <div class="gallery-item" 
                         data-type="<?php echo htmlspecialchars($item['media_type']); ?>"
                         data-src="<?php echo htmlspecialchars($item['media_url']); ?>"
                         data-title="<?php echo htmlspecialchars($item['title']); ?>">
                        
                        <?php if($item['media_type'] == 'image'): ?>
                            <img src="<?php echo htmlspecialchars($item['thumbnail_url'] ?? $item['media_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php else: ?>
                            <div class="video-thumbnail" 
                                 style="background-image: url('<?php echo htmlspecialchars($item['thumbnail_url']); ?>')">
                            </div>
                        <?php endif; ?>

                        <div class="gallery-overlay">
                            <i class="fas fa-<?php echo $item['media_type'] == 'image' ? 'search-plus' : 'play'; ?>"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default items matching hero section image locations -->
                <div class="gallery-item" data-type="image" data-src="images/sp.jpg" data-title="Flag Ceremony">
                    <img src="images/sp.jpg" alt="Flag Ceremony">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item" data-type="image" data-src="images/policepared.jpg" data-title="Police Parade">
                    <img src="images/policepared.jpg" alt="Police Parade">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item" data-type="image" data-src="images/station.jpg" data-title="Police Station">
                    <img src="images/station.jpg" alt="Police Station">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <!-- Video Items -->
                <div class="gallery-item" data-type="video" data-src="video-url" data-title="Police Training">
                    <img src="images/srikakulam.jpg" alt="Police Training">
                    <div class="gallery-overlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="gallery-item" data-type="video" data-src="video-url" data-title="Police Training">
                    <img src="images/sklmmap.jpg" alt="Police Training">
                    <div class="gallery-overlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="gallery-item" data-type="video" data-src="video-url" data-title="Police Training">
                    <img src="images/2.png" alt="Police Training">
                    <div class="gallery-overlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <a href="gallery.html"><button class="view-all-btn">View All</button></a>
    </div>

    <!-- Enhanced Modal -->
    <div class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <div class="modal-body">
                <button class="modal-nav prev"><i class="fas fa-chevron-left"></i></button>
                <div class="modal-media-container"></div>
                <button class="modal-nav next"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div class="modal-caption"></div>
        </div>
    </div>
</section>

<!-- JavaScript for gallery functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.querySelector('.modal');
    const modalClose = document.querySelector('.modal-close');
    const modalMediaContainer = document.querySelector('.modal-media-container');
    const modalCaption = document.querySelector('.modal-caption');
    const modalPrev = document.querySelector('.modal-nav.prev');
    const modalNext = document.querySelector('.modal-nav.next');
    let currentIndex = 0;

    function showModal(index) {
        const item = galleryItems[index];
        const type = item.dataset.type;
        const src = item.dataset.src;
        const title = item.dataset.title;
        
        modalMediaContainer.innerHTML = '';
        if (type === 'image') {
            const img = document.createElement('img');
            img.src = src;
            modalMediaContainer.appendChild(img);
        } else {
            const video = document.createElement('video');
            video.src = src;
            video.controls = true;
            modalMediaContainer.appendChild(video);
        }
        
        modalCaption.textContent = title;
        modal.style.display = 'flex';
        currentIndex = index;
    }

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => showModal(index));
    });

    modalClose.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modalPrev.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
        showModal(currentIndex);
    });

    modalNext.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % galleryItems.length;
        showModal(currentIndex);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Update time every second
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleString('en-IN', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        }).replace(/,/g, '');
        
        document.querySelector('local_time').textContent = 
            timeString + ' in YYYY-MM-DD HH:mm:ss format';
    }

    setInterval(updateTime, 1000);
    updateTime(); // Initial update
});
</script>
  

    <!-- Initiatives Section -->
    <?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=srikakulam_police", 
        "root", 
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch initiatives
    $stmt = $pdo->query("
        SELECT * FROM initiatives 
        WHERE status = 'active' 
        ORDER BY display_order 
        LIMIT 4
    ");
    $initiatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $initiatives = [];
}
?>

<section class="initiatives">
    <h2 class="initiatives__title">Our Initiatives</h2>
    <div class="carousel-container">
        <div class="carousel">
            <?php if (!empty($initiatives)): ?>
                <?php foreach($initiatives as $initiative): ?>
                    <div class="carousel__card">
                        <div class="carousel__card-inner">
                            <img src="<?php echo htmlspecialchars($initiative['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($initiative['alt_text']); ?>" 
                                 loading="lazy">
                            <div class="carousel__content">
                                <h3><?php echo htmlspecialchars($initiative['title']); ?></h3>
                                <p><?php echo htmlspecialchars($initiative['description']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default cards if no data -->
                <div class="carousel__card">
                    <div class="carousel__card-inner">
                        <img src="images/1.jpg" alt="Utsarga" loading="lazy">
                        <div class="carousel__content">
                            <h3>Srikakulam</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem illo quasi error deleniti.</p>
                        </div>
                    </div>
                </div>
                <!-- Repeat default cards -->
                <div class="carousel__card">
                    <div class="carousel__card-inner">
                        <img src="images/1.jpg" alt="Utsarga" loading="lazy">
                        <div class="carousel__content">
                            <h3>Srikakulam</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem illo quasi error deleniti.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel__card">
                    <div class="carousel__card-inner">
                        <img src="images/1.jpg" alt="Utsarga" loading="lazy">
                        <div class="carousel__content">
                            <h3>Srikakulam</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem illo quasi error deleniti.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel__card">
                    <div class="carousel__card-inner">
                        <img src="images/1.jpg" alt="Utsarga" loading="lazy">
                        <div class="carousel__content">
                            <h3>Srikakulam</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem illo quasi error deleniti.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="carousel__dots"></div>
        <a href="gallery.html"><button class="carousel__view-all">View All Initiatives</button></a>
    </div>
</section>

<!-- Add this CSS to maintain the original animation -->
<style>
.carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.carousel__card {
    min-width: 30%;
    flex: 0 0 auto;
}

.carousel__dots {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.carousel__dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ccc;
    margin: 0 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.carousel__dot.active {
    background: #333;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.carousel');
    const cards = document.querySelectorAll('.carousel__card');
    const dotsContainer = document.querySelector('.carousel__dots');
    let currentIndex = 0;
    let startX;
    let currentTranslate = 0;
    let isDragging = false;

    // Create dots
    cards.forEach((_, index) => {
        const dot = document.createElement('span');
        dot.classList.add('carousel__dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    function updateDots() {
        const dots = document.querySelectorAll('.carousel__dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function goToSlide(index) {
        currentIndex = index;
        currentTranslate = -index * 100;
        carousel.style.transform = `translateX(${currentTranslate}%)`;
        updateDots();
    }

    // Touch events for mobile
    carousel.addEventListener('touchstart', touchStart);
    carousel.addEventListener('touchmove', touchMove);
    carousel.addEventListener('touchend', touchEnd);

    function touchStart(event) {
        startX = event.touches[0].clientX;
        isDragging = true;
    }

    function touchMove(event) {
        if (!isDragging) return;
        const currentX = event.touches[0].clientX;
        const diff = (startX - currentX) / carousel.offsetWidth * 100;
        const translate = currentTranslate - diff;
        carousel.style.transform = `translateX(${translate}%)`;
    }

    function touchEnd() {
        isDragging = false;
        const threshold = 20;
        const diff = startX - event.changedTouches[0].clientX;
        
        if (Math.abs(diff) > threshold) {
            if (diff > 0 && currentIndex < cards.length - 1) {
                currentIndex++;
            } else if (diff < 0 && currentIndex > 0) {
                currentIndex--;
            }
        }
        
        goToSlide(currentIndex);
    }

    // Auto-advance carousel
    setInterval(() => {
        if (!isDragging) {
            currentIndex = (currentIndex + 1) % cards.length;
            goToSlide(currentIndex);
        }
    }, 7000);
});
</script>


    <!-- Social Media Section -->
     <!-- Social Media Icons Section -->
     <section class="social-media">
        <h2>Stay Connected</h2>
        <div class="social-container">
            <div class="social-accounts">
                <!-- Facebook -->
                <div class="social-account facebook">
                    <div class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-info">
                        <h3>Facebook</h3>
                        <p>@SrikakulamPolice</p>
                    </div>
                    <a href="https://www.facebook.com/profile.php?id=100068435118920" class="social-follow">Follow</a>
                </div>
    
                <!-- Twitter -->
                <div class="social-account twitter">
                    <div class="social-icon">
                        <i class="fab fa-twitter"></i>
                    </div>
                    <div class="social-info">
                        <h3>Twitter</h3>
                        <p>@SPSrikakulam</p>
                    </div>
                    <a href="https://twitter.com/SRIKAKULMPOLICE" class="social-follow">Follow</a>
                </div>
    
                <!-- YouTube -->
                <div class="social-account youtube">
                    <div class="social-icon">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <div class="social-info">
                        <h3>YouTube</h3>
                        <p>Srikakulam Police</p>
                    </div>
                    <a href="#" class="social-follow">Subscribe</a>
                </div>
    
                <!-- Instagram -->
                <div class="social-account instagram">
                    <div class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="social-info">
                        <h3>Instagram</h3>
                        <p>@SrikakulamPolice</p>
                    </div>
                    <a href="https://www.instagram.com/sklmpolice?igsh=ZGZyZmVhcHZ4bGtl" class="social-follow">Follow</a>
                </div>
            </div>
        </div>
    </section>

<!-- Separator -->
<div class="section-divider"></div>

<!-- Social Media Feeds Section -->
<section class="social-feeds-section">
    <h2>Connect With Us</h2>
    <div class="social-feeds-container">
        <!-- Facebook Feed -->
        <div class="feed-container facebook-feed">
            <div class="fb-page" 
                data-href="https://www.facebook.com/profile.php?id=100068435118920" 
                data-tabs="timeline" 
                data-width="330" 
                data-height="400" 
                data-small-header="false" 
                data-adapt-container-width="true" 
                data-hide-cover="false" 
                data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/profile.php?id=100068435118920" 
                    class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/profile.php?id=100068435118920">Srikakulam Police Department</a>
                </blockquote>
            </div>
        </div>

        <!-- Twitter Feed -->
        <div class="feed-container twitter-feed">
            <a class="twitter-timeline" 
                href="https://twitter.com/SRIKAKULMPOLICE" 
                data-width="330"
                data-height="400" 
                data-theme="light">
                Tweets by Srikakulam Police
            </a>
        </div>

        <!-- YouTube Feed -->
        <div class="feed-container youtube-feed">
            <iframe 
                width="330" 
                height="500" 
                src="https://www.youtube.com/embed?listType=user_uploads&list=appolice6367"
                title="Srikakulam Police YouTube Channel" 
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="main-footer">
    <div class="footer-content">
        <!-- Logo and Description Section -->
        <div class="footer-left">
            <img src="images/sklmplc.png" alt="Srikakulam Police Logo" class="footer-logo">
            <p class="footer-description">
                The pace of change is breathtaking. The challenges are exciting. Srikakulam Police has the courage and vision to face them. We are preparing for the future with new skills, new technologies, new attitudes.
            </p>
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=100068435118920" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/SRIKAKULMPOLICE" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <!-- Quick Links Section with Menus -->
        <div class="footer-right">
            <h3>Quick Links</h3>
            <div class="footer-menu-container">
                <!-- Emergency Services Menu -->
                <div class="footer-menu">
                    <button class="footer-menu-trigger">Emergency Services</button>
                    <div class="footer-menu-items">
                        <a href="tel:181">Women Helpline - 181</a>
                        <a href="tel:1930">Cyber Crime - 1930</a>
                    </div>
                </div>

                <!-- Public Services Menu -->
                <div class="footer-menu">
                    <button class="footer-menu-trigger">Public Services</button>
                    <div class="footer-menu-items">
                        <a href="fir.html">FIR Registration</a>
                        <a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp">Lost Report</a>
                        <a href="https://www.passportindia.gov.in/AppOnlineProject/welcomeLink">Passport Verification</a>
                        <a href="https://www.passportindia.gov.in/AppOnlineProject/pdf/HydPCC.pdf">Police Clearance</a>
                        <a href="#">Event Permission</a>
                    </div>
                </div>

                <!-- Information Menu -->
                <div class="footer-menu">
                    <button class="footer-menu-trigger">Information</button>
                    <div class="footer-menu-items">
                        <a href="know.html">Police Stations</a>
                        <a href="#">District Crime Data</a>
                        <a href="#">Most Wanted</a>
                        <a href="#">Missing Persons</a>
                        <a href="traffic.html">Traffic Updates</a>
                    </div>
                </div>

                <!-- Administration Menu -->
                <div class="footer-menu">
                    <button class="footer-menu-trigger">Administration</button>
                    <div class="footer-menu-items">
                        <a href="https://citizen.appolice.gov.in/jsp/userMenu.do?method=viewPoliceRanks">Police Ranks</a>
                        <a href="https://slprb.ap.gov.in/UI/recruitments">Police Recruitment</a>
                        <a href="https://rti.gov.in/rti-act.pdf">RTI Information</a>
                    </div>
                </div>

                <!-- Special Units Menu -->
                <div class="footer-menu">
                    <button class="footer-menu-trigger">Special Units</button>
                    <div class="footer-menu-items">
                        <a href="#">Women's Cell</a>
                        <a href="cybercell.html">Cyber Cell</a>
                        <a href="https://www.mha.gov.in/en/divisionofmha/Women_Safety_Division/anti-trafficking-cell">Anti-Human Trafficking</a>
                        <a href="https://citizen.appolice.gov.in/jsp/distcommcontact.do?method=retrieveUserUnits">Special Branch</a>
                    </div>
                </div>

                <!-- Citizen Services Menu -->
                <div class="footer-menu">
                    <button class="footer-menu-trigger">Citizen Services</button>
                    <div class="footer-menu-items">
                        <a href="https://srikakulam.ap.gov.in/service/spandana/">Public Grievances</a>
                        <a href="https://portal-psc.ap.gov.in/Default">Verification Services</a>
                        <a href="https://srikakulam.ap.gov.in/te/contact-directory/">Contact Directory</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Geolocation Section -->
        <div class="geo-location">
            <h3>Find Us</h3>
            <div class="address">
                District Police Office<br>
                Collector Office Road<br>
                Balaga, Srikakulam<br>
                Andhra Pradesh - 532001<br>
                <br>
               <a href="tel: 08942-240980"> Phone: 08942-240980 </a>
            </div>
            <iframe 
                class="map"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3792.530661787714!2d83.88721731489454!3d18.294595987506428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a3c7650e1d68c75%3A0x7e719d33d3db26f!2sDistrict%20Police%20Office!5e0!3m2!1sen!2sin!4v1640595847692!5m2!1sen!2sin"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <p class="copyright">© 2024-12-27, All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
<script src="home.js"></script>
<script async defer crossorigin="anonymous" 
src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0">
</script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

</body>

</html>