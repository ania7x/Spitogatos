<?php
session_start();
include 'db_connect.php'; // Include your database connection script

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Initialize an array to store validation errors
    $errors = [];

    // Sanitize and validate input data
    $area = isset($_POST['area']) ? $_POST['area'] : '';
    $price = isset($_POST['price']) ? intval($_POST['price']) : 0;
    $availability = isset($_POST['availability']) ? $_POST['availability'] : '';
    $size = isset($_POST['size']) ? intval($_POST['size']) : 0;

    // Validate required fields
    if (empty($area)) {
        $errors[] = "Area is required.";
    }
    if ($price <= 0) {
        $errors[] = "Price must be a positive number.";
    }
    if (empty($availability)) {
        $errors[] = "Availability is required.";
    }
    if ($size <= 0) {
        $errors[] = "Size must be a positive number.";
    }

    // Check for any validation errors
    if (empty($errors)) {
        // Insert house posting into the database
        $sql = "INSERT INTO house_postings (user_id, area, price, availability, size) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $user_id, $area, $price, $availability, $size);
    
        if ($stmt->execute()) {
            // House posting added successfully
            echo json_encode(array("status" => "success", "message" => "House posting added"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error adding house posting"));
        }

        $stmt->close(); // Close prepared statement
        $conn->close(); // Close database connection
        exit(); // Exit the script after echoing JSON response
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
    }
}

$conn->close(); // Close database connection if the form is not submitted
?>
