

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Srikakulam Police Department</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="icon" href="images/sklmlogo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="index.css">
    
    
  
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


        <?php
require_once 'db_connect.php';

class RecentActivity {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getRecentActivities() {
        $sql = "SELECT activity_text, activity_link FROM recent_activities ORDER BY created_at DESC LIMIT 10";
        $result = $this->conn->query($sql);
        $activities = [];

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
        }

        return $activities;
    }
}

$activityManager = new RecentActivity($conn);
$activities = $activityManager->getRecentActivities();
?>

<!-- HTML Section for Recent Activities -->
<div class="recent_activity">
    <marquee onmouseover="this.stop();" onmouseout="this.start();">
        <?php foreach ($activities as $activity): ?>
            <span>
                <?php if ($activity['activity_link']): ?>
                    <a href="<?php echo htmlspecialchars($activity['activity_link']); ?>">
                        <?php echo htmlspecialchars($activity['activity_text']); ?>
                    </a>
                <?php else: ?>
                    <?php echo htmlspecialchars($activity['activity_text']); ?>
                <?php endif; ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
            </span>
        <?php endforeach; ?>
    </marquee>
</div>


    
    </section>

   <div>
    <div class="image-container">
        <button class="nav-button left">&#10094;</button>
        <button class="nav-button right">&#10095;</button>
        
        <div class="banner-1 active">
            <img src="images/sp.jpg" alt="Sri K.V.Maheswara Reddy, Superintendent of Police">
            <div class="background-image"></div>
            <div class="text-box animate">
                <h1>Sri K.V.Maheswara Reddy</h1>
                <h3>I.P.S</h3>
                <p>Superintendent Of Police (sp) in Srikakulam, AP</p>
            </div>
        
        </div>
    
        <div class="banner-2">
            <img src="images/img.jpg" alt="Srikakulam Police">
            <div class="background-image"></div>
            <div class="text-box">
                <h1>SRIKAKULAM POLICE</h1>
                <h3>Serving city of joy since 1974</h3>
            </div>
        </div>
    
        <div class="banner-3">
            <img src="images/station.jpg" alt="Srikakulam Police Station">
            <div class="background-image"></div>
            <div class="text-box">
                <h1>SRIKAKULAM POLICE</h1>
                <h3>Serving city of joy since 1974</h3>
            </div>
        </div>
    
        <div class="banner-4">
            <img src="images/sklm.jpg" alt="Surya Narayana Swami Temple">
            <div class="background-image"></div>
            <div class="text-box">
                <h1>SRIKAKULAM</h1>
                <h1>Arasavilli</h1>
                <p>SURYA NARAYANA SWAMI TEMPLE</p>
            </div>
        </div>
    </div>
  </div>

    <section class="top-news-sec fontSize">
        <div class="container">
            <div class="hdr-news-wrpr">
                <h4><label> <a href="emergency.html"><img src="images/helpline.png" alt="helpline"> </a><span>Helplines</span> </label></h4>
                <marquee onmouseover="this.stop();" onmouseout="this.start();">
         <div class="marque-hldr hdrnews_marquee">
                     
                        <p>Child Line Day/Night: <a href="tel:1098">1098</a></p>
      
                     
                        <p>Anti Bank Fraud Helpline: <a href="tel:8585063104">8585063104</a></p>
      
                     
                        <p>Cyber PS : <a href="tel: 033-2214 3000">033-2214 3000</a> / <a href="tel: 98365 13000">98365 13000</a></p>
      
                     
                        <p>Control Room: <a href="tel:100">100</a> / <a href="tel:1090">1090</a></p>
      
                     
                        <p>Traffic: <a href="tel:1073">1073</a> (Toll Free)</p>
      
                     
                        <p>Women in Need Call: <a href="tel:1091">1091</a> (Toll Free)</p>
      
                    </marquee>
                </div>
            </div>
        </div>
    </section>

    

               
        <div class="Services">
            <div class="sr">
                
            <a href="know_your_police_station.html"> <img src="images/police-station.png" ></a>
            <h3>POLICE STATION</h3>
            <p >know your police station and Locate your nearest police station</p>
        </div>
        <div class="sr">
            <a href="emergency.html"> <img src="images/emergency.png" ></a>
            <h3>Emergency Contacts</h3>
            <p> Emergency contacts -  dail-100/ dail-101 / dail-108 /dail-181 /dail-1098</p>
        </div>
        <div class="sr">
            <a href=""> <img src="images/cyber.png" ></a>
            <h3>CYBER CELL</h3>
            <p >Cyber Cell</p>
        </div>
        <div class="sr">
            <a href="https://ceir.sancharsaathi.gov.in/Request/CeirUserBlockRequestDirect.jsp"> <img src="images/ceir.png" ></a>
            <h3>CEIR</h3>
            <p >Block and unblock lost or stolen mobiles</p>
        </div>
        <div class="sr">
            <a href="https://aptonline.in/CitizenPortal/CITIZENPORTAL/ECHALLAN_AP_BILLPAY.aspx?value=Usxttk74q1GYhR2T/Be6/mE9hJz0qPHX9uyIZUOOMzdD5sX36J5o6WDI7Zoniy3T"> <img src="images/echalana.png" ></a>
            <h3>e Challana</h3>
            <p > Digital Traffic/Transport Enforcement Solution
                An Initiative of MoRTH, Government of India
                 </p>
        </div>
        <div class="sr">
          <a href="wanted.html"> <img src="images/criminal.png" ></a>
          <h3>Most Wanted</h3>
          <p > click here to know about most wanted in Srikakulam dist.s
               </p>
      </div>
        

        
       
    </div>
       

       
      <form action="#" method="post">
        
        <div class="newsbars">
        <div class="newsbar">

            <h2 style="text-align:center;  text-decoration: underline; ">LATEST NEWS</h2>
            <div class="news" method="POST" >
               
        <marquee behavior="scroll" direction="up" onmouseover="this.stop();" onmouseout="this.start();">
       <form action="news_articles.php" method="POST">
            
   
               <p><a href="Data+Science_Academy+Curriculum.pdf" target="_blank">Instuctions to Submit Event Permission Application&nbsp;&nbsp;For Download Form Click Here<img src="new.gif"></a></p>
    
                &nbsp;
               &nbsp;
               &nbsp;
   
               <p><a href="citizen-services/APPLICATION FOR THE GRANT OF LICENCE TO RUN PLACE OF ENTERTAINMENT(FUNCTION HALLCONVENTION CENTER).pdf" target="_blank">Application For The Grant of Licence to Run Place of Enterainment (Function Hall / Convention Center)&nbsp;&nbsp;For Download Form Click Here<img src="new.gif"></a></p>
               
                &nbsp;
               &nbsp;
               &nbsp;
   
               <p><a href="WomensHostelRegistration.html" target="_blank">Notification for Registration of Women Hostel in IT Corridor<img src="new.gif"></a><p>
   
                &nbsp;
               &nbsp;
               &nbsp;
   
               <p><a href="WomensHostelRegistration.html" target="_blank">Notification for Registration of Women Hostel in IT Corridor<img src="new.gif"></a><p>
   
                &nbsp;
               &nbsp;
               &nbsp;
   
               <p><a href="WomensHostelRegistration.html" target="_blank">Notification for Registration of Women Hostel in IT Corridor<img src="new.gif"></a><p>
   
                &nbsp;
               &nbsp;
               &nbsp;
   
   
               <p><a href="https://www.ncwwomenhelpline.in/" target="_blank">Visit to Women in Distress Website & helpline: 7827 170 170 <img src="new.gif"></a></p> 
               &nbsp;
               &nbsp;
               &nbsp;
   
   
               <p><a href="press-release/Abandoned Vehicles 2023 (665).pdf" target="_blank">Srikakulam Police To Auction 665 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
                <p><a href="press-release/Abandoned Vehicles 2023 (1000).pdf" target="_blank">Srikakulam Police To Auction 1000 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
                 <p><a href="press-release/Abandoned Vehicles 2023 (539).pdf" target="_blank">Srikakulam Police To Auction 539 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
               <p><a href="press-release/Abandoned Vehicles 2023 (1197).pdf" target="_blank">Srikakulam Police To Auction 1197 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
   
                <p><a href="press-release/Abandoned Vehicles 2023 (820).pdf" target="_blank">Srikakulam Police To Auction 820 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
   
                 <p><a href="press-release/Abandoned Vehicles 2023 (885).pdf" target="_blank">Srikakulam Police To Auction 885 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
   
                <p><a href="press-release/Abandoned Vehicles 2023 (756).pdf" target="_blank">Srikakulam Police To Auction 756 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
                <p><a href="press-release/Abandoned Vehicles 2023 (462).pdf" target="_blank">Srikakulam Police To Auction 462 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
   
               <p><a href="press-release/Abandoned Vehicles 2023 (527).pdf" target="_blank">Srikakulam Police To Auction 527 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
   
               &nbsp;
               &nbsp;
               &nbsp;
   
                <p><a href="press-release/Abandoned Vehicles 2023 (294).pdf" target="_blank">Srikakulam Police To Auction 294 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with   xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p> 
   
   
               <p>Citizens are Advised to Report the Cyber offenses in concerned L & O Police Stations. Now we are Registering the Cyber Crime Cases in local Law & Order Police Stations only.<img src="new.gif"></p>
           </form>
           </marquee>
        
       </div>
        </div>
      </form>
        <form action="#" method="post1">
        <div class="newsbar">
          <h2 style="text-align:center;  text-decoration: underline; ">PRESS RELEASE</h2>
          <div class="news" method="POST" >
             
      <marquee behavior="scroll" direction="up" onmouseover="this.stop();" onmouseout="this.start();">
     <form action="news_articles.php" method="POST">
          
 
             <p><a href="Data+Science_Academy+Curriculum.pdf" target="_blank">Instuctions to Submit Event Permission Application&nbsp;&nbsp;For Download Form Click Here<img src="new.gif"></a></p>
  
              &nbsp;
             &nbsp;
             &nbsp;
 
             <p><a href="citizen-services/APPLICATION FOR THE GRANT OF LICENCE TO RUN PLACE OF ENTERTAINMENT(FUNCTION HALLCONVENTION CENTER).pdf" target="_blank">Application For The Grant of Licence to Run Place of Enterainment (Function Hall / Convention Center)&nbsp;&nbsp;For Download Form Click Here<img src="new.gif"></a></p>
             
              &nbsp;
             &nbsp;
             &nbsp;
 
             <p><a href="WomensHostelRegistration.html" target="_blank">Notification for Registration of Women Hostel in IT Corridor<img src="new.gif"></a><p>
 
              &nbsp;
             &nbsp;
             &nbsp;
 
             <p><a href="WomensHostelRegistration.html" target="_blank">Notification for Registration of Women Hostel in IT Corridor<img src="new.gif"></a><p>
 
              &nbsp;
             &nbsp;
             &nbsp;
 
             <p><a href="WomensHostelRegistration.html" target="_blank">Notification for Registration of Women Hostel in IT Corridor<img src="new.gif"></a><p>
 
              &nbsp;
             &nbsp;
             &nbsp;
 
 
             <p><a href="https://www.ncwwomenhelpline.in/" target="_blank">Visit to Women in Distress Website & helpline: 7827 170 170 <img src="new.gif"></a></p> 
             &nbsp;
             &nbsp;
             &nbsp;
 
 
             <p><a href="press-release/Abandoned Vehicles 2023 (665).pdf" target="_blank">Srikakulam Police To Auction 665 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
              <p><a href="press-release/Abandoned Vehicles 2023 (1000).pdf" target="_blank">Srikakulam Police To Auction 1000 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
               <p><a href="press-release/Abandoned Vehicles 2023 (539).pdf" target="_blank">Srikakulam Police To Auction 539 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
             <p><a href="press-release/Abandoned Vehicles 2023 (1197).pdf" target="_blank">Srikakulam Police To Auction 1197 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
 
              <p><a href="press-release/Abandoned Vehicles 2023 (820).pdf" target="_blank">Srikakulam Police To Auction 820 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
 
               <p><a href="press-release/Abandoned Vehicles 2023 (885).pdf" target="_blank">Srikakulam Police To Auction 885 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
 
              <p><a href="press-release/Abandoned Vehicles 2023 (756).pdf" target="_blank">Srikakulam Police To Auction 756 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
              <p><a href="press-release/Abandoned Vehicles 2023 (462).pdf" target="_blank">Srikakulam Police To Auction 462 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
 
             <p><a href="press-release/Abandoned Vehicles 2023 (527).pdf" target="_blank">Srikakulam Police To Auction 527 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p>   
 
             &nbsp;
             &nbsp;
             &nbsp;
 
              <p><a href="press-release/Abandoned Vehicles 2023 (294).pdf" target="_blank">Srikakulam Police To Auction 294 Abandoned / Unclaimed Vehicles.&nbsp;&nbsp;If any queries contact with   xxxxxxxxxx.&nbsp;&nbsp;&nbsp; For Vehicle List Click Here<img src="new.gif"></a></p> 
 
 
             <p>Citizens are Advised to Report the Cyber offenses in concerned L & O Police Stations. Now we are Registering the Cyber Crime Cases in local Law & Order Police Stations only.<img src="new.gif"></p>
         </form>
         </marquee>
 
     </div>
      </div>
        </div>
      </form>
      <br>
