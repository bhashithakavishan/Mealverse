<?php
require __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Enter a valid email address.']);
    exit;
}

$statement = $conn->prepare('INSERT INTO newsletter_subscribers (email) VALUES (?) ON DUPLICATE KEY UPDATE email = VALUES(email)');
$statement->bind_param('s', $email);
$success = $statement->execute();
$statement->close();
$conn->close();

if (!$success) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Subscription could not be saved.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'You are subscribed to MealVerse updates.']);
?>