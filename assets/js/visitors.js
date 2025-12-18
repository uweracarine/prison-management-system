// Visitors Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadVisitors();
});

function loadVisitors() {
    fetch(API_BASE + 'visitors.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('visitorsTableBody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">No visitors found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(visitor => `
                <tr>
                    <td>${visitor.visitor_id}</td>
                    <td>${visitor.first_name} ${visitor.last_name}</td>
                    <td>${visitor.phone || 'N/A'}</td>
                    <td>${visitor.email || 'N/A'}</td>
                    <td>${visitor.relationship || 'N/A'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewVisitor(${visitor.id})">View</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading visitors:', error);
            showAlert('Error loading visitors', 'error');
        });
}

function saveVisitor(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'visitors.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            closeModal('addVisitorModal');
            loadVisitors();
            form.reset();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving visitor:', error);
        showAlert('Error saving visitor', 'error');
    });
}

