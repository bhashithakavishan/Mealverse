<?php
session_start();
require __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');

$cart = $_SESSION['cart'] ?? [];
$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['customer_email'] ?? '');
$address = trim($_POST['shipping_address'] ?? '');

if (!$cart || $name === '' || strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255 || $address === '' || strlen($address) > 2000) {
    http_response_code(422);
    echo json_encode(['error' => 'Enter your name, a valid email, and a shipping address.']);
    exit;
}

$conn->begin_transaction();
try {
    $total = 0;
    $items = [];
    foreach ($cart as $itemId => $quantity) {
        $statement = $conn->prepare('SELECT id, price, stock FROM marketplace_items WHERE id = ? FOR UPDATE');
        $statement->bind_param('i', $itemId);
        $statement->execute();
        $item = $statement->get_result()->fetch_assoc();
        if (!$item || $quantity > $item['stock']) throw new Exception('An item is out of stock.');
        $total += (float) $item['price'] * $quantity;
        $items[] = [$item, $quantity];
    }

    $order = $conn->prepare('INSERT INTO orders (customer_name, customer_email, shipping_address, total) VALUES (?, ?, ?, ?)');
    $order->bind_param('sssd', $name, $email, $address, $total);
    $order->execute();
    $orderId = $conn->insert_id;

    foreach ($items as [$item, $quantity]) {
        $line = $conn->prepare('INSERT INTO order_items (order_id, item_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
        $line->bind_param('iiid', $orderId, $item['id'], $quantity, $item['price']);
        $line->execute();
        $stock = $conn->prepare('UPDATE marketplace_items SET stock = stock - ? WHERE id = ?');
        $stock->bind_param('ii', $quantity, $item['id']);
        $stock->execute();
    }

    $conn->commit();
    $_SESSION['cart'] = [];
    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Throwable $error) {
    $conn->rollback();
    http_response_code(409);
    echo json_encode(['error' => $error->getMessage()]);
}
$conn->close();
?>
