<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

require_once "../../includes/config.php";

// Initialize variables
$title = $link = $order_position = "";
$parent_id = null;
$is_active = 1;
$title_err = $link_err = $order_err = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);

    // Fetch the existing record
    $sql = "SELECT * FROM nav_menu_items WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                $title = $row["title"];
                $parent_id = $row["parent_id"];
                $link = $row["link"];
                $order_position = $row["order_position"];
                $is_active = $row["is_active"];
            } else {
                echo "Error: Record not found.";
                header("location: index.php");
                exit();
            }
        } else {
            echo "Error: Could not execute the query.";
        }
    }
} elseif($_SERVER["REQUEST_METHOD"] == "POST"){
    // Process the submitted form
    $id = trim($_POST["id"]);

    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate link
    if(empty(trim($_POST["link"]))){
        $link_err = "Please enter a link.";
    } else {
        $link = trim($_POST["link"]);
    }

    // Validate order position
    if(empty(trim($_POST["order_position"]))){
        $order_err = "Please enter order position.";
    } else {
        $order_position = trim($_POST["order_position"]);
    }

    $parent_id = !empty($_POST["parent_id"]) ? trim($_POST["parent_id"]) : null;
    $is_active = isset($_POST["is_active"]) ? 1 : 0;

    // Update the record if there are no errors
    if(empty($title_err) && empty($link_err) && empty($order_err)){
        $sql = "UPDATE nav_menu_items SET title = ?, parent_id = ?, link = ?, order_position = ?, is_active = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sisiii", $title, $parent_id, $link, $order_position, $is_active, $id);
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else {
                echo "Error: Could not execute the update statement.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Navigation Menu Item</title>
    <link rel="stylesheet" href="../../css/form.css">
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <div class="form-header">
                <h2>Edit Navigation Menu Item</h2>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                    <?php if(!empty($title_err)): ?>
                        <span class="invalid-feedback"><?php echo $title_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Parent Menu</label>
                    <select name="parent_id" class="form-control">
                        <option value="">None</option>
                        <?php
                        $sql = "SELECT id, title FROM nav_menu_items WHERE id != ?";
                        if($stmt = mysqli_prepare($conn, $sql)){
                            mysqli_stmt_bind_param($stmt, "i", $id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            while($row = mysqli_fetch_array($result)){
                                $selected = ($row['id'] == $parent_id) ? 'selected' : '';
                                echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['title']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Link</label>
                    <input type="text" name="link" class="form-control <?php echo (!empty($link_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $link; ?>">
                    <?php if(!empty($link_err)): ?>
                        <span class="invalid-feedback"><?php echo $link_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Order Position</label>
                    <input type="number" name="order_position" class="form-control <?php echo (!empty($order_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $order_position; ?>">
                    <?php if(!empty($order_err)): ?>
                        <span class="invalid-feedback"><?php echo $order_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>> Active
                    </label>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
