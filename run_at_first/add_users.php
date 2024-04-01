<?php
// Include database connection file
include 'C:\xampp\htdocs\spitogatos\db_connect.php';

// Array of predetermined usernames and passwords
$users = array(
    array("username" => "user1", "password" => "password1"),
    array("username" => "user2", "password" => "password2"),
    array("username" => "user3", "password" => "password3"),
    array("username" => "user14", "password" => "password1"),
    array("username" => "user5", "password" => "password2"),
    array("username" => "user6", "password" => "password3"),
    array("username" => "user7", "password" => "password1"),
    array("username" => "user8", "password" => "password2"),
    array("username" => "user9", "password" => "password3"),
    array("username" => "user10", "password" => "password1"),
    array("username" => "user21", "password" => "password2"),
    array("username" => "user32", "password" => "password3")
);

// Hash passwords and insert data into the database
foreach ($users as $user) {
    $username = $user['username'];
    $password = $user['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user data into the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "User '$username' inserted successfully<br>";
    } else {
        echo "Error inserting user '$username': " . $conn->error . "<br>";
    }
}

// Close the database connection
$conn->close();
?>
