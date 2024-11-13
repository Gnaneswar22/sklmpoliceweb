<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "emergency_contacts";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get all emergency contacts
function getEmergencyContacts($conn) {
    $sql = "SELECT * FROM emergency_numbers WHERE is_active = 1";
    $result = $conn->query($sql);
    return $result;
}

// Function to add new emergency contact
function addEmergencyContact($conn, $service_name, $phone_number, $description) {
    $stmt = $conn->prepare("INSERT INTO emergency_numbers (service_name, phone_number, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $service_name, $phone_number, $description);
    return $stmt->execute();
}

// Function to update emergency contact
function updateEmergencyContact($conn, $id, $service_name, $phone_number, $description) {
    $stmt = $conn->prepare("UPDATE emergency_numbers SET service_name = ?, phone_number = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $service_name, $phone_number, $description, $id);
    return $stmt->execute();
}

// Function to delete emergency contact
function deleteEmergencyContact($conn, $id) {
    $stmt = $conn->prepare("UPDATE emergency_numbers SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="headerfoot.css">
  <title>Srikakulam Police Department</title>
  <style>
    .container {
      padding: 2em;
      max-width: 900px;
      margin: auto;
    }
    .emergency-numbers {
      list-style-type: none;
      padding: 0;
    }
    .emergency-numbers li {
        color:black;
        font-size: 18px;
        font-weight:bold;
        font-style:italic;
      background-color: #e0e7ff;
      margin: 1em 0;
      padding: 1em;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: transform 0.5s ease, opacity 0.5s ease;
    }
    .emergency-numbers a span{
       font-size: 20px;
        background-color:#0a9396;
       border: 5px solid #0a9396;
       border-radius: 5px;
       box-shadow: 0 4px 8px rgba(0, 0, 0, 0);
    }
    .emergency-numbers a {
      color: #ffffff;
      font-weight: bold;
      text-decoration: none;
    }
    .emergency-numbers li:hover {
      color: red;
      transform: scale(1.05);
      opacity: 1;
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

<<<<<<< HEAD:emergency.html
<div class="container">
  <h2>Emergency Contact Numbers</h2>
  <ul class="emergency-numbers">
    <li>
      <span>Police Emergency:</span>
      <a href="tel:100"><span>DIAL</span></a>
    </li>
    <li>
      <span>Fire Services:</span>
      <a href="tel:101"><span>DIAL</span></a>
    </li>
    <li>
      <span>Ambulance:</span>
      <a href="tel:108"><span>DIAL</span></a>
    </li>
    <li>
      <span>Women’s Helpline:</span>
      <a href="tel:181"><span>DIAL</span></a>
    </li>
    <li>
      <span>Child Helpline:</span>
      <a href="tel:1098"><span>DIAL</span></a>
    </li>
  </ul>
</div>
<footer>
    <p>&copy; 2024 Srikakulam Police Department. All Rights Reserved.</p>
</footer>
<script src="hamburger.js"></script>
</body>
</html>
