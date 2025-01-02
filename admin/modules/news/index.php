// modules/news/index.php
<?php
require_once '../../includes/config.php';
checkLogin();

// Fetch all news
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$news_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage News</h1>
            <a href="add.php" class="btn-add">
                <i class="fas fa-plus"></i> Add News
            </a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news_items as $news): ?>
                <tr>
                    <td><?php echo htmlspecialchars($news['title']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $news['status']; ?>">
                            <?php echo ucfirst($news['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($news['created_at'])); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $news['id']; ?>" 
                           class="btn-action edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="delete.php?id=<?php echo $news['id']; ?>" 
                           class="btn-action delete" 
                           onclick="return confirm('Are you sure?')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
