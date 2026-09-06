<?php
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $statement = $conn->prepare('SELECT id, title, chef, cuisine, category, image, ingredients, instructions, created_at FROM recipes WHERE id = ?');
    $statement->bind_param('i', $id);
} else {
    $statement = $conn->prepare('SELECT id, title, chef, cuisine, category, image, ingredients, instructions, created_at FROM recipes ORDER BY created_at DESC, id DESC');
}

if (!$statement || !$statement->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to load recipes.']);
    exit;
}

$result = $statement->get_result();
if ($id) {
    $recipe = $result->fetch_assoc();
    if (!$recipe) {
        http_response_code(404);
        echo json_encode(['error' => 'Recipe not found.']);
    } else {
        echo json_encode($recipe);
    }
} else {
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

$statement->close();
$conn->close();
?>