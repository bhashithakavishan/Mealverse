<?php
require __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: addrecipe.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$chef = trim($_POST['chef'] ?? '');
$cuisine = trim($_POST['cuisine'] ?? '');
$category = trim($_POST['category'] ?? '');
$ingredients = [];
$steps = [];
$allowedChefs = ['Tharusha Lakshitha', 'Thimira Nimsara', 'Bhashitha Dharmarathna', 'Pavithra Wijesooriya', 'Chamidu Sandamal', 'Isuru Kumara'];
$allowedCuisines = ['Sri Lankan', 'Italian', 'Mexican', 'Asian', 'French'];
$allowedCategories = ['Main Course', 'Dessert', 'Appetizer', 'Breakfast', 'Soups', 'Salads', 'Beverages'];

foreach ($_POST['ingredients'] ?? [] as $index => $ingredient) {
    $name = trim($ingredient);
    if ($name === '') {
        continue;
    }
    $quantity = trim($_POST['quantities'][$index] ?? '');
    $ingredients[] = $name . ($quantity !== '' ? " ($quantity)" : '');
}

foreach ($_POST['steps'] ?? [] as $step) {
    $step = trim($step);
    if ($step !== '') {
        $steps[] = $step;
    }
}

if ($title === '' || strlen($title) > 255 || !in_array($chef, $allowedChefs, true) || !in_array($cuisine, $allowedCuisines, true) || !in_array($category, $allowedCategories, true) || !$ingredients || !$steps) {
    exit('Complete the recipe fields, ingredients, and instructions.');
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
$fileType = isset($_FILES['recipe_image']['tmp_name']) ? (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['recipe_image']['tmp_name']) : '';
if (!isset($_FILES['recipe_image']) || $_FILES['recipe_image']['error'] !== UPLOAD_ERR_OK || !in_array($fileType, $allowedTypes, true)) {
    exit('Upload a valid JPG, PNG, or WEBP recipe image.');
}

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    exit('The upload directory could not be created.');
}

$extension = strtolower(pathinfo($_FILES['recipe_image']['name'], PATHINFO_EXTENSION));
$fileName = bin2hex(random_bytes(12)) . '.' . $extension;
$imagePath = 'uploads/' . $fileName;
if (!move_uploaded_file($_FILES['recipe_image']['tmp_name'], $uploadDir . $fileName)) {
    exit('The recipe image could not be uploaded.');
}

$ingredientText = implode("\n", $ingredients);
$instructionText = implode("\n", array_map(static fn ($step, $index) => ($index + 1) . '. ' . $step, $steps, array_keys($steps)));
$statement = $conn->prepare('INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions) VALUES (?, ?, ?, ?, ?, ?, ?)');
$statement->bind_param('sssssss', $title, $chef, $cuisine, $category, $imagePath, $ingredientText, $instructionText);

if (!$statement->execute()) {
    @unlink($uploadDir . $fileName);
    exit('The recipe could not be saved. Please try again.');
}

$statement->close();
$conn->close();
echo '<script>alert("Recipe published successfully."); window.location.href="addrecipe.php";</script>';
?>
