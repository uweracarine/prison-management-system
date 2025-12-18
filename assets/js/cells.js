// Cells Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadCells();
});

function loadCells() {
    const tbody = document.getElementById('cellsTableBody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center"><div class="loading">Loading cells...</div></td></tr>';
    }
    
    fetch(API_BASE + 'cells.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('cellsTableBody');
            if (!tbody) return;
            
            if (!Array.isArray(data)) {
                console.error('Invalid data format:', data);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center" style="color: red;">Error loading cells. Please refresh the page.</td></tr>';
                return;
            }
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No cells found. Add a cell to get started.</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(cell => `
                <tr>
                    <td>${cell.cell_number || 'N/A'}</td>
                    <td>${cell.block_name || 'N/A'}</td>
                    <td>${cell.cell_type || 'N/A'}</td>
                    <td>${cell.capacity || 0}</td>
                    <td>${cell.current_occupancy || 0} / ${cell.capacity || 0}</td>
                    <td><span class="status-badge status-${cell.status || 'available'}">${cell.status || 'available'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewCell(${cell.id})">View</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading cells:', error);
            const tbody = document.getElementById('cellsTableBody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center" style="color: red;">Error loading cells. Please refresh the page.</td></tr>';
            }
            showAlert('Error loading cells. Please refresh the page.', 'error');
        });
}

function saveCell(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'cells.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            closeModal('addCellModal');
            form.reset();
            setTimeout(() => {
                loadCells();
            }, 300);
        } else {
            showAlert(result.message || 'Error saving cell', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving cell:', error);
        showAlert(error.message || 'Error saving cell. Please try again.', 'error');
    });
}

