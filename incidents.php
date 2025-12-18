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
    <title>Incidents - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Incident Management</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addIncidentModal')">+ Report Incident</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Incident ID</th>
                                    <th>Date & Time</th>
                                    <th>Type</th>
                                    <th>Severity</th>
                                    <th>Inmate</th>
                                    <th>Staff</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="incidentsTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addIncidentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Report Incident</h2>
                <span class="close" onclick="closeModal('addIncidentModal')">&times;</span>
            </div>
            <form id="incidentForm" onsubmit="saveIncident(event)">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Inmate (Optional)</label>
                            <select name="inmate_id" id="incidentInmateSelect">
                                <option value="">Select Inmate (if applicable)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Staff (Optional)</label>
                            <select name="staff_id" id="incidentStaffSelect">
                                <option value="">Select Staff (if applicable)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Incident Type *</label>
                            <select name="incident_type" required>
                                <option value="">Select Type</option>
                                <option value="violence">Violence</option>
                                <option value="escape_attempt">Escape Attempt</option>
                                <option value="contraband">Contraband</option>
                                <option value="discipline">Discipline</option>
                                <option value="medical">Medical</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Severity *</label>
                            <select name="severity" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Incident Date *</label>
                            <input type="date" name="incident_date" required>
                        </div>
                        <div class="form-group">
                            <label>Incident Time *</label>
                            <input type="time" name="incident_time" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addIncidentModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Report Incident</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        const isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/incidents.js"></script>
</body>
</html>


