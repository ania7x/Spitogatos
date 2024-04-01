<?php
// Function to hash a password
function hashPassword($password) {
    // Use password_hash() function to hash the password
    // PASSWORD_DEFAULT algorithm automatically selects the best hashing algorithm
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    return $hashedPassword;
}

// Function to verify a hashed password
function verifyPassword($password, $hashedPassword) {
    // Use password_verify() function to verify the password against its hash
    // Returns true if the password matches the hash, false otherwise
    $isValid = password_verify($password, $hashedPassword);
    return $isValid;
}

// Example usage:
$password = "user_password";
$hashedPassword = hashPassword($password);
echo "Hashed Password: " . $hashedPassword . "<br>";

// Simulating password verification
$userInputPassword = "user_password"; // Assume this is the password entered by the user during login
if (verifyPassword($userInputPassword, $hashedPassword)) {
    echo "Password is valid";
} else {
    echo "Password is invalid";
}
?>