<div class="hlayout">
        <section class="horizontal-layout">
            <!-- Twitter Section -->
            <div class="twitter-section">
                <h2>Srikakulam Police Department</h2>
                <a class="twitter-timeline" data-width="330" data-height="350" href="https://twitter.com/POLICESRIKAKULM?ref_src=twsrc%5Etfw">Tweets by POLICESRIKAKULM</a>
                <script async src="https://platform.twitter.com/widgets.js"></script>
            </div>
    
            <!-- Slideshow Section -->
            <div class="slideshow-container">
                <div class="mySlides fade">
                    <div class="numbertext">1 / 3</div>
                    <img src="images/2.jpg" style="width:100%">
                </div>
                <div class="mySlides fade">
                    <div class="numbertext">2 / 3</div>
                    <img src="images/1.jpg" style="width:100%">
                </div>
                <div class="mySlides fade">
                    <div class="numbertext">3 / 3</div>
                    <img src="images/3.jpg" style="width:100%">
                </div>
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            
            <!-- Facebook Section -->
            <div class="fb-post">
                <h2>Srikakulam Police Department</h2>
                <div class="fb-page" 
                    data-href="https://www.facebook.com/profile.php?id=100068435118920" 
                    data-tabs="timeline" 
                    data-width="330" 
                    data-height="350" 
                    data-small-header="false" 
                    data-adapt-container-width="true" 
                    data-hide-cover="false" 
                    data-show-facepile="true">
                    <blockquote cite="https://www.facebook.com/profile.php?id=100068435118920" 
                            class="fb-xfbml-parse-ignore">
                           <a href="https://www.facebook.com/profile.php?id=100068435118920">Srikakulam Police Department</a>
                    </blockquote>
                </div>
                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0"></script>
            </div>
        </section>
