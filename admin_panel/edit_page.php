<?php
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: pages.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE pages SET title = ?, content = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $content, $status, $id]);

    header('Location: pages.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
$stmt->execute([$id]);
$page = $stmt->fetch();

if (!$page) {
    header('Location: pages.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <style>
        /* Copy the CSS from previous example */
    </style>
</head>
<body>
    <div class="sidebar">
        <!-- Same sidebar as dashboard -->
    </div>

    <div class="content">
        <div class="card">
            <h3>Edit Page</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Page Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($page['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($page['content']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="draft" <?php echo $page['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $page['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
                <button type="submit" class="btn">Update Page</button>
                <a href="pages.php" class="btn" style="background-color: #6c757d;">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
