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
    <title>Activities - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Activity Programs</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addActivityModal')">+ Add New Activity</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Activity Name</th>
                                    <th>Type</th>
                                    <th>Schedule</th>
                                    <th>Instructor</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="activitiesTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addActivityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Activity</h2>
                <span class="close" onclick="closeModal('addActivityModal')">&times;</span>
            </div>
            <form id="activityForm" onsubmit="saveActivity(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Activity Name *</label>
                        <input type="text" name="activity_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Activity Type *</label>
                            <select name="activity_type" required>
                                <option value="">Select Type</option>
                                <option value="education">Education</option>
                                <option value="recreation">Recreation</option>
                                <option value="work">Work</option>
                                <option value="therapy">Therapy</option>
                                <option value="religious">Religious</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Instructor</label>
                            <select name="instructor_id" id="instructorSelect">
                                <option value="">Select Instructor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Schedule Time</label>
                            <input type="time" name="schedule_time">
                        </div>
                        <div class="form-group">
                            <label>Schedule Days</label>
                            <input type="text" name="schedule_days" placeholder="e.g., Mon, Wed, Fri">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addActivityModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Activity</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="assets/js/main.js"></script>
    <script src="assets/js/activities.js"></script>
</body>
</html>

