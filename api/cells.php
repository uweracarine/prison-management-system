<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $available = isset($_GET['available']) && $_GET['available'] === 'true';
        
        $query = "SELECT * FROM cells WHERE 1=1";
        if ($available) {
            $query .= " AND current_occupancy < capacity AND status != 'maintenance'";
        }
        $query .= " ORDER BY block_name, cell_number";
        
        $result = $conn->query($query);
        
        if (!$result) {
            echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
            closeDBConnection($conn);
            exit();
        }
        
        $cells = [];
        while ($row = $result->fetch_assoc()) {
            $cells[] = $row;
        }
        echo json_encode($cells);
        break;
        
    case 'POST':
        // Only admins can create cells
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create cells.']);
            break;
        }
        
        $cell_number = sanitizeInput($_POST['cell_number']);
        $block_name = sanitizeInput($_POST['block_name']);
        $capacity = intval($_POST['capacity']);
        $cell_type = $_POST['cell_type'];
        
        $stmt = $conn->prepare("INSERT INTO cells (cell_number, block_name, capacity, cell_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $cell_number, $block_name, $capacity, $cell_type);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cell added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

