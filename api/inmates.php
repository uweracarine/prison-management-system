<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        // Get all inmates or single inmate
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT i.*, c.cell_number, c.block_name FROM inmates i LEFT JOIN cells c ON i.cell_id = c.id WHERE i.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $inmate = $result->fetch_assoc();
            echo json_encode($inmate);
        } else {
            $status = $_GET['status'] ?? '';
            $search = $_GET['search'] ?? '';
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 0;
            
            $query = "SELECT i.*, c.cell_number, c.block_name FROM inmates i LEFT JOIN cells c ON i.cell_id = c.id WHERE 1=1";
            $params = [];
            $types = "";
            
            if ($status) {
                $query .= " AND i.status = ?";
                $params[] = $status;
                $types .= "s";
            }
            
            if ($search) {
                $query .= " AND (i.first_name LIKE ? OR i.last_name LIKE ? OR i.inmate_id LIKE ?)";
                $searchParam = "%$search%";
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
                $types .= "sss";
            }
            
            $query .= " ORDER BY i.created_at DESC";
            if ($limit > 0) {
                $query .= " LIMIT ?";
                $params[] = $limit;
                $types .= "i";
            }
            
            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $inmates = [];
            while ($row = $result->fetch_assoc()) {
                $inmates[] = $row;
            }
            echo json_encode($inmates);
        }
        break;
        
    case 'POST':
        // Only admins can create inmates
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create inmates.']);
            break;
        }
        
        // Create new inmate
        $inmate_id = 'INM-' . strtoupper(uniqid());
        $first_name = sanitizeInput($_POST['first_name']);
        $last_name = sanitizeInput($_POST['last_name']);
        $date_of_birth = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $nationality = sanitizeInput($_POST['nationality'] ?? '');
        $crime_type = sanitizeInput($_POST['crime_type']);
        $sentence_start = $_POST['sentence_start'];
        $sentence_end = $_POST['sentence_end'] ?? null;
        $sentence_duration = intval($_POST['sentence_duration_months'] ?? 0);
        $cell_id = !empty($_POST['cell_id']) && $_POST['cell_id'] !== '' && $_POST['cell_id'] !== '0' ? intval($_POST['cell_id']) : null;
        
        // Prepare statement - handle NULL cell_id properly
        if ($cell_id === null) {
            $stmt = $conn->prepare("INSERT INTO inmates (inmate_id, first_name, last_name, date_of_birth, gender, nationality, crime_type, sentence_start, sentence_end, sentence_duration_months, cell_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)");
            $stmt->bind_param("sssssssssi", $inmate_id, $first_name, $last_name, $date_of_birth, $gender, $nationality, $crime_type, $sentence_start, $sentence_end, $sentence_duration);
        } else {
            $stmt = $conn->prepare("INSERT INTO inmates (inmate_id, first_name, last_name, date_of_birth, gender, nationality, crime_type, sentence_start, sentence_end, sentence_duration_months, cell_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssii", $inmate_id, $first_name, $last_name, $date_of_birth, $gender, $nationality, $crime_type, $sentence_start, $sentence_end, $sentence_duration, $cell_id);
        }
        
        if ($stmt->execute()) {
            // Update cell occupancy
            if ($cell_id) {
                $updateStmt = $conn->prepare("UPDATE cells SET current_occupancy = current_occupancy + 1, status = 'occupied' WHERE id = ?");
                $updateStmt->bind_param("i", $cell_id);
                $updateStmt->execute();
                $updateStmt->close();
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Inmate added successfully! The inmate will appear in the list below.',
                'inmate_id' => $conn->insert_id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
        
    case 'PUT':
        // Only admins can update inmates
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can update inmates.']);
            break;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id = intval($data['id']);
        $first_name = sanitizeInput($data['first_name']);
        $last_name = sanitizeInput($data['last_name']);
        $date_of_birth = $data['date_of_birth'];
        $gender = $data['gender'];
        $nationality = sanitizeInput($data['nationality'] ?? '');
        $crime_type = sanitizeInput($data['crime_type']);
        $sentence_start = $data['sentence_start'];
        $sentence_end = $data['sentence_end'] ?? null;
        $sentence_duration = intval($data['sentence_duration_months'] ?? 0);
        $cell_id = !empty($data['cell_id']) ? intval($data['cell_id']) : null;
        $status = $data['status'] ?? 'active';
        
        // Get old cell_id
        $oldStmt = $conn->prepare("SELECT cell_id FROM inmates WHERE id = ?");
        $oldStmt->bind_param("i", $id);
        $oldStmt->execute();
        $oldResult = $oldStmt->get_result();
        $oldInmate = $oldResult->fetch_assoc();
        $oldCellId = $oldInmate['cell_id'];
        $oldStmt->close();
        
        $stmt = $conn->prepare("UPDATE inmates SET first_name = ?, last_name = ?, date_of_birth = ?, gender = ?, nationality = ?, crime_type = ?, sentence_start = ?, sentence_end = ?, sentence_duration_months = ?, cell_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssssssssiss", $first_name, $last_name, $date_of_birth, $gender, $nationality, $crime_type, $sentence_start, $sentence_end, $sentence_duration, $cell_id, $status, $id);
        
        if ($stmt->execute()) {
            // Update cell occupancy
            if ($oldCellId != $cell_id) {
                if ($oldCellId) {
                    $updateStmt = $conn->prepare("UPDATE cells SET current_occupancy = GREATEST(0, current_occupancy - 1) WHERE id = ?");
                    $updateStmt->bind_param("i", $oldCellId);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
                if ($cell_id) {
                    $updateStmt = $conn->prepare("UPDATE cells SET current_occupancy = current_occupancy + 1, status = 'occupied' WHERE id = ?");
                    $updateStmt->bind_param("i", $cell_id);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Inmate updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
        
    case 'DELETE':
        // Only admins can delete inmates
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can delete inmates.']);
            break;
        }
        
        $id = intval($_GET['id']);
        
        // Get cell_id before deletion
        $cellStmt = $conn->prepare("SELECT cell_id FROM inmates WHERE id = ?");
        $cellStmt->bind_param("i", $id);
        $cellStmt->execute();
        $cellResult = $cellStmt->get_result();
        $inmate = $cellResult->fetch_assoc();
        $cell_id = $inmate['cell_id'];
        $cellStmt->close();
        
        $stmt = $conn->prepare("DELETE FROM inmates WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Update cell occupancy
            if ($cell_id) {
                $updateStmt = $conn->prepare("UPDATE cells SET current_occupancy = GREATEST(0, current_occupancy - 1) WHERE id = ?");
                $updateStmt->bind_param("i", $cell_id);
                $updateStmt->execute();
                $updateStmt->close();
            }
            
            echo json_encode(['success' => true, 'message' => 'Inmate deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

