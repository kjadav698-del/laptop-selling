<?php
session_start();
require_once '../db_connection.php'; // Include your DB connection file

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handling the uploaded image
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];

    // If there are no errors, proceed to upload
    if ($imageError === 0) {
        // Generate a unique name for the image file
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);  // Get the file extension (e.g., jpg, png)
        $imageExtension = strtolower($imageExtension); // Convert to lowercase
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file extensions

        // Check if the file has an allowed extension
        if (in_array($imageExtension, $allowedExtensions)) {
            // Generate a new name for the image (e.g., using a unique ID or timestamp)
            $imageNewName = uniqid('', true) . "." . $imageExtension;
            $imageDestination = '../upload/' . $imageNewName;  // Path to save the image

            // Move the image to the 'uploads' directory
            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                // Insert data into the database
                $sql = "INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $name, $price, $imageDestination, $description); // Bind parameters
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Product added successfully!";
                    header("Location: manage_product.php");
                } else {
                    $_SESSION['message'] = "Error adding product to the database.";
                }
            } else {
                $_SESSION['message'] = "Failed to upload image.";
            }
        } else {
            $_SESSION['message'] = "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
        }
    } else {
        $_SESSION['message'] = "Error uploading the image. Please try again.";
    }
}
?>