// User Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
});

function loadUsers() {
    fetch(API_BASE + 'users.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('usersTableBody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No users found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(user => `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.full_name}</td>
                    <td><span class="status-badge">${user.role}</span></td>
                    <td><span class="status-badge status-${user.is_active ? 'active' : 'inactive'}">${user.is_active ? 'Active' : 'Inactive'}</span></td>
                    <td>${user.last_login ? formatDateTime(user.last_login) : 'Never'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editUser(${user.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading users:', error);
            showAlert('Error loading users', 'error');
        });
}

function saveUser(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'users.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            closeModal('addUserModal');
            loadUsers();
            form.reset();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving user:', error);
        showAlert('Error saving user', 'error');
    });
}

function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) {
        return;
    }
    
    fetch(API_BASE + 'users.php?id=' + id, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert(result.message, 'success');
            loadUsers();
        } else {
            showAlert(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting user:', error);
        showAlert('Error deleting user', 'error');
    });
}

