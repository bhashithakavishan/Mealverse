<?php
// Include database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Get basic text fields
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $chef = mysqli_real_escape_string($conn, $_POST['chef']);
    $cuisine = mysqli_real_escape_string($conn, $_POST['cuisine']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // 2. Combine steps into one formatted text
    $instructions = "";
    if (!empty($_POST['steps'])) {
        foreach ($_POST['steps'] as $index => $step) {
            $stepClean = mysqli_real_escape_string($conn, trim($step));
            $instructions .= ($index + 1) . ". " . $stepClean . "\n";
        }
    }

    // 3. Handle Recipe Image Upload
    $imagePath = "";
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] == 0) {
        $uploadDir = "uploads/";
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Create a unique file name
        $fileName = time() . "_" . basename($_FILES["recipe_image"]["name"]);
        $targetFilePath = $uploadDir . $fileName;

        // Move uploaded file to uploads directory
        if (move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

    // 4. Save data into database
    $sql = "INSERT INTO recipes (title, chef, cuisine, category, image, instructions) 
            VALUES ('$title', '$chef', '$cuisine', '$category', '$imagePath', '$instructions')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Recipe published successfully with image and steps!');
                window.location.href = 'addrecipe.html';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>