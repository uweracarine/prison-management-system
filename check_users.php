<?php
// Script to check existing users in database
// Access: http://localhost/BEST/check_users.php

require_once 'config/database.php';

$conn = getDBConnection();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Check Users - Prison Management System</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; }
        .user-list { margin-top: 20px; }
        .user-item { padding: 15px; margin: 10px 0; background: #f8f9fa; border-left: 4px solid #3498db; border-radius: 5px; }
        .user-item strong { color: #2c3e50; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #2980b9; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 5px; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Database Users Check</h1>";

// Check if database exists
$db_check = $conn->query("SELECT DATABASE()");
if ($db_check) {
    $db_name = $db_check->fetch_array()[0];
    echo "<div class='alert alert-info'><strong>Database:</strong> " . htmlspecialchars($db_name) . "</div>";
    
    // Check if users table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'users'");
    if ($table_check->num_rows > 0) {
        // Get all users
        $result = $conn->query("SELECT id, username, email, role, full_name, is_active, created_at FROM users ORDER BY id");
        
        if ($result->num_rows > 0) {
            echo "<div class='user-list'>";
            echo "<h2>Existing Users (" . $result->num_rows . "):</h2>";
            
            while ($user = $result->fetch_assoc()) {
                echo "<div class='user-item'>";
                echo "<strong>ID:</strong> " . $user['id'] . "<br>";
                echo "<strong>Username:</strong> " . htmlspecialchars($user['username']) . "<br>";
                echo "<strong>Email:</strong> " . htmlspecialchars($user['email']) . "<br>";
                echo "<strong>Full Name:</strong> " . htmlspecialchars($user['full_name']) . "<br>";
                echo "<strong>Role:</strong> " . htmlspecialchars($user['role']) . "<br>";
                echo "<strong>Status:</strong> " . ($user['is_active'] ? 'Active' : 'Inactive') . "<br>";
                echo "<strong>Created:</strong> " . $user['created_at'] . "<br>";
                echo "</div>";
            }
            
            echo "</div>";
            echo "<div class='alert alert-info'>";
            echo "<h3>📝 Login Credentials:</h3>";
            echo "<p><strong>To login, use one of the usernames above with their corresponding password.</strong></p>";
            echo "<p><strong>Default Admin:</strong> username: <code>admin</code> / password: <code>admin123</code></p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning'>";
            echo "<h3>⚠️ No users found in database!</h3>";
            echo "<p>The users table exists but is empty. You need to:</p>";
            echo "<ol>";
            echo "<li>Import the database schema from <code>database/schema.sql</code></li>";
            echo "<li>OR run <a href='add_user_carine.php'>add_user_carine.php</a> to create user 'carine'</li>";
            echo "</ol>";
            echo "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>";
        echo "<h3>⚠️ Users table does not exist!</h3>";
        echo "<p>You need to import the database schema first:</p>";
        echo "<ol>";
        echo "<li>Go to phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Select or create database: <code>prison_management</code></li>";
        echo "<li>Go to Import tab</li>";
        echo "<li>Select file: <code>database/schema.sql</code></li>";
        echo "<li>Click Go</li>";
        echo "</ol>";
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>";
    echo "<h3>⚠️ Cannot connect to database!</h3>";
    echo "<p>Please check your database configuration in <code>config/database.php</code></p>";
    echo "</div>";
}

closeDBConnection($conn);

echo "<br><a href='login.php' class='btn'>Go to Login Page</a>";
echo "<a href='add_user_carine.php' class='btn' style='background: #27ae60; margin-left: 10px;'>Create User 'carine'</a>";
echo "</div></body></html>";
?>


