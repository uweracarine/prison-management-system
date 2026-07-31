<?php
// Script to add user "carine" with password "123"
// Run this once by accessing: http://localhost/BEST/add_user_carine.php

require_once 'config/database.php';

$conn = getDBConnection();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Create User - Prison Management System</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #050505ff; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #030303ff; margin-bottom: 30px; }
        .success { color: #27ae60; padding: 20px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0; }
        .info { color: #0c5460; padding: 20px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; margin: 20px 0; }
        .error { color: #721c24; padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0; }
        .credentials { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: left; }
        .credentials strong { color: #2c3e50; }
        .btn { display: inline-block; padding: 12px 30px; background: #011b2cff; color: white; text-decoration: none; border-radius: 5px; margin: 10px; }
        .btn:hover { background: #020d14ff; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
    </style>
</head>
<body>
    <div class='container'>";

// Check if user already exists
$username = "carine";
$checkStmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE username = ?");
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<h1>✓ User Already Exists</h1>";
    echo "<div class='info'>";
    echo "<h2>User 'carine' is already in the database!</h2>";
    echo "<div class='credentials'>";
    echo "<p><strong>Username:</strong> " . htmlspecialchars($user['username']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
    echo "<p><strong>Role:</strong> " . htmlspecialchars($user['role']) . "</p>";
    echo "<p><strong>Password:</strong> 123</p>";
    echo "</div>";
    echo "<p>You can now login with these credentials.</p>";
    echo "</div>";
} else {
    // Create password hash for "123"
    $password = "123";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $username = "carine";
    $email = "carine@prison.com";
    $role = "admin";
    $full_name = "Carine";
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $role, $full_name);
    
    if ($stmt->execute()) {
        echo "<h1>✓ User Created Successfully!</h1>";
        echo "<div class='success'>";
        echo "<h2>Login Credentials Created</h2>";
        echo "<div class='credentials'>";
        echo "<p><strong>Username:</strong> carine</p>";
        echo "<p><strong>Password:</strong> 123</p>";
        echo "<p><strong>Email:</strong> carine@prison.com</p>";
        echo "<p><strong>Role:</strong> Admin (Full Access)</p>";
        echo "</div>";
        echo "<p>You can now login with these credentials.</p>";
        echo "</div>";
    } else {
        echo "<h1>✗ Error Creating User</h1>";
        echo "<div class='error'>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($stmt->error) . "</p>";
        echo "<p>Please check your database connection and try again.</p>";
        echo "</div>";
    }
    
    $stmt->close();
}

$checkStmt->close();
closeDBConnection($conn);

echo "<a href='login.php' class='btn'>Go to Login Page</a>";
echo "<a href='check_users.php' class='btn btn-success'>Check All Users</a>";
echo "</div></body></html>";
?>

