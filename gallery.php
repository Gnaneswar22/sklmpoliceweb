<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="headerfoot.css">
    <style>
                
        .highlights {
            text-align: center;
            padding: 1.5rem;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 1rem;
        }

        .photo {
            position: relative;
            width: 220px;
            height: 260px;
            overflow: hidden;
            border-radius: 12px;
            border: 4px solid #0a9396;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .photo img {
            width: 100%;
            height: 80%;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .caption {
            width: 100%;
            text-align: center;
            padding: 0.5rem;
            background-color: #0a9396;
            color: white;
            font-weight: bold;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 0.9rem;
        }

        .photo:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: space-around;
            align-items: center;
            opacity: 0;
            padding: 0.5rem 0;
            transition: opacity 0.3s;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .photo:hover .overlay {
            opacity: 1;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        button {
            background-color: #0a9396;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        button:hover {
            background-color: #087f84;
        }

        /* CSS for Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 20px;
}

.modal-content {
    max-width: 90%;
    max-height: 80%;
    border-radius: 10px;
}

#modalCaption {
    margin-top: 10px;
    text-align: center;
    color: #ccc;
    font-size: 1.1rem;
    padding: 10px;
    background-color: rgba(0, 0, 0, 0.8);
    width: 100%;
    text-align: center;
}

.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #ccc;
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
        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="#about"><i class="fas fa-info-circle"></i> About Us</a>
            <ul>
                <li><a href="history.html">History</a></li>
                <li><a href="organisation.html">Organisation Chart</a></li>
                <li><a href="awards.html">Awards</a></li>
                <li><a href="officers.php">Our Team</a></li>
                <li><a href="gallery.php">Gallery</a></li>
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
                        <li><a href="services.html">Knowmore</a></li>
            </ul>
        </li>
        <li><a href="contacts.html"><i class="fas fa-phone-alt"></i> Contact Us</a></li>
    </ul>
</nav>


        <?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "srikakulam_police";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM gallery_highlights";
$result = $conn->query($sql);
?>
        <section class="highlights">
            <h2>Gallery Highlights</h2>
            <p>Explore recent events and achievements captured by our team.</p>
        </section>
        
        <section class="gallery">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='photo'>
                            <img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['caption']) . "' onclick='openModal(this)'>
                            <div class='caption'>" . htmlspecialchars($row['caption']) . "</div>
                            <div class='overlay'>
                                <div class='buttons'>
                                    <button class='fullscreen' onclick='openModal(this)'>🔍 Fullscreen</button>
                                </div>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No highlights available.</p>";
            }
            ?>
        </section>
        
        <!-- Modal for fullscreen view -->
        <div id="fullscreenModal" class="modal" style="display:none;">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImage">
            <div id="modalCaption"></div>
        </div>
        
        <script>
        function openModal(element) {
            var modal = document.getElementById("fullscreenModal");
            var modalImg = document.getElementById("modalImage");
            var captionText = document.getElementById("modalCaption");
            modal.style.display = "block";
            modalImg.src = element.src;
            captionText.innerHTML = element.alt;
        }
        
        function closeModal() {
            var modal = document.getElementById("fullscreenModal");
            modal.style.display = "none";
        }
        </script>
        
        </body>
        </html>
        
        <?php
        $conn->close();
        ?>


<script>
    // Get the modal elements
    const modal = document.getElementById("fullscreenModal");
    const modalImg = document.getElementById("modalImage");
    const modalCaption = document.getElementById("modalCaption");
    const closeBtn = document.querySelector(".close");

    // Add event listener for fullscreen button
    document.querySelectorAll(".fullscreen").forEach(button => {
        button.addEventListener("click", function() {
            const photoDiv = this.closest(".photo");
            const imgSrc = photoDiv.querySelector("img").src;
            const captionText = photoDiv.querySelector(".caption").textContent;

            // Set modal image and caption
            modal.style.display = "flex";
            modalImg.src = imgSrc;
            modalCaption.textContent = captionText;
        });
    });

    // Close the modal when 'X' is clicked
    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    // Close the modal when clicking outside the modal image
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>
<script src="hamburger.js"></script>
</body>
</html>
