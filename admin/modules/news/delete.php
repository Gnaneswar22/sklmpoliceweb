// modules/news/delete.php
<?php
require_once '../../includes/config.php';
checkLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Log error
    }
}

header('Location: index.php');
exit();
?>
