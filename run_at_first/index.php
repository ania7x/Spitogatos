<?php
// Include database connection file
include 'C:\xampp\htdocs\spitogatos\db_connect.php';

// Check if the database has been initialized
$sql = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$count = $row['count'];

// If the users table is empty, run the initialization script
if ($count == 0) {
    include 'add_users.php'; // Include the initialization script
}
else{ echo "Users already added";}


// Your remaining application code goes here
?>
