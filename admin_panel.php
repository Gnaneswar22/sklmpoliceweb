<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$dbname = 'srikakulam_police';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: admin_panel.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_panel.php");
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .login-container { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 300px; }
            .login-container h2 { text-align: center; margin-bottom: 20px; }
            .form-group { margin-bottom: 15px; }
            .form-group label { display: block; margin-bottom: 5px; }
            .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
            .btn { width: 100%; padding: 10px; background: #1a73e8; color: white; border: none; border-radius: 5px; cursor: pointer; }
            .btn:hover { background: #1557b0; }
            .error { color: red; text-align: center; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Admin Login</h2>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $response = ['success' => false, 'message' => ''];

    try {
        switch ($_POST['action']) {
            case 'add_news':
                $stmt = $conn->prepare("INSERT INTO news (title, link, description) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['link'], $_POST['description']]);
                $response['success'] = true;
                break;

            case 'delete_news':
                $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $response['success'] = true;
                break;

            case 'update_news':
                $stmt = $conn->prepare("UPDATE news SET title = ?, link = ?, description = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['link'], $_POST['description'], $_POST['id']]);
                $response['success'] = true;
                break;

            // Add similar cases for other tables like press_releases, officers, etc.
        }
    } catch (PDOException $e) {
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    exit();
}

// Fetch Data for Tables
if (isset($_GET['fetch'])) {
    $table = $_GET['fetch'];
    $stmt = $conn->query("SELECT * FROM $table");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f6f9; }
        .sidebar { width: 200px; background: #2c3e50; color: white; height: 100vh; position: fixed; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li { padding: 15px; }
        .sidebar ul li a { color: white; text-decoration: none; display: block; }
        .sidebar ul li a:hover { background: #34495e; }
        .main-content { margin-left: 200px; padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background: #f4f4f4; }
        .btn { padding: 5px 10px; background: #1a73e8; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .btn:hover { background: #1557b0; }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="#" onclick="loadSection('news')">News</a></li>
            <li><a href="#" onclick="loadSection('press_releases')">Press Releases</a></li>
            <li><a href="#" onclick="loadSection('helplines')">Helplines</a></li>
            <li><a href="#" onclick="loadSection('officers')">Officers</a></li>
            <li><a href="?logout=1">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <div id="section-content"></div>
    </div>

    <script>
        function loadSection(table) {
            fetch(`?fetch=${table}`)
                .then(response => response.json())
                .then(data => {
                    let content = `<h2>${table.replace('_', ' ').toUpperCase()}</h2>`;
                    content += `<table class="table"><thead><tr>`;
                    for (let key in data[0]) content += `<th>${key}</th>`;
                    content += `<th>Actions</th></tr></thead><tbody>`;
                    data.forEach(row => {
                        content += `<tr>`;
                        for (let key in row) content += `<td>${row[key]}</td>`;
                        content += `
                            <td>
                                <button class="btn" onclick="editRow(${row.id}, '${table}')">Edit</button>
                                <button class="btn" onclick="deleteRow(${row.id}, '${table}')">Delete</button>
                            </td>
                        </tr>`;
                    });
                    content += `</tbody></table>`;
                    document.getElementById('section-content').innerHTML = content;
                });
        }

        function deleteRow(id, table) {
            if (confirm('Are you sure you want to delete this row?')) {
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete_${table}&id=${id}`
                }).then(() => loadSection(table));
            }
        }

        // Add editRow and other functions as needed
    </script>
</body>
</html>
