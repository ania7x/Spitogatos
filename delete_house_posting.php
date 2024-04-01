<?php
session_start();
include 'db_connect.php'; // Include your database connection script

// Retrieve the user's ID from the session
$user_id = $_SESSION['user_id'];

// Check if a house posting should be deleted
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    // Ensure the house posting belongs to the user before deleting
    $check_sql = "SELECT * FROM house_postings WHERE id = $delete_id AND user_id = $user_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows == 1) {
        // house posting belongs to the user, proceed with deletion
        $delete_sql = "DELETE FROM house_postings WHERE id = $delete_id";
        
        if ($conn->query($delete_sql)) {
            echo json_encode(array("status" => "success", "message" => "house posting deleted successfully"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error deleting house posting"));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "house posting does not exist or does not belong to you"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "No delete ID provided"));
}

$conn->close();
exit();
?>
