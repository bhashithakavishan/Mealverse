<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MealVerse</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container py-5">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <a href="index.php" class="btn btn-primary">Continue to MealVerse</a>
    </main>
</body>
</html>
