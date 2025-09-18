<?php
require 'Dbc.php'; // include connection to taskapp database

function registerUser($fullname, $email, $password) {
    global $conn;

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in taskapp.users
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        $checkQuery->close();
        return "Email already registered in TaskApp.";
    }

    $checkQuery->close();

    // Insert new user into users table
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if ($stmt->execute()) {
        $stmt->close();
        return "User registered successfully in TaskApp.";
    } else {
        $stmt->close();
        return "Error saving user in TaskApp: " . $conn->error;
    }
}
?>