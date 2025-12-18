// Visits Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadVisits();
    loadInmatesForVisit();
    loadVisitorsForVisit();
});

function loadVisits() {
    fetch(API_BASE + 'visits.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('visitsTableBody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No visits found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(visit => `
                <tr>
                    <td>${formatDate(visit.visit_date)}</td>
                    <td>${visit.visit_time}</td>
                    <td>${visit.inmate_name || 'N/A'}</td>
                    <td>${visit.visitor_name || 'N/A'}</td>
                    <td>${visit.visit_type}</td>
                    <td>${visit.duration_minutes} min</td>
                    <td><span class="status-badge status-${visit.status}">${visit.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewVisit(${visit.id})">View</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading visits:', error);
            showAlert('Error loading visits', 'error');
        });
}

function loadInmatesForVisit() {
    fetch(API_BASE + 'inmates.php?status=active')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('inmateSelect');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Inmate</option>' + 
                data.map(inmate => `
                    <option value="${inmate.id}">${inmate.inmate_id} - ${inmate.first_name} ${inmate.last_name}</option>
                `).join('');
        });
}

function loadVisitorsForVisit() {
    fetch(API_BASE + 'visitors.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('visitorSelect');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Visitor</option>' + 
                data.map(visitor => `
                    <option value="${visitor.id}">${visitor.visitor_id} - ${visitor.first_name} ${visitor.last_name}</option>
                `).join('');
        });
}

function saveVisit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'visits.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            closeModal('addVisitModal');
            loadVisits();
            form.reset();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving visit:', error);
        showAlert('Error saving visit', 'error');
    });
}

