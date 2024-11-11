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
  <title>Srikakulam Police Department</title>
  <style>
    body {
            margin: 0;
            font-family: Arial, sans-serif;
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
            width: 100px;
            padding: 5px;
        }
        .recent_activity {
            background-color: #61ddd3;
            color: white;
        }
        .header-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 35px;
            font-weight: bold;
        }
        nav {
            background-color: #0a9396;
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
            font-size: 16px;
            padding: 10px;
        }
        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.112);
            border-radius: 5px;
        }
        /* Dropdown styles */
        nav ul li ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #0a9396;
            padding: 0;
            margin: 0;
            list-style: none;
            width: 150px;
            z-index: 1;
            border-radius: 10px;
        }
        nav ul li ul li a {
            padding: 10px;
            width: auto;
            display: block;
        }
        nav ul li ul li a:hover {
            background-color: rgba(255, 255, 255, 0.112);
        }
        nav ul li:hover ul {
            display: block;
        }


        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 5px;
        }

        /* Hamburger menu on smaller screens */
        @media only screen and (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .navbar ul {
                display: none;
                flex-direction: column;
                background-color: #00509e;
                width: 100%;
                text-align: center;
            }

            .navbar ul.show {
                display: flex;
            }
        }

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
                        <li><a href="">Organisation Chart</a></li>
                        <li><a href="awards.html">Awards</a></li>
                        <li><a href="officers.html">OUR TEAM</a></li>
                    </ul>
                </li>
                <li><a href="#about">wings</a>
                    <ul>
                        <li><a href="law.html">Law & order</a></li>
                        <li><a href="traffic.html">Traffic</a></li>
                        <li><a href="tel:100">Dial 100</a></li>
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
    </section>

    <div class="container">
        <h2>Emergency Contact Numbers</h2>
        
        <!-- Display Emergency Contacts -->
        <ul class="emergency-numbers">
            <?php
            $contacts = getEmergencyContacts($conn);
            while($row = $contacts->fetch_assoc()) {
                echo "<li>";
                echo "<span>" . htmlspecialchars($row['service_name']) . ": " . htmlspecialchars($row['phone_number']) . "</span>";
                echo "<a href='tel:" . htmlspecialchars($row['phone_number']) . "' class='dial-button'>DIAL</a>";
                echo "</li>";
            }
            ?>
        </ul>

        <!-- Admin Controls -->
        <div class="admin-controls">
            <h3>Add New Emergency Contact</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="service_name">Service Name:</label>
                    <input type="text" name="service_name" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" name="phone_number" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description"></textarea>
                </div>
                <button type="submit" name="add_contact">Add Contact</button>
            </form>
        </div>
    </div>

    <?php
    // Handle form submissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add_contact'])) {
            $service_name = $_POST['service_name'];
            $phone_number = $_POST['phone_number'];
            $description = $_POST['description'];
            
            if (addEmergencyContact($conn, $service_name, $phone_number, $description)) {
                echo "<script>alert('Contact added successfully!'); window.location.reload();</script>";
            } else {
                echo "<script>alert('Error adding contact!');</script>";
            }
        }
    }

    $conn->close();
    ?>
</body>
</html>
