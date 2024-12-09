<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$id = (int)$_GET['id'];
$type = $_GET['type'];

try {
    if ($type === 'news') {
        $stmt = $conn->prepare("
            SELECT id, title, content, news_date as date 
            FROM news_items 
            WHERE id = :id AND is_active = 1
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT id, title, content, release_date as date 
            FROM press_releases 
            WHERE id = :id AND is_active = 1
        ");
    }
    
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($item) {
        echo json_encode($item);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>
