// Staff Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadStaff();
});

function loadStaff() {
    const department = document.getElementById('filterDepartment')?.value || '';
    const search = document.getElementById('searchStaff')?.value || '';
    
    let url = API_BASE + 'staff.php';
    const params = new URLSearchParams();
    if (department) params.append('department', department);
    if (search) params.append('search', search);
    if (params.toString()) url += '?' + params.toString();
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('staffTableBody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No staff found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(staff => `
                <tr>
                    <td>${staff.staff_id}</td>
                    <td>${staff.first_name} ${staff.last_name}</td>
                    <td>${staff.position}</td>
                    <td>${staff.department || 'N/A'}</td>
                    <td>${formatDate(staff.hire_date)}</td>
                    <td><span class="status-badge status-${staff.status}">${staff.status}</span></td>
                    <td>
                        ${isAdmin ? `
                            <button class="btn btn-sm btn-primary" onclick="editStaff(${staff.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteStaff(${staff.id})">Delete</button>
                        ` : `
                            <button class="btn btn-sm btn-primary" onclick="viewStaff(${staff.id})">View</button>
                        `}
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading staff:', error);
            showAlert('Error loading staff', 'error');
        });
}

function saveStaff(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'staff.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            closeModal('addStaffModal');
            loadStaff();
            form.reset();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving staff:', error);
        showAlert('Error saving staff', 'error');
    });
}

document.getElementById('filterDepartment')?.addEventListener('change', loadStaff);
document.getElementById('searchStaff')?.addEventListener('input', function() {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(loadStaff, 500);
});

