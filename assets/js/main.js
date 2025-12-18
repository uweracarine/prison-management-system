// Main JavaScript file for Prison Management System

const API_BASE = 'api/';

// Utility Functions
function showAlert(message, type = 'error') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('en-US');
}

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        
        // Reload cells when opening the add inmate modal
        if (modalId === 'addInmateModal' && typeof loadCellsForInmate === 'function') {
            loadCellsForInmate();
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            form.querySelector('input[type="hidden"]')?.remove();
        }
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Dashboard Functions
function loadRecentInmates() {
    fetch(API_BASE + 'inmates.php?limit=5')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('recentInmates');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No inmates found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(inmate => `
                <tr>
                    <td>${inmate.inmate_id}</td>
                    <td>${inmate.first_name} ${inmate.last_name}</td>
                    <td>${inmate.crime_type}</td>
                    <td><span class="status-badge status-${inmate.status}">${inmate.status}</span></td>
                    <td><a href="inmates.php?id=${inmate.id}" class="btn btn-sm btn-primary">View</a></td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading recent inmates:', error);
        });
}

function loadUpcomingVisits() {
    fetch(API_BASE + 'visits.php?upcoming=true')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('upcomingVisits');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No upcoming visits</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(visit => `
                <tr>
                    <td>${formatDate(visit.visit_date)}</td>
                    <td>${visit.inmate_name || 'N/A'}</td>
                    <td>${visit.visitor_name || 'N/A'}</td>
                    <td>${visit.visit_type}</td>
                    <td><span class="status-badge status-${visit.status}">${visit.status}</span></td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading upcoming visits:', error);
        });
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.filters-bar')?.nextElementSibling?.querySelector('tbody');
            if (table) {
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            }
        });
    });
});

