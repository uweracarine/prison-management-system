<header class="main-header">
    <div class="header-content">
        <div class="header-left">
            <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                <h1 class="logo">🏛️ Prison Management System</h1>
            </a>
        </div>
        <div class="header-right">
            <div class="user-menu">
                <a href="index.php" class="btn btn-sm" style="margin-right: 10px; background: #95a5a6; color: white;">Home</a>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </div>
</header>

