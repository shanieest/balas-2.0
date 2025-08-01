<?php 
require_once __DIR__ . '/includes/auth.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Officials | Barangay Balas Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .badge {
            font-size: 0.9em;
        }
        .alert {
            transition: opacity 0.15s linear;
        }
        .position-select {
            width: 250px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'includes/navbar.php'; ?>
    
    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Barangay Officials</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Barangay Officials</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Manage Barangay Officials
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOfficialModal">
                                    <i class="fas fa-plus me-1"></i> Add Official
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="officialsTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- Add Official Modal -->
    <div class="modal fade" id="addOfficialModal" tabindex="-1" aria-labelledby="addOfficialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addOfficialModalLabel">Add New Barangay Official</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addOfficialForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="officialFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="officialFirstName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="officialMiddleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="officialMiddleName">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="officialLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="officialLastName" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="officialPosition" class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select position-select" id="officialPosition" required>
                                    <option value="">Select Position...</option>
                                    <option value="Barangay Captain">Barangay Captain</option>
                                    <option value="Barangay Secretary">Barangay Secretary</option>
                                    <option value="Barangay Treasurer">Barangay Treasurer</option>
                                    <option value="Barangay Kagawad">Barangay Kagawad</option>
                                    <option value="SK Chairman">SK Chairman</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="officialStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="officialStatus" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="officialEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="officialEmail" required>
                                <div class="form-text">Will be used for login</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="officialContact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="officialContact" pattern="[0-9]{11}" title="Please enter a valid 11-digit phone number">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="officialPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="officialPassword" required minlength="8">
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="officialConfirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="officialConfirmPassword" required minlength="8">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Official</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Official Modal -->
    <div class="modal fade" id="editOfficialModal" tabindex="-1" aria-labelledby="editOfficialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editOfficialModalLabel">Edit Barangay Official</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editOfficialForm">
                    <input type="hidden" id="editOfficialId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editOfficialFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editOfficialFirstName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editOfficialMiddleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="editOfficialMiddleName">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editOfficialLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editOfficialLastName" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editOfficialPosition" class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select position-select" id="editOfficialPosition" required>
                                    <option value="Barangay Captain">Barangay Captain</option>
                                    <option value="Barangay Secretary">Barangay Secretary</option>
                                    <option value="Barangay Treasurer">Barangay Treasurer</option>
                                    <option value="Barangay Kagawad">Barangay Kagawad</option>
                                    <option value="SK Chairman">SK Chairman</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editOfficialStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="editOfficialStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editOfficialEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="editOfficialEmail" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editOfficialContact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="editOfficialContact" pattern="[0-9]{11}" title="Please enter a valid 11-digit phone number">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editOfficialPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="editOfficialPassword" minlength="8">
                            <div class="form-text">Leave blank to keep current password</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white">Update Official</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Official Modal -->
    <div class="modal fade" id="deleteOfficialModal" tabindex="-1" aria-labelledby="deleteOfficialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteOfficialModalLabel">Delete Barangay Official</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this barangay official?</p>
                    <p><strong>Name:</strong> <span id="deleteOfficialName"></span></p>
                    <p><strong>Position:</strong> <span id="deleteOfficialPosition"></span></p>
                    <p class="text-danger">This action cannot be undone.</p>
                    <input type="hidden" id="deleteOfficialId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Official</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load officials data
            loadOfficials();
            
            // Add event listeners for modals
            document.getElementById('addOfficialForm').addEventListener('submit', addOfficial);
            document.getElementById('editOfficialForm').addEventListener('submit', updateOfficial);
            document.getElementById('confirmDeleteBtn').addEventListener('click', deleteOfficial);
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function loadOfficials() {
            fetch('barangay-officials-backend.php?action=get_officials')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load officials');
                    }
                    
                    const tableBody = document.querySelector('#officialsTable tbody');
                    tableBody.innerHTML = '';
                    
                    data.data.forEach((official, index) => {
                        const row = document.createElement('tr');
                        const fullName = `${official.first_name} ${official.middle_name ? official.middle_name + ' ' : ''}${official.last_name}`;
                        
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${fullName}</td>
                            <td>${official.position}</td>
                            <td>
                                ${official.email}<br>
                                ${official.contact_number || 'N/A'}
                            </td>
                            <td><span class="badge ${official.status === 'Active' ? 'bg-success' : 'bg-secondary'}">${official.status}</span></td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${official.id}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${official.id}" 
                                    ${official.position === 'Barangay Captain' ? 'disabled data-bs-toggle="tooltip" title="Cannot delete Barangay Captain"' : 'data-bs-toggle="tooltip" title="Delete"'} >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to edit/delete buttons
                    document.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            loadOfficialData(id);
                        });
                    });
                    
                    document.querySelectorAll('.delete-btn:not([disabled])').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const row = this.closest('tr');
                            const name = row.querySelector('td:nth-child(2)').textContent;
                            const position = row.querySelector('td:nth-child(3)').textContent;
                            
                            document.getElementById('deleteOfficialId').value = id;
                            document.getElementById('deleteOfficialName').textContent = name;
                            document.getElementById('deleteOfficialPosition').textContent = position;
                            
                            new bootstrap.Modal(document.getElementById('deleteOfficialModal')).show();
                        });
                    });
                })
                .catch(error => {
                    console.error('Error loading officials:', error);
                    showAlert('Failed to load officials: ' + error.message, 'error');
                });
        }

        function loadOfficialData(id) {
            fetch(`barangay-officials-backend.php?action=get_official&id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load official data');
                    }
                    
                    const official = data.data;
                    document.getElementById('editOfficialId').value = official.id;
                    document.getElementById('editOfficialFirstName').value = official.first_name;
                    document.getElementById('editOfficialMiddleName').value = official.middle_name || '';
                    document.getElementById('editOfficialLastName').value = official.last_name;
                    document.getElementById('editOfficialPosition').value = official.position;
                    document.getElementById('editOfficialEmail').value = official.email;
                    document.getElementById('editOfficialContact').value = official.contact_number || '';
                    document.getElementById('editOfficialStatus').value = official.status;
                    
                    new bootstrap.Modal(document.getElementById('editOfficialModal')).show();
                })
                .catch(error => {
                    console.error('Error loading official data:', error);
                    showAlert('Failed to load official data: ' + error.message, 'error');
                });
        }

        function addOfficial(e) {
            e.preventDefault();
            
            const password = document.getElementById('officialPassword').value;
            const confirmPassword = document.getElementById('officialConfirmPassword').value;
            
            if (password !== confirmPassword) {
                showAlert('Passwords do not match', 'error');
                return;
            }
            
            if (password.length < 8) {
                showAlert('Password must be at least 8 characters long', 'error');
                return;
            }
            
            const formData = {
                first_name: document.getElementById('officialFirstName').value.trim(),
                middle_name: document.getElementById('officialMiddleName').value.trim(),
                last_name: document.getElementById('officialLastName').value.trim(),
                position: document.getElementById('officialPosition').value,
                email: document.getElementById('officialEmail').value.trim(),
                contact_number: document.getElementById('officialContact').value.trim(),
                status: document.getElementById('officialStatus').value,
                password: password
            };
            
            // Validate required fields
            if (!formData.first_name || !formData.last_name || !formData.position || !formData.email) {
                showAlert('Please fill in all required fields', 'error');
                return;
            }
            
            fetch('barangay-officials-backend.php?action=add_official', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Failed to add official'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('Official added successfully!', 'success');
                    document.getElementById('addOfficialForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('addOfficialModal')).hide();
                    loadOfficials();
                } else {
                    throw new Error(data.message || 'Failed to add official');
                }
            })
            .catch(error => {
                console.error('Error adding official:', error);
                showAlert('Error: ' + error.message, 'error');
            });
        }

        function updateOfficial(e) {
            e.preventDefault();
            
            const password = document.getElementById('editOfficialPassword').value;
            if (password && password.length < 8) {
                showAlert('Password must be at least 8 characters long', 'error');
                return;
            }
            
            const formData = {
                id: document.getElementById('editOfficialId').value,
                first_name: document.getElementById('editOfficialFirstName').value.trim(),
                middle_name: document.getElementById('editOfficialMiddleName').value.trim(),
                last_name: document.getElementById('editOfficialLastName').value.trim(),
                position: document.getElementById('editOfficialPosition').value,
                email: document.getElementById('editOfficialEmail').value.trim(),
                contact_number: document.getElementById('editOfficialContact').value.trim(),
                status: document.getElementById('editOfficialStatus').value,
                password: password || undefined
            };
            
            // Validate required fields
            if (!formData.first_name || !formData.last_name || !formData.position || !formData.email) {
                showAlert('Please fill in all required fields', 'error');
                return;
            }
            
            fetch('barangay-officials-backend.php?action=update_official', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Failed to update official'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('Official updated successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editOfficialModal')).hide();
                    loadOfficials();
                } else {
                    throw new Error(data.message || 'Failed to update official');
                }
            })
            .catch(error => {
                console.error('Error updating official:', error);
                showAlert('Error: ' + error.message, 'error');
            });
        }

        function deleteOfficial() {
            const id = document.getElementById('deleteOfficialId').value;
            
            if (!id) {
                showAlert('Invalid official ID', 'error');
                return;
            }
            
            fetch(`barangay-officials-backend.php?action=delete_official&id=${id}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Failed to delete official'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('Official deleted successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deleteOfficialModal')).hide();
                    loadOfficials();
                } else {
                    throw new Error(data.message || 'Failed to delete official');
                }
            })
            .catch(error => {
                console.error('Error deleting official:', error);
                showAlert('Error: ' + error.message, 'error');
            });
        }

        function showAlert(message, type) {
            Swal.fire({
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                position: 'top-end',
                toast: true
            });
        }
    </script>
</body>
</html>