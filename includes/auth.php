<?php
// Authentication Functions

function login($username, $password) {
    $conn = getDBConnection();
    
    $username = sanitizeInput($username);
    $stmt = $conn->prepare("SELECT id, username, email, password, role, full_name, is_active FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account is deactivated'];
        }
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['login_time'] = time();
            
            // Update last login
            $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $updateStmt->bind_param("i", $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
            
            $stmt->close();
            closeDBConnection($conn);
            
            return ['success' => true, 'role' => $user['role']];
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    return ['success' => false, 'message' => 'Invalid username or password'];
}

function register($username, $email, $password, $full_name, $role = 'user') {
    $conn = getDBConnection();
    
    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    $full_name = sanitizeInput($full_name);
    
    // Validate username format (alphanumeric and underscore only)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Username can only contain letters, numbers, and underscores'];
    }
    
    // Validate username length
    if (strlen($username) < 3) {
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Username must be at least 3 characters long'];
    }
    
    // Validate password length
    if (strlen($password) < 6) {
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Password must be at least 6 characters long'];
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Please enter a valid email address'];
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if username already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        $checkStmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Username already exists. Please choose a different username.'];
    }
    $checkStmt->close();
    
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        $checkStmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Email address already registered. Please use a different email or login.'];
    }
    $checkStmt->close();
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $role, $full_name);
    
    if ($stmt->execute()) {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => true, 'message' => 'Account created successfully! You can now login.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Registration failed: ' . $error];
    }
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

function checkSessionTimeout() {
    if (isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
            logout();
        } else {
            $_SESSION['login_time'] = time();
        }
    }
}
?>

