<?php
// Dashboard Functions

function getDashboardStats() {
    $conn = getDBConnection();
    $stats = [];
    
    // Total inmates
    $result = $conn->query("SELECT COUNT(*) as count FROM inmates WHERE status = 'active'");
    $stats['total_inmates'] = $result->fetch_assoc()['count'];
    
    // Total staff
    $result = $conn->query("SELECT COUNT(*) as count FROM staff WHERE status = 'active'");
    $stats['total_staff'] = $result->fetch_assoc()['count'];
    
    // Total cells
    $result = $conn->query("SELECT COUNT(*) as count FROM cells");
    $stats['total_cells'] = $result->fetch_assoc()['count'];
    
    // Scheduled visits (next 7 days)
    $result = $conn->query("SELECT COUNT(*) as count FROM visits WHERE visit_date >= CURDATE() AND visit_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status = 'scheduled'");
    $stats['scheduled_visits'] = $result->fetch_assoc()['count'];
    
    // Active incidents
    $result = $conn->query("SELECT COUNT(*) as count FROM incidents WHERE status IN ('reported', 'under_investigation')");
    $stats['active_incidents'] = $result->fetch_assoc()['count'];
    
    // Active activities
    $result = $conn->query("SELECT COUNT(*) as count FROM activities WHERE status = 'active'");
    $stats['active_activities'] = $result->fetch_assoc()['count'];
    
    closeDBConnection($conn);
    return $stats;
}
?>

