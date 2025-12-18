<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM visitors ORDER BY created_at DESC");
        $visitors = [];
        while ($row = $result->fetch_assoc()) {
            $visitors[] = $row;
        }
        echo json_encode($visitors);
        break;
        
    case 'POST':
        // Only admins can create visitors
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create visitors.']);
            break;
        }
        
        $visitor_id = 'VIS-' . strtoupper(uniqid());
        $first_name = sanitizeInput($_POST['first_name']);
        $last_name = sanitizeInput($_POST['last_name']);
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $relationship = sanitizeInput($_POST['relationship'] ?? '');
        
        $stmt = $conn->prepare("INSERT INTO visitors (visitor_id, first_name, last_name, phone, email, relationship) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $visitor_id, $first_name, $last_name, $phone, $email, $relationship);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Visitor added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

