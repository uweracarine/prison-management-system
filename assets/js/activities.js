// Activities Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadActivities();
    loadStaffForActivity();
});

function loadActivities() {
    fetch(API_BASE + 'activities.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('activitiesTableBody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No activities found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(activity => `
                <tr>
                    <td>${activity.activity_name}</td>
                    <td>${activity.activity_type}</td>
                    <td>${activity.schedule_days || 'N/A'} ${activity.schedule_time || ''}</td>
                    <td>${activity.instructor_name}</td>
                    <td>${activity.capacity || 'N/A'}</td>
                    <td><span class="status-badge status-${activity.status}">${activity.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewActivity(${activity.id})">View</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading activities:', error);
            showAlert('Error loading activities', 'error');
        });
}

function loadStaffForActivity() {
    fetch(API_BASE + 'staff.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('instructorSelect');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Instructor</option>' + 
                data.map(staff => `
                    <option value="${staff.id}">${staff.first_name} ${staff.last_name} - ${staff.position}</option>
                `).join('');
        });
}

function saveActivity(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'activities.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            closeModal('addActivityModal');
            loadActivities();
            form.reset();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving activity:', error);
        showAlert('Error saving activity', 'error');
    });
}

