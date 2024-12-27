<?php
// File upload function
function uploadFile($file, $directory, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
    $targetDir = UPLOAD_PATH . $directory . '/';
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
    
    // Check file type
    if (!in_array($imageFileType, $allowedTypes)) {
        return ["success" => false, "message" => "Sorry, only " . implode(", ", $allowedTypes) . " files are allowed."];
    }
    
    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "Sorry, your file is too large."];
    }
    
    // Generate unique filename
    $newFileName = uniqid() . '.' . $imageFileType;
    $targetFile = $targetDir . $newFileName;
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ["success" => true, "path" => "uploads/" . $directory . "/" . $newFileName];
    } else {
        return ["success" => false, "message" => "Sorry, there was an error uploading your file."];
    }
}

// Delete file function
function deleteFile($filePath) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/srikakulam_police/' . $filePath;
    if (file_exists($fullPath)) {
        unlink($fullPath);
        return true;
    }
    return false;
}

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Format date
function formatDate($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

// Check if user is logged in
function checkLogin() {
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ../../login.php");
        exit;
    }
}
?>
