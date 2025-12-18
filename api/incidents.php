<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $query = "SELECT i.*, 
                  inm.first_name as inmate_first, inm.last_name as inmate_last, inm.inmate_id as inmate_inmate_id,
                  s.first_name as staff_first, s.last_name as staff_last, s.staff_id as staff_staff_id
                  FROM incidents i
                  LEFT JOIN inmates inm ON i.inmate_id = inm.id
                  LEFT JOIN staff s ON i.staff_id = s.id
                  ORDER BY i.incident_date DESC";
        
        $result = $conn->query($query);
        
        if (!$result) {
            echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
            closeDBConnection($conn);
            exit();
        }
        
        $incidents = [];
        while ($row = $result->fetch_assoc()) {
            $row['inmate_name'] = $row['inmate_first'] ? $row['inmate_first'] . ' ' . $row['inmate_last'] . ' (' . $row['inmate_inmate_id'] . ')' : 'N/A';
            $row['staff_name'] = $row['staff_first'] ? $row['staff_first'] . ' ' . $row['staff_last'] . ' (' . $row['staff_staff_id'] . ')' : 'N/A';
            $incidents[] = $row;
        }
        echo json_encode($incidents);
        break;
        
    case 'POST':
        // Only admins can create incidents
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create incidents.']);
            break;
        }
        
        $incident_id = 'INC-' . strtoupper(uniqid());
        $inmate_id = !empty($_POST['inmate_id']) && $_POST['inmate_id'] !== '' ? intval($_POST['inmate_id']) : null;
        $staff_id = !empty($_POST['staff_id']) && $_POST['staff_id'] !== '' ? intval($_POST['staff_id']) : null;
        $incident_type = sanitizeInput($_POST['incident_type']);
        $incident_date = $_POST['incident_date'];
        $incident_time = $_POST['incident_time'];
        $incident_datetime = $incident_date . ' ' . $incident_time . ':00';
        $description = sanitizeInput($_POST['description']);
        $severity = sanitizeInput($_POST['severity']);
        $reported_by = $_SESSION['user_id'];
        
        // Handle NULL values properly
        // Parameters: incident_id(s), inmate_id(i/null), staff_id(i/null), incident_type(s), incident_date(s), description(s), severity(s), reported_by(i)
        if ($inmate_id === null && $staff_id === null) {
            // 6 params: incident_id, incident_type, incident_date, description, severity, reported_by
            $stmt = $conn->prepare("INSERT INTO incidents (incident_id, inmate_id, staff_id, incident_type, incident_date, description, severity, reported_by) VALUES (?, NULL, NULL, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $incident_id, $incident_type, $incident_datetime, $description, $severity, $reported_by);
        } elseif ($inmate_id === null) {
            // 7 params: incident_id, staff_id, incident_type, incident_date, description, severity, reported_by
            $stmt = $conn->prepare("INSERT INTO incidents (incident_id, inmate_id, staff_id, incident_type, incident_date, description, severity, reported_by) VALUES (?, NULL, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sississi", $incident_id, $staff_id, $incident_type, $incident_datetime, $description, $severity, $reported_by);
        } elseif ($staff_id === null) {
            // 7 params: incident_id, inmate_id, incident_type, incident_date, description, severity, reported_by
            $stmt = $conn->prepare("INSERT INTO incidents (incident_id, inmate_id, staff_id, incident_type, incident_date, description, severity, reported_by) VALUES (?, ?, NULL, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sississi", $incident_id, $inmate_id, $incident_type, $incident_datetime, $description, $severity, $reported_by);
        } else {
            // 8 params: incident_id, inmate_id, staff_id, incident_type, incident_date, description, severity, reported_by
            $stmt = $conn->prepare("INSERT INTO incidents (incident_id, inmate_id, staff_id, incident_type, incident_date, description, severity, reported_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siississi", $incident_id, $inmate_id, $staff_id, $incident_type, $incident_datetime, $description, $severity, $reported_by);
        }
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Incident reported successfully! The incident will appear in the list below.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