</div>
</div>



<section id="initiatives">
    <div class="container">
      <h1 class="text-center-section-heading">Our Initiatives</h1>
    </div>
    <div class="container">
      <div class="swiper initiatives-slider">
        <div class="swiper-wrapper">
          <!-- Slide-start -->
          <div class="swiper-slide initiative-slide">
            <div class="initiative-slide-img">
              <img src="images/1.jpg" alt="Initiative 1">
            </div>
            <div class="initiative-slide-content">
              <h1 class="initiative-title">Community Outreach</h1>
              <div class="initiative-slide-content-bottom">
                <h2 class="initiative-description">
                  Building Trust through Engagement
                </h2>
              </div>
            </div>
          </div>
          <!-- Slide-end -->
          <!-- Slide-start -->
          <div class="swiper-slide initiative-slide">
            <div class="initiative-slide-img">
              <img src="images/2.jpg" alt="Initiative 2">
            </div>
            <div class="initiative-slide-content">
              <h1 class="initiative-title">Safety Programs</h1>
              <div class="initiative-slide-content-bottom">
                <h2 class="initiative-description">
                  Road Safety and Accident Prevention
                </h2>
              </div>
            </div>
          </div>
          <!-- Slide-end -->
          <!-- Slide-start -->
          <div class="swiper-slide initiative-slide">
            <div class="initiative-slide-img">
              <img src="images/3.jpg" alt="Initiative 3">
            </div>
            <div class="initiative-slide-content">
              <h1 class="initiative-title">Women’s Safety</h1>
              <div class="initiative-slide-content-bottom">
                <h2 class="initiative-description">
                  Programs for Protecting Women
                </h2>
              </div>
            </div>
          </div>
          <!-- Slide-end -->
          <!-- Slide-start -->
          <div class="swiper-slide initiative-slide">
            <div class="initiative-slide-img">
              <img src="images/sp.jpg" alt="Initiative 4">
            </div>
            <div class="initiative-slide-content">
              <h1 class="initiative-title">Anti-Drug Campaign</h1>
              <div class="initiative-slide-content-bottom">
                <h2 class="initiative-description">
                  Awareness Programs Against Substance Abuse
                </h2>
              </div>
            </div>
          </div>
          <!-- Slide-end -->
        </div>
        <div class="slider-control" >
          <div class="swiper-button-prev slider-arrow" style="color: white"; >
            <ion-icon name="arrow-back-outline" ></ion-icon>
          </div>
          <div class="swiper-button-next slider-arrow" style="color: white";>
            <ion-icon name="arrow-forward-outline"></ion-icon>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </section>
