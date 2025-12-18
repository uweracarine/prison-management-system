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
    <title>Cells - Prison Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>Cell Management</h1>
                <?php if (isAdmin()): ?>
                <button class="btn btn-primary" onclick="openModal('addCellModal')">+ Add New Cell</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Cell Number</th>
                                    <th>Block</th>
                                    <th>Type</th>
                                    <th>Capacity</th>
                                    <th>Occupancy</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="cellsTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php if (isAdmin()): ?>
    <div id="addCellModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Cell</h2>
                <span class="close" onclick="closeModal('addCellModal')">&times;</span>
            </div>
            <form id="cellForm" onsubmit="saveCell(event)">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Cell Number *</label>
                            <input type="text" name="cell_number" required>
                        </div>
                        <div class="form-group">
                            <label>Block Name *</label>
                            <input type="text" name="block_name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Capacity *</label>
                            <input type="number" name="capacity" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Cell Type *</label>
                            <select name="cell_type" required>
                                <option value="">Select Type</option>
                                <option value="single">Single</option>
                                <option value="shared">Shared</option>
                                <option value="isolation">Isolation</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addCellModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Cell</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        const isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cells.js"></script>
</body>
</html>

