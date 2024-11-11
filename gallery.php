<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Gallery</title>
    <style>
        /* General styling reset */
        body {
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            font-family: Arial, sans-serif;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-family: Arial, sans-serif; 
            background: #fff;
            font-size: 90%;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0a9396;
            padding: 0;
            color: white;
            position: relative;
            
        }
        .logo {
           
        height:auto;
            width: 100px;
           padding-top: 18px;
            padding-left: 80px;
            padding-right: 80px;
        }
        
        .header-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 35px;
            font-weight: bold;
        }
        nav {
            display: flex;
            background-color:#0a9396;
            justify-content: center;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 10px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            position: relative;
        }
        nav ul li {
            position: relative;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 17px;
            padding: 10px;
        }
        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.11);
            border-radius: 5px;
        }
        /* Dropdown styles */
        nav ul li ul {
            display:none;
            position:absolute;
            top: 100%;
           
            background-color: whitesmoke;
          
            list-style: none;
            height: auto;;
            width: 160px;
            z-index: 10;
            border-radius: 10px;
        }
        nav ul li ul li a {
            padding: auto;
            height: auto;
            width: auto;
            display: block;
            color: black;
            font-size: 14px;
            align-items: center;
        }
        nav ul li ul li a:hover {
            background-color: rgba(18, 140, 126, 0.507);
            border-radius: 5px;
        }
        nav ul li:hover ul {
            display: block;
        }
        
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
    <section>
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
        </header>

        

        <!-- Navigation Bar -->
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="#about">About Us</a>
                    <ul>
                        <li><a href="history.html">History</a></li>
                        <li><a href="organisation.html">Organisation Chart</a></li>
                        <li><a href="awards.html">Awards</a></li>
                        <li><a href="officers.html">OUR TEAM</a></li>
                    </ul>
                </li>
                <li><a href="#about">wings</a>
                    <ul>
                        <li><a href="law.html">Law & order</a></li>
                        <li><a href="traffic.html">Traffic</a></li>
                        <li><a href="tel:100">Dail 100</a></li>
                        <li><a href="">AHTU</a></li>
                    </ul>
                </li>
                <li><a href="womenscorner.html">Women’s Corner</a></li>
                <li><a href="">Services</a>
                  <ul>
                    <li><a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp">Lost Report</a></li>
                    <li><a href="fir.html">View FIR</a></li>
                    <li><a href="domestic.html">Domestic Violence</a></li>
                    <li><a href="accident.html">Accedent Analysis</a></li>
                    <li><a href="https://services.india.gov.in/service/detail/apply-online-for-use-of-loud-speakers-1">Loud Speaker Permission</a></li>
                </ul>
              </li>
                <li><a href="contacts.html">Contact Us</a></li>
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

</body>
</html>
