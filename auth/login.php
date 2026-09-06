<?php
session_start();
require __DIR__ . '/../includes/db.php';

function authResponse(string $message, string $location): never
{
    echo '<script>alert(' . json_encode($message) . '); window.location.href=' . json_encode($location) . ';</script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.php');
    exit;
}

$action = $_POST['action'] ?? '';
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    authResponse('Enter a valid email and password.', 'signin.php');
}

if ($action === 'signup') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        authResponse('Enter your name to create an account.', 'signin.php');
    }

    $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $check->bind_param('s', $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        authResponse('Email already registered. Please sign in.', 'signin.php');
    }
    $check->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
    $statement->bind_param('sss', $name, $email, $hash);
    if (!$statement->execute()) {
        $statement->close();
        authResponse('Registration failed. Please try again.', 'signin.php');
    }
    $statement->close();
    authResponse('Registration successful. Please sign in.', 'signin.php');
}

if ($action === 'signin') {
    $statement = $conn->prepare('SELECT id, name, password FROM users WHERE email = ? LIMIT 1');
    $statement->bind_param('s', $email);
    $statement->execute();
    $user = $statement->get_result()->fetch_assoc();
    $statement->close();

    if (!$user || !password_verify($password, $user['password'])) {
        authResponse('Invalid email or password.', 'signin.php');
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    authResponse('Welcome back, ' . $user['name'] . '!', 'index.php');
}

authResponse('Invalid authentication request.', 'signin.php');
?>
