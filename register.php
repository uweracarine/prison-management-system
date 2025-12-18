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
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $role = $_POST['role'] ?? 'user';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all required fields';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!in_array($role, ['user', 'admin'])) {
        $error = 'Invalid account type selected';
    } else {
        // Register the user with selected role
        $result = register($username, $email, $password, $full_name, $role);
        
        if ($result['success']) {
            $roleText = ($role === 'admin') ? 'Administrator' : 'Regular User';
            $success = "Account created successfully as {$roleText}! You can now login.";
            // Auto-redirect to login after 2 seconds
            header('refresh:2;url=login.php');
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
    <title>Create Account - Prison Management System</title>
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
                <li><a href="login.php" class="nav-link btn-login">Login</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="login-container" style="padding-top: 80px;">
        <div class="login-box" style="max-width: 500px;">
            <div class="login-header">
                <h1>📝 Create Account</h1>
                <p>Sign up for a new account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                    <p style="margin-top: 10px; font-size: 14px;">Redirecting to login page...</p>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form" id="registerForm">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                           placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           placeholder="Choose a username (min. 3 characters)"
                           minlength="3">
                    <small style="color: #666; font-size: 12px;">Must be at least 3 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           placeholder="Enter your email address">
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter password (min. 6 characters)"
                           minlength="6">
                    <small style="color: #666; font-size: 12px;">Must be at least 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           placeholder="Confirm your password"
                           minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="role">Account Type *</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : 'selected'; ?>>Regular User (View Only)</option>
                        <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Administrator (Full Access)</option>
                    </select>
                    <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                        ⚠️ <strong>Administrator:</strong> Full access to create, edit, delete records, and manage all system data.<br>
                        <strong>Regular User:</strong> View-only access to all information.
                    </small>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>
            
            <div class="login-footer">
                <p>Already have an account? <a href="login.php" style="color: var(--secondary-color);">Sign In</a></p>
                <p style="margin-top: 10px; font-size: 12px; color: #666;">
                    By creating an account, you agree to our terms of service.
                </p>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
        // Password match validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
        
        // Real-time password match indicator
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordInput = document.getElementById('password');
        
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value && this.value.length > 0) {
                this.style.borderColor = '#e74c3c';
            } else {
                this.style.borderColor = '#ddd';
            }
        });
    </script>
</body>
</html>

