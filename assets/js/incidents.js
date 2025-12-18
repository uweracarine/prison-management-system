// Incidents Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadIncidents();
    loadInmatesForIncident();
    loadStaffForIncident();
});

function loadIncidents() {
    const tbody = document.getElementById('incidentsTableBody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center"><div class="loading">Loading incidents...</div></td></tr>';
    }
    
    fetch(API_BASE + 'incidents.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('incidentsTableBody');
            if (!tbody) return;
            
            if (!Array.isArray(data)) {
                console.error('Invalid data format:', data);
                tbody.innerHTML = '<tr><td colspan="8" class="text-center" style="color: red;">Error loading incidents. Please refresh the page.</td></tr>';
                return;
            }
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No incidents found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(incident => `
                <tr>
                    <td>${incident.incident_id}</td>
                    <td>${formatDateTime(incident.incident_date) || formatDate(incident.incident_date)}</td>
                    <td>${incident.incident_type}</td>
                    <td><span class="status-badge status-${incident.severity}">${incident.severity}</span></td>
                    <td>${incident.inmate_name || 'N/A'}</td>
                    <td>${incident.staff_name || 'N/A'}</td>
                    <td><span class="status-badge status-${incident.status || 'reported'}">${incident.status || 'reported'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewIncident(${incident.id})">View</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading incidents:', error);
            showAlert('Error loading incidents', 'error');
        });
}

function loadInmatesForIncident() {
    fetch(API_BASE + 'inmates.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('incidentInmateSelect');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Inmate (if applicable)</option>' + 
                data.map(inmate => `
                    <option value="${inmate.id}">${inmate.inmate_id} - ${inmate.first_name} ${inmate.last_name}</option>
                `).join('');
        });
}

function loadStaffForIncident() {
    fetch(API_BASE + 'staff.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('incidentStaffSelect');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Staff (if applicable)</option>' + 
                data.map(staff => `
                    <option value="${staff.id}">${staff.staff_id} - ${staff.first_name} ${staff.last_name}</option>
                `).join('');
        });
}

function saveIncident(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'incidents.php', {
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
            closeModal('addIncidentModal');
            form.reset();
            setTimeout(() => {
                loadIncidents();
            }, 300);
        } else {
            showAlert(result.message || 'Error saving incident', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving incident:', error);
        showAlert(error.message || 'Error saving incident. Please try again.', 'error');
    });
}

