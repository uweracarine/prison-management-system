<?php
require_once '../config/config.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $department = $_GET['department'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $query = "SELECT * FROM staff WHERE 1=1";
        $params = [];
        $types = "";
        
        if ($department) {
            $query .= " AND department = ?";
            $params[] = $department;
            $types .= "s";
        }
        
        if ($search) {
            $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR staff_id LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= "sss";
        }
        
        $query .= " ORDER BY hire_date DESC";
        
        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = [];
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }
        echo json_encode($staff);
        break;
        
    case 'POST':
        // Only admins can create staff
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can create staff members.']);
            break;
        }
        
        $staff_id = 'STF-' . strtoupper(uniqid());
        $first_name = sanitizeInput($_POST['first_name']);
        $last_name = sanitizeInput($_POST['last_name']);
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $position = sanitizeInput($_POST['position']);
        $department = sanitizeInput($_POST['department'] ?? '');
        $hire_date = $_POST['hire_date'];
        $salary = !empty($_POST['salary']) ? floatval($_POST['salary']) : null;
        
        $stmt = $conn->prepare("INSERT INTO staff (staff_id, first_name, last_name, email, phone, position, department, hire_date, salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssd", $staff_id, $first_name, $last_name, $email, $phone, $position, $department, $hire_date, $salary);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Staff added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

