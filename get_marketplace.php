<?php
require __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');

$statement = $conn->prepare('SELECT id, name, category, description, price, image, stock FROM marketplace_items WHERE stock > 0 ORDER BY id');
if (!$statement || !$statement->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to load marketplace items.']);
    exit;
}
$result = $statement->get_result();
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
$statement->close();
$conn->close();
?>
