<?php
require_once 'config/config.php';
requireLogin();
checkSessionTimeout();

require_once 'includes/dashboard.php';
$stats = getDashboardStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_inmates']; ?></h3>
                        <p>Total Inmates</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_staff']; ?></h3>
                        <p>Staff Members</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🚪</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_cells']; ?></h3>
                        <p>Cells</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['scheduled_visits']; ?></h3>
                        <p>Scheduled Visits</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">⚠️</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['active_incidents']; ?></h3>
                        <p>Active Incidents</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['active_activities']; ?></h3>
                        <p>Active Programs</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activities -->
            <div class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h2>Recent Inmates</h2>
                        <a href="inmates.php" class="btn btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Inmate ID</th>
                                        <th>Name</th>
                                        <th>Crime Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="recentInmates">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2>Upcoming Visits</h2>
                        <a href="visits.php" class="btn btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Inmate</th>
                                        <th>Visitor</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="upcomingVisits">
                                    <!-- Loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
        // Load dashboard data
        loadRecentInmates();
        loadUpcomingVisits();
    </script>
</body>
</html>

