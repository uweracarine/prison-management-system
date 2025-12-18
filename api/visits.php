<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $upcoming = isset($_GET['upcoming']) && $_GET['upcoming'] === 'true';
        
        $query = "SELECT v.*, i.first_name as inmate_first, i.last_name as inmate_last, 
                  vis.first_name as visitor_first, vis.last_name as visitor_last
                  FROM visits v
                  LEFT JOIN inmates i ON v.inmate_id = i.id
                  LEFT JOIN visitors vis ON v.visitor_id = vis.id
                  WHERE 1=1";
        
        if ($upcoming) {
            $query .= " AND v.visit_date >= CURDATE() AND v.status = 'scheduled' ORDER BY v.visit_date ASC, v.visit_time ASC LIMIT 5";
        } else {
            $query .= " ORDER BY v.visit_date DESC, v.visit_time DESC";
        }
        
        $result = $conn->query($query);
        $visits = [];
        while ($row = $result->fetch_assoc()) {
            $row['inmate_name'] = $row['inmate_first'] . ' ' . $row['inmate_last'];
            $row['visitor_name'] = $row['visitor_first'] . ' ' . $row['visitor_last'];
            $visits[] = $row;
        }
        echo json_encode($visits);
        break;
        
    case 'POST':
        // Only admins can schedule visits
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can schedule visits.']);
            break;
        }
        
        $inmate_id = intval($_POST['inmate_id']);
        $visitor_id = intval($_POST['visitor_id']);
        $visit_date = $_POST['visit_date'];
        $visit_time = $_POST['visit_time'];
        $duration = intval($_POST['duration_minutes'] ?? 30);
        $visit_type = $_POST['visit_type'];
        $notes = sanitizeInput($_POST['notes'] ?? '');
        
        $stmt = $conn->prepare("INSERT INTO visits (inmate_id, visitor_id, visit_date, visit_time, duration_minutes, visit_type, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iississ", $inmate_id, $visitor_id, $visit_date, $visit_time, $duration, $visit_type, $notes);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Visit scheduled successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

