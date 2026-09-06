<?php
// Start user session
session_start();

// Include database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // --- SIGN UP LOGIC ---
    if ($action == "signup") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        if ($name === '' || $email === '' || $password === '') {
            die('Name, email, and password are required.');
        }

        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "<script>
                    alert('Email already registered! Please sign in.');
                    window.location.href = 'signin.html';
                  </script>";
        } else {
            // Hash password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('Registration successful! Please sign in.');
                        window.location.href = 'signin.html';
                      </script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    // --- SIGN IN LOGIC ---
    if ($action == "signin") {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify entered password with hashed password
            if (password_verify($password, $user['password'])) {
                // Save user details to session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                echo "<script>
                        alert('Welcome back, " . $user['name'] . "!');
                        window.location.href = 'index.html';
                      </script>";
            } else {
                echo "<script>
                        alert('Invalid password! Try again.');
                        window.location.href = 'signin.html';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('User not found with this email!');
                    window.location.href = 'signin.html';
                  </script>";
        }
    }
}

$conn->close();
?>