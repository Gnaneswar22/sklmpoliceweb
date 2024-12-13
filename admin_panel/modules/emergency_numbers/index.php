<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}
require_once "../../includes/config.php";

// Local time function
function parseLocalTime($timeString) {
    preg_match('/<local_time>(.*?)<\/local_time>/', $timeString, $matches);
    if (isset($matches[1])) {
        return trim(explode(' in ', $matches[1])[0]);
    }
    return date('Y-m-d H:i:s');
}

$timeString = "<local_time>2024-12-12 20:14:48 in YYYY-MM-DD HH:mm:ss format</local_time>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Numbers Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/common.css">
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <div class="logo-container">
                <img src="../../assets/images/logo.png" alt="Logo" class="logo">
            </div>
            <div class="user-info">
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h3>
                <div class="date-time">
                    <?php echo parseLocalTime($timeString); ?>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="../../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../nav_menu_items/"><i class="fas fa-bars"></i> Navigation Menu</a></li>
                <li><a href="../hero_slides/"><i class="fas fa-sliders-h"></i> Hero Slides</a></li>
                <li><a href="../about_section/"><i class="fas fa-info-circle"></i> About Section</a></li>
                <li><a href="../about_slider/"><i class="fas fa-images"></i> About Slider</a></li>
                <li><a href="../emergency_numbers/" class="active"><i class="fas fa-phone"></i> Emergency Numbers</a></li>
                <li><a href="../news_items/"><i class="fas fa-newspaper"></i> News Items</a></li>
                <li><a href="../press_releases/"><i class="fas fa-file-alt"></i> Press Releases</a></li>
                <li><a href="../gallery/"><i class="fas fa-camera"></i> Gallery</a></li>
                <li><a href="../initiatives/"><i class="fas fa-lightbulb"></i> Initiatives</a></li>
                <li><a href="../services/"><i class="fas fa-cogs"></i> Services</a></li>
                <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="module-header">
                <h1>Emergency Numbers Management</h1>
                <div class="action-buttons">
                    <button onclick="undoDelete()" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Undo Delete
                    </button>
                    <a href="create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Number
                    </a>
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Phone Number</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM emergency_numbers ORDER BY id";
                        $result = mysqli_query($conn, $sql);

                        while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td><i class="<?php echo htmlspecialchars($row['icon']); ?>"></i></td>
                                <td>
                                    <span class="status-badge <?php echo $row['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this number?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }

                        if(mysqli_num_rows($result) == 0) {
                            echo "<tr><td colspan='5' class='no-records'>No emergency numbers found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
    function undoDelete() {
        if(confirm('Do you want to restore the last deleted emergency number?')) {
            fetch('undo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Unable to restore the emergency number');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while trying to restore the emergency number');
            });
        }
    }

    // Update time
    function updateTime() {
        const timeElement = document.querySelector('.date-time');
        const now = new Date();
        const formattedTime = now.getFullYear() + '-' + 
            String(now.getMonth() + 1).padStart(2, '0') + '-' + 
            String(now.getDate()).padStart(2, '0') + ' ' + 
            String(now.getHours()).padStart(2, '0') + ':' + 
            String(now.getMinutes()).padStart(2, '0') + ':' + 
            String(now.getSeconds()).padStart(2, '0');
        
        timeElement.textContent = formattedTime;
    }

    setInterval(updateTime, 1000);
    updateTime();
    </script>
</body>
</html>
