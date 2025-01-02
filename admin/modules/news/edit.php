// modules/news/edit.php
<?php
require_once '../../includes/config.php';
checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit();
}

// Fetch news item
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE news 
            SET title = ?, content = ?, status = ? 
            WHERE id = ?
        ");
        
        $stmt->execute([
            $_POST['title'],
            $_POST['content'],
            $_POST['status'],
            $id
        ]);

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit News</h1>
            <a href="index.php" class="btn-back">Back to List</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" 
                       value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required>
                    <?php echo htmlspecialchars($news['content']); ?>
                </textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft" <?php echo $news['status'] === 'draft' ? 'selected' : ''; ?>>
                        Draft
                    </option>
                    <option value="published" <?php echo $news['status'] === 'published' ? 'selected' : ''; ?>>
                        Published
                    </option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Update</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
