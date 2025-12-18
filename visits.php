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
    <title>Visits - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Visit Management</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addVisitModal')">+ Schedule Visit</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Inmate</th>
                                    <th>Visitor</th>
                                    <th>Type</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="visitsTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addVisitModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Schedule Visit</h2>
                <span class="close" onclick="closeModal('addVisitModal')">&times;</span>
            </div>
            <form id="visitForm" onsubmit="saveVisit(event)">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Inmate *</label>
                            <select name="inmate_id" id="inmateSelect" required>
                                <option value="">Select Inmate</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Visitor *</label>
                            <select name="visitor_id" id="visitorSelect" required>
                                <option value="">Select Visitor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Visit Date *</label>
                            <input type="date" name="visit_date" required>
                        </div>
                        <div class="form-group">
                            <label>Visit Time *</label>
                            <input type="time" name="visit_time" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Visit Type *</label>
                            <select name="visit_type" required>
                                <option value="regular">Regular</option>
                                <option value="legal">Legal</option>
                                <option value="medical">Medical</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Duration (minutes)</label>
                            <input type="number" name="duration_minutes" value="30" min="15" max="120">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addVisitModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Visit</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="assets/js/main.js"></script>
    <script src="assets/js/visits.js"></script>
</body>
</html>

