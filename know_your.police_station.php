<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "srikakulam_police";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch police station data
$sql = "SELECT * FROM police_stations";
$result = $conn->query($sql);

$policeStationsData = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $policeStationsData[$row['name']] = [
            'coords' => ['lat' => floatval($row['latitude']), 'lng' => floatval($row['longitude'])],
            'contact' => $row['contact'],
            'address' => $row['address'],
            'imageUrl' => $row['image_url']
        ];
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Srikakulam Police Department</title>
    <style>
        /* Your existing CSS styles here */
        body {
    
    margin: 0;
    font-family: Arial, sans-serif;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-family: Arial, sans-serif; 
    
    background: #fff;
    font-size: 100%;
    
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
    padding-left: 30px;
    padding-right: 30px;
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

.recent_activity{
    justify-content: center;
    height: 24px;
 font-size: 14px;
 background-color: #0a949699;
 padding-bottom: 6px;

}

    /* Mobile Adjustments (to keep layout similar to desktop) */
    @media only screen and (max-width: 768px) {
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            flex-wrap: nowrap;
            flex-direction: row;
        }
    
        #sklmlogo, .aplogo {
            width: 80px;
        }
    
        .titles {
            padding: 0 5px;
            margin: 0 auto;
            text-align: center;
        }
    
        .sklm {
            font-size: 30px;
        }
    
        .police {
            font-size: 28px;
        }
    }
    
    /* Very Small Devices (up to 480px) */
    @media only screen and (max-width: 480px) {
        .header {
            padding: 10px;
            flex-direction: row;
        }
    
        #sklmlogo, .aplogo {
            width: 60px;
        }
    
        .sklm {
            font-size: 24px;
        }
    
        .police {
            font-size: 22px;
        }
    
        .titles {
            padding: 0 10px;
            text-align: center;
        }
    }
    
    /* Navbar Styles */
    .navbar {
        background-color: #00509e;
        padding: 12px 0;
        text-align: center;
        width: 100%;
        box-sizing: border-box;
    }
    
    .navbar ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
    }
    
    .navbar ul li {
        padding: 8px 10px;
        position: relative;
    }
    
    .navbar ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
    }
    
    /* Responsive Navbar for smaller devices */
    @media only screen and (max-width: 768px) {
        .navbar ul {
            flex-direction: column;
            display: none;
            width: 100%;
            background-color: #2f415c;
        }
    
        .navbar ul.show {
            display: flex;
        }
    
        .navbar ul li {
            padding: 10px 0;
            text-align: center;
        }
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
    
    /* Map and Dropdown Section */
.container {
padding: 20px;
display: flex;
flex-wrap: wrap;
}

.form-group {
margin-bottom: 20px;
width: 100%;
}

#map {
width: 50%;
height: 400px;
display: inline-block;
margin-right: 20px;
}

#mandal-details {
width: 35%;
padding: 20px;
background-color: #fff;
border: 1px solid #ddd;
border-radius: 10px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
display: inline-block;
vertical-align: top;
margin-top: 20px;
}

#police-station-list li {
margin-bottom: 20px;
padding: 10px;
border: 1px solid #ccc;
border-radius: 5px;
background-color: #f9f9f9;
}

/* Style for the dropdown */
select {
margin: 20px 0;
padding: 10px;
width: 100%;
}

/* Responsive layout for smaller screens */
@media only screen and (max-width: 768px) {
#map {
    width: 100%;
    height: 300px;
    margin: 0 0 20px 0;
}

#mandal-details {
    width: 100%;
    margin: 0;
}
}

@media only screen and (max-width: 480px) {
#map {
    height: 250px;
}

#mandal-details {
    padding: 15px;
}

