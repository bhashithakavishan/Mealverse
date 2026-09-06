<?php
$host = getenv('MEALVERSE_DB_HOST') ?: '127.0.0.1';
$user = getenv('MEALVERSE_DB_USER') ?: 'root';
$password = getenv('MEALVERSE_DB_PASSWORD') ?: '';
$database = getenv('MEALVERSE_DB_NAME') ?: 'mealverse_db';
$port = (int) (getenv('MEALVERSE_DB_PORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($host, $user, $password, '', $port);

if ($conn->connect_errno) {
    die('Database server connection failed: ' . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'));
}

$databaseName = '`' . str_replace('`', '``', $database) . '`';
if (!$conn->query("CREATE DATABASE IF NOT EXISTS $databaseName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die('Database creation failed: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
}

if (!$conn->select_db($database)) {
    die('Database selection failed: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
}

$conn->set_charset('utf8mb4');

$schema = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS recipes (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        chef VARCHAR(100) NOT NULL,
        cuisine VARCHAR(100) NOT NULL,
        category VARCHAR(100) NOT NULL,
        image VARCHAR(500) NOT NULL,
        ingredients TEXT NOT NULL,
        instructions TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS marketplace_items (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category VARCHAR(50) NOT NULL,
        description TEXT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(500) NOT NULL,
        stock INT UNSIGNED NOT NULL DEFAULT 100,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS orders (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(100) NOT NULL,
        customer_email VARCHAR(255) NOT NULL,
        shipping_address TEXT NOT NULL,
        total DECIMAL(10, 2) NOT NULL,
        status VARCHAR(30) NOT NULL DEFAULT 'confirmed',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS order_items (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id INT UNSIGNED NOT NULL,
        item_id INT UNSIGNED NOT NULL,
        quantity INT UNSIGNED NOT NULL,
        unit_price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES marketplace_items(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS messages (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

foreach ($schema as $statement) {
    if (!$conn->query($statement)) {
        die('Database table setup failed: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
    }
}

$columnCheck = $conn->query("SHOW COLUMNS FROM recipes LIKE 'ingredients'");
if ($columnCheck && $columnCheck->num_rows === 0 && !$conn->query('ALTER TABLE recipes ADD ingredients TEXT NOT NULL AFTER image')) {
    die('Database migration failed: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
}
?>