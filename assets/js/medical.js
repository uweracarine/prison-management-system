// Medical Records Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadMedicalRecords();
    loadInmatesForMedical();
});

function loadMedicalRecords() {
    const tbody = document.getElementById('medicalTableBody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center"><div class="loading">Loading medical records...</div></td></tr>';
    }
    
    fetch(API_BASE + 'medical.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('medicalTableBody');
            if (!tbody) return;
            
            if (!Array.isArray(data)) {
                console.error('Invalid data format:', data);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center" style="color: red;">Error loading medical records. Please refresh the page.</td></tr>';
                return;
            }
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No medical records found</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(record => `
                <tr>
                    <td>${formatDate(record.record_date)}</td>
                    <td>${record.inmate_name} (${record.inmate_id})</td>
                    <td>${record.condition || 'N/A'}</td>
                    <td>${record.diagnosis ? (record.diagnosis.length > 50 ? record.diagnosis.substring(0, 50) + '...' : record.diagnosis) : 'N/A'}</td>
                    <td>${record.treatment ? (record.treatment.length > 50 ? record.treatment.substring(0, 50) + '...' : record.treatment) : 'N/A'}</td>
                    <td>${record.doctor_name || 'N/A'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewMedicalRecord(${record.id})">View</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading medical records:', error);
            showAlert('Error loading medical records', 'error');
        });
}

function loadInmatesForMedical() {
    fetch(API_BASE + 'inmates.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('medicalInmateSelect');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Inmate</option>' + 
                data.map(inmate => `
                    <option value="${inmate.id}">${inmate.inmate_id} - ${inmate.first_name} ${inmate.last_name}</option>
                `).join('');
        });
}

function saveMedical(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(API_BASE + 'medical.php', {
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
            closeModal('addMedicalModal');
            form.reset();
            setTimeout(() => {
                loadMedicalRecords();
            }, 300);
        } else {
            showAlert(result.message || 'Error saving medical record', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving medical record:', error);
        showAlert(error.message || 'Error saving medical record. Please try again.', 'error');
    });
}

