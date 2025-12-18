// Inmates Management JavaScript

let currentEditId = null;

// Load inmates on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load inmates immediately
    if (document.getElementById('inmatesTableBody')) {
        loadInmates();
    }
    // Load cells for dropdown when page loads
    if (document.getElementById('cellSelect')) {
        loadCellsForInmate();
    }
});

// Load all inmates
function loadInmates() {
    const status = document.getElementById('filterStatus')?.value || '';
    const search = document.getElementById('searchInmates')?.value || '';
    
    const tbody = document.getElementById('inmatesTableBody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center"><div class="loading"><div class="spinner"></div> Loading inmates...</div></td></tr>';
    }
    
    let url = API_BASE + 'inmates.php';
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    if (params.toString()) url += '?' + params.toString();
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('inmatesTableBody');
            if (!tbody) return;
            
            if (!Array.isArray(data)) {
                console.error('Invalid data format:', data);
                tbody.innerHTML = '<tr><td colspan="8" class="text-center" style="color: red;">Error loading inmates. Please refresh the page.</td></tr>';
                return;
            }
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No inmates found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(inmate => `
                <tr>
                    <td>${inmate.inmate_id}</td>
                    <td>${inmate.first_name} ${inmate.last_name}</td>
                    <td>${formatDate(inmate.date_of_birth)}</td>
                    <td>${inmate.crime_type}</td>
                    <td>${formatDate(inmate.sentence_start)} - ${formatDate(inmate.sentence_end) || 'Life'}</td>
                    <td>${inmate.cell_number ? inmate.cell_number + ' (' + inmate.block_name + ')' : 'N/A'}</td>
                    <td><span class="status-badge status-${inmate.status}">${inmate.status}</span></td>
                    <td>
                        ${isAdmin ? `
                            <button class="btn btn-sm btn-primary" onclick="editInmate(${inmate.id})" title="Edit Inmate">✏️ Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteInmate(${inmate.id})" title="Delete Inmate">🗑️ Delete</button>
                        ` : `
                            <button class="btn btn-sm btn-primary" onclick="viewInmate(${inmate.id})" title="View Details">👁️ View</button>
                            <span style="color: #999; font-size: 12px;">Read Only</span>
                        `}
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading inmates:', error);
            showAlert('Error loading inmates', 'error');
        });
}

// Load cells for dropdown (for inmates form)
function loadCellsForInmate() {
    const select = document.getElementById('cellSelect');
    if (!select) return;
    
    // Show loading state
    select.innerHTML = '<option value="">Loading cells...</option>';
    
    // Load all cells, not just available ones
    fetch(API_BASE + 'cells.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load cells');
            }
            return response.json();
        })
        .then(data => {
            if (!Array.isArray(data)) {
                console.error('Invalid cells data format:', data);
                select.innerHTML = '<option value="">Error loading cells. Cell assignment is optional.</option>';
                return;
            }
            
            if (data.length === 0) {
                select.innerHTML = '<option value="">No cells available. You can add inmate without a cell.</option>';
                return;
            }
            
            // Show all cells, but mark available ones
            select.innerHTML = '<option value="">No Cell Assigned (Optional)</option>' + 
                data.map(cell => {
                    const currentOccupancy = cell.current_occupancy || 0;
                    const capacity = cell.capacity || 0;
                    const isAvailable = currentOccupancy < capacity && cell.status !== 'maintenance';
                    const availableText = isAvailable ? '✓ Available' : '✗ Full';
                    return `<option value="${cell.id}" ${!isAvailable ? 'disabled' : ''}>${cell.cell_number || 'N/A'} - ${cell.block_name || 'N/A'} (${currentOccupancy}/${capacity}) - ${availableText}</option>`;
                }).join('');
        })
        .catch(error => {
            console.error('Error loading cells:', error);
            const select = document.getElementById('cellSelect');
            if (select) {
                select.innerHTML = '<option value="">Error loading cells. You can still add inmate without a cell.</option>';
            }
            // Don't show alert for cells error - it's not critical
        });
}

// Alias for backward compatibility - redirects to loadCellsForInmate
function loadCells() {
    loadCellsForInmate();
}

// Save inmate (create or update)
function saveInmate(event) {
    event.preventDefault();
    
    const form = event.target;
    const isEdit = currentEditId !== null;
    
    if (isEdit) {
        // For PUT request, use JSON
        const data = {
            id: currentEditId,
            first_name: form.querySelector('[name="first_name"]').value,
            last_name: form.querySelector('[name="last_name"]').value,
            date_of_birth: form.querySelector('[name="date_of_birth"]').value,
            gender: form.querySelector('[name="gender"]').value,
            nationality: form.querySelector('[name="nationality"]').value,
            crime_type: form.querySelector('[name="crime_type"]').value,
            sentence_start: form.querySelector('[name="sentence_start"]').value,
            sentence_end: form.querySelector('[name="sentence_end"]').value,
            sentence_duration_months: form.querySelector('[name="sentence_duration_months"]').value,
            cell_id: form.querySelector('[name="cell_id"]').value,
            status: 'active'
        };
        
        fetch(API_BASE + 'inmates.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
    } else {
        // For POST request, use FormData
        const formData = new FormData(form);
        
        fetch(API_BASE + 'inmates.php', {
            method: 'POST',
            body: formData
        })
    }
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
                closeModal('addInmateModal');
                // Clear form first
                form.reset();
                currentEditId = null;
                // Reload inmates list immediately to show the new/updated inmate
                setTimeout(() => {
                    loadInmates();
                    loadCellsForInmate(); // Reload cells in case occupancy changed
                }, 200);
            } else {
                showAlert(result.message || 'Error saving inmate', 'error');
            }
        })
        .catch(error => {
            console.error('Error saving inmate:', error);
            showAlert(error.message || 'Error saving inmate. Please try again.', 'error');
        });
}

// Edit inmate
function editInmate(id) {
    currentEditId = id;
    
    fetch(API_BASE + 'inmates.php?id=' + id)
        .then(response => response.json())
        .then(inmate => {
            const form = document.getElementById('inmateForm');
            if (!form) return;
            
            form.querySelector('[name="first_name"]').value = inmate.first_name;
            form.querySelector('[name="last_name"]').value = inmate.last_name;
            form.querySelector('[name="date_of_birth"]').value = inmate.date_of_birth;
            form.querySelector('[name="gender"]').value = inmate.gender;
            form.querySelector('[name="nationality"]').value = inmate.nationality || '';
            form.querySelector('[name="crime_type"]').value = inmate.crime_type;
            form.querySelector('[name="sentence_start"]').value = inmate.sentence_start;
            form.querySelector('[name="sentence_end"]').value = inmate.sentence_end || '';
            form.querySelector('[name="sentence_duration_months"]').value = inmate.sentence_duration_months || '';
            form.querySelector('[name="cell_id"]').value = inmate.cell_id || '';
            
            // Add hidden field for edit
            let hiddenInput = form.querySelector('#editInmateId');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'editInmateId';
                hiddenInput.name = 'inmate_id';
                form.appendChild(hiddenInput);
            }
            hiddenInput.value = id;
            
            openModal('addInmateModal');
        })
        .catch(error => {
            console.error('Error loading inmate:', error);
            showAlert('Error loading inmate details', 'error');
        });
}

// Delete inmate
function deleteInmate(id) {
    if (!confirm('Are you sure you want to delete this inmate?')) {
        return;
    }
    
    fetch(API_BASE + 'inmates.php?id=' + id, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            loadInmates();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting inmate:', error);
        showAlert('Error deleting inmate', 'error');
    });
}

// View inmate (for non-admin users)
function viewInmate(id) {
    // Redirect to detail page or show modal
    window.location.href = 'inmate-details.php?id=' + id;
}

// Filter and search event listeners
document.getElementById('filterStatus')?.addEventListener('change', loadInmates);
document.getElementById('searchInmates')?.addEventListener('input', function() {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(loadInmates, 500);
});