#police-station-list li {
    font-size: 14px;
}
}
/* Footer Section */
    footer {
        background-color: #0a9396;
        color: white;
        text-align: center;
        padding: 5px 0;
        position: fixed;
        width: 100%;
        bottom: 0;
    }
    
    /* Mobile adjustments for map */
    @media only screen and (max-width: 768px) {
        #map {
            height: 300px;
        }
    }
    
    /* Very small device adjustments for map */
    @media only screen and (max-width: 480px) {
        #map {
            height: 250px;
        }
    }
    </style>
     <!-- Google Maps JavaScript API -->
    
    <script>
        const policeStationsData = <?php echo json_encode($policeStationsData); ?>;
        
        let map;
        let marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 18.29379415, lng: 83.8954661 },
                zoom: 10
            });
        }

        function updateMapForMandal() {
            const selectedMandal = document.getElementById("mandal").value;
            const policeStationDetails = policeStationsData[selectedMandal];

            const heading = document.getElementById("mandal-heading");
            const stationList = document.getElementById("police-station-list");

            heading.textContent = `${selectedMandal} Police Stations`;
            stationList.innerHTML = ''; // Clear previous list

            if (policeStationDetails) {
                const li = document.createElement("li");
                li.innerHTML = `
                    <strong>Station Name:</strong> ${selectedMandal}<br>
                    <strong>Address:</strong> ${policeStationDetails.address}<br>
                    <strong>Contact:</strong> ${policeStationDetails.contact}<br>
                    <img src="${policeStationDetails.imageUrl}" alt="Head of Station" style="width: 100px; height: auto; border-radius: 50%;"><br>
                    <button onclick="getDirections(${policeStationDetails.coords.lat}, ${policeStationDetails.coords.lng})">Get Directions</button>
                `;
                stationList.appendChild(li);

                map.setCenter(policeStationDetails.coords);
                map.setZoom(12);

                if (marker) {
                    marker.setMap(null); // Remove previous marker
                }
                marker = new google.maps.Marker({
                    position: policeStationDetails.coords,
                    map: map,
                    title: selectedMandal
                });
            }
        }

        function getDirections(lat, lng) {
            window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`, '_blank');
        }
    </script>
    
    <script async defer src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyL00PdPtPBYYqQYcdiaLKAKKj32GihM__z=initMap"></script>
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
                    <li><a href="">Awards</a></li>
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
    

<!-- Mandal Dropdown & Map -->
<div class="container">
    <div class="form-group">
        <label for="mandal">Select Mandal:</label>
        <select class="form-control" id="mandal" onchange="updateMapForMandal()">
  <option value="">Select Mandal</option>
  <option value="Srikakulam I Town">Srikakulam I Town</option>
  <option value="Srikakulam II Town">Srikakulam II Town</option>
  <option value="Gara">Gara</option>
  <option value="Polaki">Polaki</option>
  <option value="Narasannapeta">narasannapeta</option>
  <option value="Amadalavalasa">Amadalavalasa</option>
  <option value="Sarubujjili">Sarubujjili</option>
  <option value="Burja">Burja</option>
  <option value="Ponduru">Ponduru</option>
  <option value="Etcherla">Etcherla</option>
  <option value="Laveru">Laveru</option>
  <option value="Ranasthalam">Ranasthalam</option>
  <option value="G Sigadam">G Sigadam</option>
  <option value="Jalumuru">Jalumuru</option>
  <option value="Tekkali">Tekkali</option>
  <option value="Kota Bommali">Kota Bommali</option>
  <option value="Santha Bommali">Santha Bommali</option>
  <option value="Saravakota">Saravakota</option>
  <option value="Pathapatnam">Pathapatnam</option>
  <option value="Meliyaputti">Meliyaputti</option>
  <option value="Hiramandalam">Hiramandalam</option>
  <option value="Kotthuru">KOtthuru</option>
  <option value="L N Peta">L N Peta</option>
  <option value="Nandigam">Nandigam</option>
  <option value="Palasa">Palasa</option>
  <option value="Sompeta">Sompeta</option>
  <option value="Kaviti">Kaviti</option>
  <option value="Kanchili">Kanchili</option>
  <option value="Ecchapuram">Ecchapuram</option>
  <option value="Vajrapu KOtthuru">Vajrapu Kotthuru</option>
  <option value="Mandasa">Mandasa</option>
</select>
    </div>
    <div id="map"></div>
    <div id="mandal-details">
        <h2 id="mandal-heading">Police Stations</h2>
        <ul id="police-station-list"></ul>
    </div>
</div>

<br>
<br>
<br>
    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Srikakulam Police Department. All Rights Reserved.</p>
    </footer>

    <script>
        function toggleMenu() {
            const navMenu = document.querySelector('.navbar ul');
            navMenu.classList.toggle('show');
        }
    </script>
    
</body>
</html>
