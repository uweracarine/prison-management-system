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
    <title>Visitors - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Visitor Management</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addVisitorModal')">+ Add New Visitor</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Visitor ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Relationship</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="visitorsTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addVisitorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Visitor</h2>
                <span class="close" onclick="closeModal('addVisitorModal')">&times;</span>
            </div>
            <form id="visitorForm" onsubmit="saveVisitor(event)">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name *</label>
                            <input type="text" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name *</label>
                            <input type="text" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Relationship</label>
                        <input type="text" name="relationship" placeholder="e.g., Family, Friend, Lawyer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addVisitorModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Visitor</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="assets/js/main.js"></script>
    <script src="assets/js/visitors.js"></script>
</body>
</html>

