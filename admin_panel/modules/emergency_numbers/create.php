<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

// Initialize variables
$service_name = $phone_number = $icon = "";
$is_active = 1;
$service_name_err = $phone_number_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate service name
    if(empty(trim($_POST["service_name"]))){
        $service_name_err = "Please enter a service name.";
    } else {
        $service_name = trim($_POST["service_name"]);
    }
    
    // Validate phone number
    if(empty(trim($_POST["phone_number"]))){
        $phone_number_err = "Please enter a phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }
    
    // Get icon
    $icon = !empty($_POST["icon"]) ? trim($_POST["icon"]) : "fas fa-phone";
    
    // Get is_active status
    $is_active = isset($_POST["is_active"]) ? 1 : 0;
    
    // If no errors, proceed with insertion
    if(empty($service_name_err) && empty($phone_number_err)){
        $sql = "INSERT INTO emergency_numbers (service_name, phone_number, icon, is_active) VALUES (?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_service_name, $param_phone_number, $param_icon, $param_active);
            
            $param_service_name = $service_name;
            $param_phone_number = $phone_number;
            $param_icon = $icon;
            $param_active = $is_active;
            
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Emergency number added successfully.";
                // Clear form data after successful insertion
                $service_name = $phone_number = "";
                $is_active = 1;
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Get current time
$timeString = "<local_time>2024-12-12 20:53:48 in YYYY-MM-DD HH:mm:ss format</local_time>";
function parseLocalTime($timeString) {
    preg_match('/<local_time>(.*?)<\/local_time>/', $timeString, $matches);
    if (isset($matches[1])) {
        return trim(explode(' in ', $matches[1])[0]);
    }
    return date('Y-m-d H:i:s');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Emergency Number</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/form.css">
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <!-- Same sidebar as index.php -->
        </nav>

        <main class="content">
            <div class="form-wrapper">
                <div class="form-container">
                    <div class="form-header">
                        <h2>Add Emergency Number</h2>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Service Name</label>
                            <input type="text" name="service_name" class="form-control <?php echo (!empty($service_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $service_name; ?>">
                            <?php if (!empty($service_name_err)): ?>
                                <div class="invalid-feedback"><?php echo $service_name_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                            <?php if (!empty($phone_number_err)): ?>
                                <div class="invalid-feedback"><?php echo $phone_number_err; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Icon Class (Optional)</label>
                            <input type="text" name="icon" class="form-control" value="<?php echo $icon; ?>" placeholder="fas fa-phone">
                            <div class="help-text">Example: fas fa-phone, fas fa-ambulance, etc.</div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>>
                                Active
                            </label>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Add Number
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
