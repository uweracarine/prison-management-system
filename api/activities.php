<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $query = "SELECT a.*, s.first_name as instructor_first, s.last_name as instructor_last
                  FROM activities a
                  LEFT JOIN staff s ON a.instructor_id = s.id
                  ORDER BY a.created_at DESC";
        
        $result = $conn->query($query);
        $activities = [];
        while ($row = $result->fetch_assoc()) {
            $row['instructor_name'] = $row['instructor_first'] ? $row['instructor_first'] . ' ' . $row['instructor_last'] : 'N/A';
            $activities[] = $row;
        }
        echo json_encode($activities);
        break;
        
    case 'POST':
        // Only admins can create activities
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create activities.']);
            break;
        }
        
        $activity_name = sanitizeInput($_POST['activity_name']);
        $description = sanitizeInput($_POST['description'] ?? '');
        $activity_type = $_POST['activity_type'];
        $schedule_time = $_POST['schedule_time'] ?? null;
        $schedule_days = sanitizeInput($_POST['schedule_days'] ?? '');
        $capacity = !empty($_POST['capacity']) ? intval($_POST['capacity']) : null;
        $instructor_id = !empty($_POST['instructor_id']) ? intval($_POST['instructor_id']) : null;
        
        $stmt = $conn->prepare("INSERT INTO activities (activity_name, description, activity_type, schedule_time, schedule_days, capacity, instructor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $activity_name, $description, $activity_type, $schedule_time, $schedule_days, $capacity, $instructor_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Activity added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

