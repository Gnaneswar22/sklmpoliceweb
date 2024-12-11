<?php
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

// Initialize error array
$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate and handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Set up file upload parameters
            $target_dir = "uploads/images/";
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $max_file_size = 5000000; // 5MB

            // Create uploads directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Get file information
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Validate file type
            if (!in_array($file_extension, $allowed_types)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Validate file size
            if ($_FILES["image"]["size"] > $max_file_size) {
                throw new Exception("File is too large. Maximum size is 5MB.");
            }

            // Move uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Get other form data
                $alt_text = $_POST['alt_text'] ?? '';
                $order_position = $_POST['order_position'] ?? 0;
                $is_active = isset($_POST['is_active']) ? 1 : 0;

                // Insert into database
                $sql = "INSERT INTO about_slider (image_path, alt_text, order_position, is_active) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $target_file, $alt_text, $order_position, $is_active);

                if ($stmt->execute()) {
                    $success = true;
                    header("Location: about_slider.php?success=1");
                    exit();
                } else {
                    throw new Exception("Database error: " . $conn->error);
                }
            } else {
                throw new Exception("Failed to upload file.");
            }
        } else {
            throw new Exception("Please select an image to upload.");
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Slider Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Add Slider Image</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Image uploaded successfully!
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" required accept="image/*">
                                <small class="text-muted">Allowed types: JPG, JPEG, PNG, GIF. Max size: 5MB</small>
                            </div>

                            <div class="mb-3">
                                <label for="alt_text" class="form-label">Alt Text</label>
                                <input type="text" class="form-control" id="alt_text" name="alt_text" 
                                       value="<?php echo isset($_POST['alt_text']) ? htmlspecialchars($_POST['alt_text']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="order_position" class="form-label">Order Position</label>
                                <input type="number" class="form-control" id="order_position" name="order_position" 
                                       value="<?php echo isset($_POST['order_position']) ? htmlspecialchars($_POST['order_position']) : '0'; ?>">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <a href="about_slider.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
