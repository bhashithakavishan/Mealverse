<?php
require __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$isAjax = ($_SERVER['HTTP_ACCEPT'] ?? '') === 'application/json';
$success = false;

if ($name === '' || strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255 || $subject === '' || strlen($subject) > 255 || $message === '' || strlen($message) > 5000) {
    http_response_code(422);
    $confirmation = 'Please complete all fields with a valid email address.';
} else {
    $statement = $conn->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
    $statement->bind_param('ssss', $name, $email, $subject, $message);

    if ($statement->execute()) {
        $confirmation = 'Your message was sent successfully. Our team will get back to you soon.';
        $success = true;
    } else {
        http_response_code(500);
        $confirmation = 'We could not save your message. Please try again.';
    }
    $statement->close();
}

$conn->close();

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    if (!$success && http_response_code() < 400) {
        http_response_code(422);
    }
    echo json_encode([
        'success' => $success,
        'message' => $confirmation
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Status - MealVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container py-5 text-center">
        <div class="contact-form-card bg-white p-5 rounded-4 shadow-sm mx-auto" style="max-width: 640px;">
            <h1 class="fw-bold mb-3">Message Status</h1>
            <p class="lead mb-4"><?php echo htmlspecialchars($confirmation, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="contact.php" class="btn btn-primary rounded-pill px-4">Back to Contact</a>
        </div>
    </main>
</body>
</html>
