<?php
session_start();
require 'db.php';
header('Content-Type: application/json; charset=utf-8');

$_SESSION['cart'] ??= [];
$action = $_POST['action'] ?? $_GET['action'] ?? 'view';
$itemId = (int) ($_POST['item_id'] ?? $_GET['item_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

if ($action === 'add' || $action === 'update') {
    $statement = $conn->prepare('SELECT id, name, price, image, stock FROM marketplace_items WHERE id = ? AND stock > 0');
    $statement->bind_param('i', $itemId);
    $statement->execute();
    $item = $statement->get_result()->fetch_assoc();
    if (!$item) {
        http_response_code(404);
        echo json_encode(['error' => 'Item is unavailable.']);
        exit;
    }
    $_SESSION['cart'][$itemId] = min($quantity, (int) $item['stock']);
} elseif ($action === 'remove') {
    unset($_SESSION['cart'][$itemId]);
} elseif ($action === 'clear') {
    $_SESSION['cart'] = [];
}

$cart = [];
$total = 0;
foreach ($_SESSION['cart'] as $id => $count) {
    $statement = $conn->prepare('SELECT id, name, price, image, stock FROM marketplace_items WHERE id = ?');
    $statement->bind_param('i', $id);
    $statement->execute();
    $item = $statement->get_result()->fetch_assoc();
    if (!$item) continue;
    $item['quantity'] = min((int) $count, (int) $item['stock']);
    $_SESSION['cart'][$id] = $item['quantity'];
    $item['line_total'] = round((float) $item['price'] * $item['quantity'], 2);
    $total += $item['line_total'];
    $cart[] = $item;
}

echo json_encode(['items' => $cart, 'total' => round($total, 2), 'count' => array_sum($_SESSION['cart'])]);
$conn->close();
?>
