<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $query = "SELECT m.*, i.inmate_id, i.first_name as inmate_first, i.last_name as inmate_last
                  FROM medical_records m
                  LEFT JOIN inmates i ON m.inmate_id = i.id
                  ORDER BY m.record_date DESC, m.created_at DESC";
        
        $result = $conn->query($query);
        
        if (!$result) {
            echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
            closeDBConnection($conn);
            exit();
        }
        
        $records = [];
        while ($row = $result->fetch_assoc()) {
            $row['inmate_name'] = $row['inmate_first'] ? $row['inmate_first'] . ' ' . $row['inmate_last'] : 'N/A';
            $records[] = $row;
        }
        echo json_encode($records);
        break;
        
    case 'POST':
        // Only admins can create medical records
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create medical records.']);
            break;
        }
        
        $inmate_id = intval($_POST['inmate_id']);
        $record_date = $_POST['record_date'];
        $condition = sanitizeInput($_POST['condition'] ?? '');
        $diagnosis = sanitizeInput($_POST['diagnosis'] ?? '');
        $treatment = sanitizeInput($_POST['treatment'] ?? '');
        $medication = sanitizeInput($_POST['medication'] ?? '');
        $doctor_name = sanitizeInput($_POST['doctor_name'] ?? '');
        $notes = sanitizeInput($_POST['notes'] ?? '');
        
        $stmt = $conn->prepare("INSERT INTO medical_records (inmate_id, record_date, `condition`, diagnosis, treatment, medication, doctor_name, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $inmate_id, $record_date, $condition, $diagnosis, $treatment, $medication, $doctor_name, $notes);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Medical record added successfully! The record will appear in the list below.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

