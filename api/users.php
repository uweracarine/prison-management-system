<?php
require_once '../config/config.php';
requireAdmin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT id, username, email, full_name, role, is_active, last_login, created_at FROM users ORDER BY created_at DESC");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
        break;
        
    case 'POST':
        require_once '../includes/auth.php';
        
        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $full_name = sanitizeInput($_POST['full_name']);
        $role = $_POST['role'];
        
        $result = register($username, $email, $password, $full_name, $role);
        echo json_encode($result);
        break;
        
    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $username = sanitizeInput($data['username']);
        $email = sanitizeInput($data['email']);
        $full_name = sanitizeInput($data['full_name']);
        $role = $data['role'];
        $is_active = isset($data['is_active']) ? intval($data['is_active']) : 1;
        
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, role = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $username, $email, $full_name, $role, $is_active, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
        
    case 'DELETE':
        $id = intval($_GET['id']);
        
        // Prevent deleting own account
        if ($id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
            break;
        }
        
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

closeDBConnection($conn);
?>

