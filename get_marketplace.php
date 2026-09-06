<?php
require 'db.php';
header('Content-Type: application/json; charset=utf-8');

$result = $conn->query('SELECT id, name, category, description, price, image, stock FROM marketplace_items WHERE stock > 0 ORDER BY id');
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to load marketplace items.']);
    exit;
}
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
$conn->close();
?>
