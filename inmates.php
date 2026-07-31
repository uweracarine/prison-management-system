<?php
require_once 'config/config.php';
requireLogin();
checkSessionTimeout();

$message = '';
if (!isAdmin()) {
    die("You do not have permission to add inmates.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $inmate_id = 'INM-' . strtoupper(uniqid());
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $nationality = sanitizeInput($_POST['nationality'] ?? '');
    $crime_type = sanitizeInput($_POST['crime_type']);
    $sentence_start = $_POST['sentence_start'];
    $sentence_end = !empty($_POST['sentence_end']) ? $_POST['sentence_end'] : null;
    $sentence_duration = intval($_POST['sentence_duration_months'] ?? 0);
    $cell_id = (!empty($_POST['cell_id']) && $_POST['cell_id'] !== '0') ? intval($_POST['cell_id']) : null;
    $status = 'active';
    $photo = null;

    try {
        $stmt = $conn->prepare("
            INSERT INTO inmates 
            (inmate_id, first_name, last_name, date_of_birth, gender, nationality, 
             crime_type, sentence_start, sentence_end, sentence_duration_months, 
             cell_id, status, photo, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "sssssssssiiss",
            $inmate_id,
            $first_name,
            $last_name,
            $date_of_birth,
            $gender,
            $nationality,
            $crime_type,
            $sentence_start,
            $sentence_end,
            $sentence_duration,
            $cell_id,
            $status,
            $photo
        );

        $stmt->execute();

        // Update cell occupancy if assigned
        if ($cell_id !== null) {
            $update = $conn->prepare("
                UPDATE cells 
                SET current_occupancy = current_occupancy + 1,
                    status = 'occupied'
                WHERE id = ?
            ");
            $update->bind_param("i", $cell_id);
            $update->execute();
            $update->close();
        }

        $_SESSION['success'] = "Inmate added successfully!";
        header("Location: inmates.php");
        exit;
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Inmate - Prison Management System</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 700px;
        margin: 50px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #555;
    }
    input, select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: border 0.2s;
    }
    input:focus, select:focus {
        border-color: #007bff;
        outline: none;
    }
    button {
        padding: 12px 20px;
        background-color: #007bff;
        border: none;
        color: #fff;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.2s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .message {
        margin-top: 20px;
        font-weight: bold;
        padding: 10px 15px;
        border-radius: 5px;
    }
    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .back-button {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #007bff;
        font-weight: 600;
    }
    .back-button:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Add New Inmate</h1>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message,'Error')===0 ? 'error':'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>First Name *</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="form-group">
            <label>Last Name *</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="form-group">
            <label>Date of Birth *</label>
            <input type="date" name="date_of_birth" required>
        </div>
        <div class="form-group">
            <label>Gender *</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nationality</label>
            <input type="text" name="nationality">
        </div>
        <div class="form-group">
            <label>Crime Type *</label>
            <input type="text" name="crime_type" required>
        </div>
        <div class="form-group">
            <label>Sentence Start *</label>
            <input type="date" name="sentence_start" required>
        </div>
        <div class="form-group">
            <label>Sentence End</label>
            <input type="date" name="sentence_end">
        </div>
        <div class="form-group">
            <label>Sentence Duration (Months)</label>
            <input type="number" name="sentence_duration_months" min="1">
        </div>
        <div class="form-group">
            <label>Cell (Optional)</label>
            <select name="cell_id">
                <option value="">No Cell Assigned</option>
                <?php
                $conn = getDBConnection();
                $result = $conn->query("SELECT id, block_name, cell_number FROM cells");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['block_name']} - {$row['cell_number']}</option>";
                }
                closeDBConnection($conn);
                ?>
            </select>
        </div>
        <button type="submit">Add Inmate</button>
    </form>

    <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>
</div>
</body>
</html>
