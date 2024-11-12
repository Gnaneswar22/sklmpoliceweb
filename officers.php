<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Department Hierarchy Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="headerfoot.css">
    <style>
       /* Add to your existing CSS */
.hierarchy-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.rank-section {
    margin-bottom: 40px;
}

.rank-title {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
}

.officer-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    text-align: center;
}

.officer-card:hover {
    transform: translateY(-5px);
}

.officer-image {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
}

.officer-name {
    font-size: 18px;
    color: #333;
    margin: 10px 0 5px;
}

.officer-station {
    color: #666;
    font-size: 14px;
}

.officers-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
    padding: 20px;
}

.officer-card {
    flex: 0 1 200px; /* Adjust the width of each card */
}

@media screen and (max-width: 768px) {
    .officers-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 480px) {
    .officers-grid {
        grid-template-columns: 1fr;
    }
    
    .officer-image {
        width: 150px;
        height: 150px;
    }
}

    </style>
</head>

<body>
    <header>
        <!-- Left Logo -->
        <div>
            <img src="images/Appolice.png" alt="Left Logo" class="logo">
        </div>

        <!-- Center Heading -->
        <h1 class="header-title">Srikakulam Police Department</h1>

        <!-- Right Logo -->
        <div>
            <img src="images/Sklmlogo.png" alt="Right Logo" class="logo">
        </div>

        <!-- Hamburger Menu -->
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </header>
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="#about"><i class="fas fa-info-circle"></i> About Us</a>
                <ul>
                    <li><a href="history.html">History</a></li>
                    <li><a href="organisation.html">Organisation Chart</a></li>
                    <li><a href="awards.html">Awards</a></li>
                </ul>
            </li>
            <li><a href="#wings"><i class="fas fa-shield-alt"></i> Wings</a>
                <ul>
                    <li><a href="law.html">Law & Order</a></li>
                    <li><a href="traffic.html">Traffic</a></li>
                    <li><a href="tel:100">Dial 100</a></li>
                    <li><a href="#">AHTU</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="fas fa-female"></i> Women’s Corner</a></li>
            <li><a href="#"><i class="fas fa-concierge-bell"></i> Services</a>
                <ul>
                    <li><a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp">Lost
                            Report</a></li>
                    <li><a href="fir.html">View FIR</a></li>
                    <li><a href="domestic.html">Domestic Violence</a></li>
                    <li><a href="accident.html">Accident Analysis</a></li>
                    <li><a href="https://services.india.gov.in/service/detail/apply-online-for-use-of-loud-speakers-1">Loud
                            Speaker Permission</a></li>
                </ul>
            </li>
            <li><a href="contacts.html"><i class="fas fa-phone-alt"></i> Contact Us</a></li>
        </ul>
    </nav>

    <?php
// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "srikakulam_police"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch officers
$sql = "SELECT name, rank, station, image_path FROM officers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<main class="hierarchy-content">
            <section class="officers-section">
                <h1 class="page-title">Police Department Hierarchy</h1>';

    // Initialize rank sections
    $currentRank = '';

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        if ($currentRank !== $row["rank"]) {
            if ($currentRank !== '') {
                echo '</div>'; // Close previous officers-grid
            }
            $currentRank = $row["rank"];
            echo '<div class="rank-section">
                    <h2 class="rank-title">' . htmlspecialchars($row["rank"]) . '</h2>
                    <div class="officers-grid">';
        }

        echo '<div class="officer-card">
                <img src="' . htmlspecialchars($row["image_path"]) . '" alt="' . htmlspecialchars($row["name"]) . '" class="officer-image">
                <h3 class="officer-name">' . htmlspecialchars($row["name"]) . '</h3>';
        if (!empty($row["station"])) {
            echo '<p class="officer-station">' . htmlspecialchars($row["station"]) . '</p>';
        }
        echo '</div>';
    }
    echo '      </div> <!-- Close last officers-grid -->
            </section>
        </main>';
} else {
    echo "No officers found.";
}
$conn->close();
?>

    <footer>
        <p>&copy; 2024 Srikakulam Police Department. All Rights Reserved.</p>
    </footer>
    <script>
        // Optional JavaScript for enhanced functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Handle image loading
            const images = document.querySelectorAll('.gallery img');

            images.forEach(img => {
                img.addEventListener('load', function () {
                    this.style.opacity = 1;
                });

                img.addEventListener('error', function () {
                    this.src = 'placeholder.jpg'; // Fallback image
                });
            });
        });
    </script>
    <script src="hamburger.js"></script>
</body>

</html>