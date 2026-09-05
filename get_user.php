<?php
// Start session to access logged-in user details
session_start();

// Set header to JSON format
header('Content-Type: application/json');

// Check if user is logged in
if (isset($_SESSION['user_name'])) {
    echo json_encode([
        'loggedIn' => true,
        'name' => $_SESSION['user_name']
    ]);
} else {
    echo json_encode([
        'loggedIn' => false
    ]);
}
?>