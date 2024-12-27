<?php
require_once 'config.php';
requireLogin();

// Fetch quick stats
$stmt = $pdo->query("SELECT COUNT(*) as page_count FROM pages");
$pageCount = $stmt->fetch()['page_count'];

$stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
$userCount = $stmt->fetch()['user_count'];

// Fetch recent pages
$stmt = $pdo->query("SELECT * FROM pages ORDER BY updated_at DESC LIMIT 5");
$recentPages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Copy the CSS from previous dashboard.html */
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="color: white; text-align: center;">Admin Panel</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="pages.php">Pages</a>
        <a href="users.php">Users</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="grid">
            <div class="card">
                <h3>Quick Stats</h3>
                <p>Total Pages: <?php echo $pageCount; ?></p>
                <p>Total Users: <?php echo $userCount; ?></p>
            </div>
            <div class="card">
                <h3>Recent Activity</h3>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
        </div>

        <div class="card">
            <h3>Recent Pages</h3>
            <table>
                <thead>
                    <tr>
                        <th>Page Title</th>
                        <th>Last Modified</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo $page['updated_at']; ?></td>
                        <td><?php echo ucfirst($page['status']); ?></td>
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