<br>


<!-- Footer Section -->
<footer class="footer">
    
    <!-- Social Media Links -->
    <div class="footer-section social-media">
        <h3>Follow Us</h3>
        <div class="social-icons">
          <a href="#" aria-label="whatsapp"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/profile.php?id=100068435118920" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com/POLICESRIKAKULM" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/sklmpolice?igsh=aWV2Z3p1aDBlbHA3" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="Youtube"><i class="fab fa-youtube"></i></a>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="footer-section contact-info">
        <h3>Contact Us</h3>
        <p>Email: <a href="mailto:support@example.com">support@example.com</a></p>
        <p>Phone: <a href="tel:+1234567890">+1 234 567 890</a></p>
        <p>Address: <a href="https://www.google.com/maps/dir/?api=1&destination=8V5V+9PM,+RTC+Complex+Area,+Shanti+Nagar+Colony,+Balaga,+Srikakulam,+Andhra+Pradesh+532001" target="_blank">
            Get Directions
        </a>
        </p>
    </div>

    <!-- Help Us Section -->
    <div class="footer-section help-us">
        <h3>Help Us</h3>
        <p class="help-text">We appreciate any support you can provide to help us grow and improve. Your contributions go directly to enhancing our services and outreach.</p>
        <a href="#" class="donate-link">Donate Now</a>
    </div>
    
</footer>




    <script>
          

    </script>


<script>
       
    </script>



<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="banners.js"></script>
    <script src="slides.js"></script>
    <script src="initiatives.js"></script>
</body>
</html>6