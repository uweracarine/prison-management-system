<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/auth.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $result = login($username, $password);
        if ($result['success']) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
</head>
<body class="login-page">
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <span class="brand-icon">🏛️</span>
                <span class="brand-text">Prison Management System</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="index.php#features" class="nav-link">Features</a></li>
                <li><a href="index.php#about" class="nav-link">About</a></li>
            </ul>
        </div>
    </nav>
    <div class="login-container" style="padding-top: 80px;">
        <div class="login-box">
            <div class="login-header">
                <h1>🔒 Sign In</h1>
                <p>Access your Prison Management System account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register.php" style="color: var(--secondary-color); font-weight: 600;">Create Account</a></p>
                <p style="margin-top: 15px; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 15px;">
                    <strong>Demo Accounts:</strong><br>
                    Admin: <strong>admin</strong> / <strong>admin123</strong><br>
                    <a href="check_users.php" style="color: var(--secondary-color); font-size: 11px;">Check All Users</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

