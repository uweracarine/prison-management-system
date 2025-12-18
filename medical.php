<?php
require_once 'config/config.php';
requireLogin();
checkSessionTimeout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Medical Records</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addMedicalModal')">+ Add Medical Record</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Inmate</th>
                                    <th>Condition</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                    <th>Doctor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="medicalTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addMedicalModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Medical Record</h2>
                <span class="close" onclick="closeModal('addMedicalModal')">&times;</span>
            </div>
            <form id="medicalForm" onsubmit="saveMedical(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Inmate *</label>
                        <select name="inmate_id" id="medicalInmateSelect" required>
                            <option value="">Select Inmate</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Record Date *</label>
                            <input type="date" name="record_date" required>
                        </div>
                        <div class="form-group">
                            <label>Condition</label>
                            <input type="text" name="condition">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Diagnosis</label>
                        <textarea name="diagnosis" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Treatment</label>
                        <textarea name="treatment" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Medication</label>
                            <input type="text" name="medication">
                        </div>
                        <div class="form-group">
                            <label>Doctor Name</label>
                            <input type="text" name="doctor_name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addMedicalModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        const isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/medical.js"></script>
</body>
</html>

