<?php
require_once 'config.php';
requireLogin();

// Handle page creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $slug = strtolower(str_replace(' ', '-', $title));

    $stmt = $pdo->prepare("INSERT INTO pages (title, slug, content, status, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $content, $status, $_SESSION['user_id']]);
    
    header('Location: pages.php');
    exit();
}

// Fetch all pages
$stmt = $pdo->query("SELECT * FROM pages ORDER BY created_at DESC");
$pages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pages</title>
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
            <h3>Create New Page</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Page Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <button type="submit" class="btn">Create Page</button>
            </form>
        </div>

        <div class="card">
            <h3>All Pages</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo ucfirst($page['status']); ?></td>
                        <td><?php echo $page['created_at']; ?></td>
                        <td>
                            <a href="edit_page.php?id=<?php echo $page['id']; ?>" class="btn">Edit</a>
                            <a href="delete_page.php?id=<?php echo $page['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
