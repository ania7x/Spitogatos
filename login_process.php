<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Query the database for the user
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($_POST['password'], $row['password'])) {
            // Password is correct, set session variable and redirect to dashboard
            $_SESSION['user_id'] = $row['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            header("Location: login.html");
            exit();
        }
    } else {
        // Username not found
        header("Location: login.html");
        exit();
    }
}

$conn->close();
?>
