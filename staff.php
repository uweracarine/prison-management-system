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
    <title>Staff - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Staff Management</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addStaffModal')">+ Add New Staff</button>
                <?php endif; ?>
            </div>
            
            <div class="filters-bar">
                <input type="text" id="searchStaff" placeholder="Search staff..." class="search-input">
                <select id="filterDepartment" class="filter-select">
                    <option value="">All Departments</option>
                    <option value="Security">Security</option>
                    <option value="Administration">Administration</option>
                    <option value="Medical">Medical</option>
                    <option value="Education">Education</option>
                </select>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Staff ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Hire Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addStaffModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Staff</h2>
                <span class="close" onclick="closeModal('addStaffModal')">&times;</span>
            </div>
            <form id="staffForm" onsubmit="saveStaff(event)">
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
                            <label>Email</label>
                            <input type="email" name="email">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Position *</label>
                            <input type="text" name="position" required>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department">
                                <option value="">Select Department</option>
                                <option value="Security">Security</option>
                                <option value="Administration">Administration</option>
                                <option value="Medical">Medical</option>
                                <option value="Education">Education</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Hire Date *</label>
                            <input type="date" name="hire_date" required>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="number" name="salary" step="0.01">
                        </div>
                    </div>
                    
                    <input type="hidden" name="staff_id" id="editStaffId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addStaffModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Staff</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        const isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/staff.js"></script>
</body>
</html>

