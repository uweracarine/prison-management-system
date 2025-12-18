<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <li><a href="dashboard.php" class="nav-link active">
                <span class="nav-icon">📊</span> Dashboard
            </a></li>
            
            <li><a href="inmates.php" class="nav-link">
                <span class="nav-icon">👥</span> Inmates
            </a></li>
            
            <li><a href="staff.php" class="nav-link">
                <span class="nav-icon">👔</span> Staff
            </a></li>
            
            <li><a href="cells.php" class="nav-link">
                <span class="nav-icon">🚪</span> Cells
            </a></li>
            
            <li><a href="visitors.php" class="nav-link">
                <span class="nav-icon">👤</span> Visitors
            </a></li>
            
            <li><a href="visits.php" class="nav-link">
                <span class="nav-icon">📅</span> Visits
            </a></li>
            
            <li><a href="activities.php" class="nav-link">
                <span class="nav-icon">📚</span> Activities
            </a></li>
            
            <li><a href="incidents.php" class="nav-link">
                <span class="nav-icon"></span> Incidents
            </a></li>
            
            <li><a href="medical.php" class="nav-link">
                <span class="nav-icon">🏥</span> Medical Records
            </a></li>
            
            <?php if (isAdmin()): ?>
            <li class="nav-divider">Admin</li>
            <li><a href="users.php" class="nav-link">
                <span class="nav-icon">👤</span> User Management
            </a></li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>

