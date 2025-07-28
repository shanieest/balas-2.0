<?php 
require_once __DIR__ . '/includes/auth.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residents | Barangay Balas Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }
        .img-preview {
            max-height: 200px;
            max-width: 100%;
        }
        .note-textarea {
            min-height: 100px;
        }
        .note-textarea.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        .account-notes {
            margin-top: 1rem;
        }
        .account-notes .notes-content {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            white-space: pre-wrap;
        }
        .account-status-badge {
            font-size: 0.75rem;
        }
        .account-approved { background-color: #28a745; }
        .account-pending { background-color: #ffc107; color: #212529; }
        .account-disapproved { background-color: #dc3545; }
        .account-details {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            margin-top: 1rem;
        }
        .auth-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .form-section-title {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .btn-auth {
            background-color: #3498db;
            color: white;
            width: 100%;
            padding: 10px;
            font-weight: 500;
        }
        .btn-auth:hover {
            background-color: #2980b9;
            color: white;
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
                    <h1 class="mt-4">Residents Management</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Residents</li>
                    </ol>
                    
                    <!-- Tabs for Residents and Account Requests -->
                    <ul class="nav nav-tabs mb-4" id="residentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="verified-tab" data-bs-toggle="tab" data-bs-target="#verified-residents" type="button" role="tab">Verified Residents</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#account-requests" type="button" role="tab">Account Requests <span class="badge bg-danger" id="pending-count">0</span></button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="residentTabsContent">
                        <!-- Verified Residents Tab -->
                        <div class="tab-pane fade show active" id="verified-residents" role="tabpanel">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-users me-1"></i>
                                            Verified Residents
                                        </div>
                                        <div>
                                            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                                                <i class="fas fa-plus me-1"></i> Add Resident
                                            </button>
                                            <button class="btn btn-success btn-sm" onclick="exportResidents()">
                                                <i class="fas fa-file-excel me-1"></i> Export to Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="residentsTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Contact</th>
                                                    <th>Birthdate</th>
                                                    <th>Account Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Requests Tab -->
                        <div class="tab-pane fade" id="account-requests" role="tabpanel">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user-clock me-1"></i>
                                            Resident Account Requests
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="requestsTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Contact</th>
                                                    <th>Date Requested</th>
                                                    <th>Status</th>
                                                    <th>Processed By</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- Add Resident Modal -->
    <div class="modal fade" id="addResidentModal" tabindex="-1" aria-labelledby="addResidentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addResidentModalLabel">Add New Resident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addResidentForm" enctype="multipart/form-data" novalidate>
                        <div class="form-section">
                            <h5 class="form-section-title"><i class="fas fa-user"></i> Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="firstName" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                                        <div class="invalid-feedback">Please provide a first name.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lastName" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                                        <div class="invalid-feedback">Please provide a last name.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="middleName" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middleName" name="middleName">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" id="suffix" name="suffix">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sex" class="form-label">Sex *</label>
                                        <select class="form-select" id="sex" name="sex" required>
                                            <option value="">Select...</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a sex.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="civilStatus" class="form-label">Civil Status *</label>
                                        <select class="form-select" id="civilStatus" name="civilStatus" required>
                                            <option value="">Select...</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Separated">Separated</option>
                                            <option value="Divorced">Divorced</option>
                                        </select>
                                        <div class="invalid-feedback">Please select civil status.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5 class="form-section-title"><i class="fas fa-address-card"></i> Contact Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contactNumber" class="form-label">Contact Number *</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="contactNumber" name="contactNumber" required>
                                        </div>
                                        <div class="invalid-feedback">Please provide a contact number.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Complete Address *</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                                <div class="invalid-feedback">Please provide a complete address.</div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5 class="form-section-title"><i class="fas fa-calendar-alt"></i> Birth Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birthdate" class="form-label">Birthdate *</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                                        </div>
                                        <div class="invalid-feedback">Please provide a birthdate.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="age" class="form-label">Age</label>
                                        <input type="number" class="form-control" id="age" name="age" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5 class="form-section-title"><i class="fas fa-user-plus"></i> Account Creation</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="createAccountCheck">
                                <label class="form-check-label" for="createAccountCheck">
                                    Create resident account for portal access
                                </label>
                            </div>
                            
                            <div id="accountFields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username *</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="username" name="username">
                                            </div>
                                            <div class="invalid-feedback">Please provide a username.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password *</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                            <div class="invalid-feedback">Please provide a password.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveResidentBtn">Add Resident</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Resident Modal -->
    <div class="modal fade" id="viewResidentModal" tabindex="-1" aria-labelledby="viewResidentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewResidentModalLabel">Resident Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="" class="img-thumbnail mb-3 resident-photo" width="150">
                            <h5 class="resident-name"></h5>
                            <p class="text-muted resident-id"></p>
                            <span class="badge verification-badge mb-2"></span>
                            <span class="badge resident-status-badge"></span>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Birthdate:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 resident-birthdate"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Sex:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 resident-sex"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Civil Status:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 resident-civil-status"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Contact:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 resident-contact"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Email:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 resident-email"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0"><strong>Address:</strong></p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0 resident-address"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-3">Valid ID:</h6>
                            <img src="" class="img-fluid rounded border img-preview resident-valid-id" alt="Valid ID">
                            
                            <!-- Account Information Section -->
                            <div class="account-details mt-4" style="display: none;">
                                <h6>Account Information:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <p class="mb-0"><strong>Status:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <span class="badge account-status-badge"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <p class="mb-0"><strong>Username:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="text-muted mb-0 resident-username"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <p class="mb-0"><strong>Processed By:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="text-muted mb-0 resident-processed-by"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <p class="mb-0"><strong>Date Processed:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="text-muted mb-0 resident-date-processed"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row mb-2">
                                            <div class="col-sm-2">
                                                <p class="mb-0"><strong>Notes:</strong></p>
                                            </div>
                                            <div class="col-sm-10">
                                                <p class="text-muted mb-0 resident-account-notes"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info text-white" id="verifyResidentBtn">Verify</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Account Request Modal -->
    <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewRequestModalLabel">Account Request Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="" class="img-thumbnail mb-3 request-photo" width="150">
                            <h5 class="request-name"></h5>
                            <p class="text-muted request-id"></p>
                            <span class="badge request-status-badge mb-2"></span>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Birthdate:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 request-birthdate"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Sex:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 request-sex"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Civil Status:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 request-civil-status"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Contact:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 request-contact"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Email:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 request-email"></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <p class="mb-0"><strong>Username:</strong></p>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0 request-username"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0"><strong>Address:</strong></p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0 request-address"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-3">Valid ID:</h6>
                            <img src="" class="img-fluid rounded border img-preview request-valid-id" alt="Valid ID">
                            
                            <!-- Request Details -->
                            <div class="account-details mt-4">
                                <h6>Request Information:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <p class="mb-0"><strong>Date Requested:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="text-muted mb-0 request-date-requested"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <p class="mb-0"><strong>Status:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <span class="badge request-status-badge"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Only show if processed -->
                                <div id="requestProcessedInfo" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Processed By:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0 request-processed-by"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Date Processed:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0 request-date-processed"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-2">
                                                <div class="col-sm-2">
                                                    <p class="mb-0"><strong>Notes:</strong></p>
                                                </div>
                                                <div class="col-sm-10">
                                                    <p class="text-muted mb-0 request-notes"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="approveRequestBtn">Approve</button>
                    <button type="button" class="btn btn-danger" id="rejectRequestBtn">Reject</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approve/Reject Request Modal -->
    <div class="modal fade" id="processRequestModal" tabindex="-1" aria-labelledby="processRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" id="processRequestModalHeader">
                    <h5 class="modal-title" id="processRequestModalLabel">Process Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="processRequestMessage">Please provide any notes for this action:</p>
                    <div class="mb-3">
                        <textarea class="form-control note-textarea" id="requestNote" placeholder="Enter notes..." required></textarea>
                        <div class="invalid-feedback">Please provide a note for this action.</div>
                    </div>
                    <input type="hidden" id="requestIdForProcess">
                    <input type="hidden" id="requestActionType">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn" id="confirmProcessRequestBtn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Resident Modal -->
    <div class="modal fade" id="deleteResidentModal" tabindex="-1" aria-labelledby="deleteResidentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteResidentModalLabel">Delete Resident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this resident record?</p>
                    <p><strong>Name:</strong> <span id="deleteResidentName"></span></p>
                    <p><strong>Resident ID:</strong> <span id="deleteResidentId"></span></p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Resident</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        // Global variables
        let currentResidentId = null;
        let currentRequestId = null;
        
        // Function to show toast notifications
        function showToast(message, type = 'success') {
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container';
                document.body.appendChild(toastContainer);
            }
            
            const toastElement = document.createElement('div');
            toastElement.className = `toast align-items-center text-white bg-${type} border-0`;
            toastElement.setAttribute('role', 'alert');
            toastElement.setAttribute('aria-live', 'assertive');
            toastElement.setAttribute('aria-atomic', 'true');
            
            toastElement.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toastElement);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Function to refresh resident list
        function refreshResidentList() {
            fetch('residents-backend.php?action=list')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        const tableBody = document.querySelector('#residentsTable tbody');
                        if (!tableBody) return;
                        
                        tableBody.innerHTML = '';
                        
                        data.data.forEach((resident, index) => {
                            // Account status badge
                            let accountStatusBadge = '';
                            if (resident.account_status === 'Approved') {
                                accountStatusBadge = '<span class="badge account-status-badge account-approved">Approved</span>';
                            } else if (resident.account_status === 'Pending') {
                                accountStatusBadge = '<span class="badge account-status-badge account-pending">Pending</span>';
                            } else if (resident.account_status === 'Disapproved') {
                                accountStatusBadge = '<span class="badge account-status-badge account-disapproved">Disapproved</span>';
                            } else {
                                accountStatusBadge = '<span class="badge bg-secondary">No Account</span>';
                            }
                            
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${resident.last_name}, ${resident.first_name} ${resident.middle_name || ''} ${resident.suffix || ''}</td>
                                <td>${resident.email || 'N/A'}</td>
                                <td>${resident.contact_number}</td>
                                <td>${resident.birthdate}</td>
                                <td>${accountStatusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-info view-btn" data-id="${resident.id}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${resident.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${resident.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                        
                        addButtonEventListeners();
                    } else {
                        showToast(data.message || 'Failed to load residents', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load residents: ' + error.message, 'danger');
                });
        }

        // Function to refresh account requests list
        function refreshAccountRequests() {
            fetch('residents-backend.php?action=account_requests')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        const tableBody = document.querySelector('#requestsTable tbody');
                        if (!tableBody) return;
                        
                        tableBody.innerHTML = '';
                        
                        // Update pending count badge
                        const pendingCount = data.data.filter(r => r.account_status === 'Pending').length;
                        document.getElementById('pending-count').textContent = pendingCount;
                        
                        data.data.forEach((request, index) => {
                            // Status badge
                            let statusBadge = '';
                            if (request.account_status === 'Approved') {
                                statusBadge = '<span class="badge account-approved">Approved</span>';
                            } else if (request.account_status === 'Pending') {
                                statusBadge = '<span class="badge account-pending">Pending</span>';
                            } else if (request.account_status === 'Disapproved') {
                                statusBadge = '<span class="badge account-disapproved">Disapproved</span>';
                            }
                            
                            // Processed by info
                            const processedBy = request.processed_by ? 
                                `${request.processed_by} (${request.date_processed})` : 'N/A';
                            
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${request.last_name}, ${request.first_name}</td>
                                <td>${request.email}</td>
                                <td>${request.contact_number}</td>
                                <td>${request.date_requested}</td>
                                <td>${statusBadge}</td>
                                <td>${processedBy}</td>
                                <td>
                                    <button class="btn btn-sm btn-info view-request-btn" data-id="${request.id}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ${request.account_status === 'Pending' ? `
                                    <button class="btn btn-sm btn-success approve-request-btn" data-id="${request.id}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger reject-request-btn" data-id="${request.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    ` : ''}
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                        
                        addRequestButtonEventListeners();
                    } else {
                        showToast(data.message || 'Failed to load account requests', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load account requests: ' + error.message, 'danger');
                });
        }

        // Function to add event listeners to all action buttons
        function addButtonEventListeners() {
            // View buttons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const residentId = this.getAttribute('data-id');
                    viewResident(residentId);
                });
            });
            
            // Edit buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const residentId = this.getAttribute('data-id');
                    editResident(residentId);
                });
            });
            
            // Delete buttons
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const residentId = this.getAttribute('data-id');
                    showDeleteModal(residentId);
                });
            });
        }

        // Function to add event listeners to request action buttons
        function addRequestButtonEventListeners() {
            // View request buttons
            document.querySelectorAll('.view-request-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-id');
                    viewRequest(requestId);
                });
            });
            
            // Approve request buttons
            document.querySelectorAll('.approve-request-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-id');
                    showProcessRequestModal(requestId, 'approve');
                });
            });
            
            // Reject request buttons
            document.querySelectorAll('.reject-request-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-id');
                    showProcessRequestModal(requestId, 'reject');
                });
            });
        }

        // View resident function
        function viewResident(id) {
            fetch(`residents-backend.php?action=list&id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        const resident = data.data[0];
                        const viewModal = document.getElementById('viewResidentModal');
                        if (!viewModal) {
                            throw new Error('View modal not found');
                        }

                        // Format birthdate
                        const birthdate = new Date(resident.birthdate);
                        const formattedBirthdate = birthdate.toLocaleDateString('en-US', { 
                            year: 'numeric', month: 'long', day: 'numeric' 
                        }) + ` (${resident.age} years old)`;
                        
                        // Set verification badge class
                        const verificationClass = resident.verification_status === 'Verified' ? 'bg-success' : 
                                               resident.verification_status === 'Pending' ? 'bg-warning' : 'bg-secondary';
                        
                        // Set resident status badge class
                        const statusClass = resident.resident_status === 'Active' ? 'bg-primary' : 
                                           resident.resident_status === 'Inactive' ? 'bg-secondary' :
                                           resident.resident_status === 'Deceased' ? 'bg-dark' : 'bg-info';
                        
                        // Update modal content
                        const verificationBadge = viewModal.querySelector('.verification-badge');
                        if (verificationBadge) {
                            verificationBadge.className = `badge ${verificationClass}`;
                            verificationBadge.textContent = resident.verification_status;
                        }

                        const statusBadge = viewModal.querySelector('.resident-status-badge');
                        if (statusBadge) {
                            statusBadge.className = `badge ${statusClass}`;
                            statusBadge.textContent = resident.resident_status;
                        }

                        // Update other fields
                        const updateField = (selector, value) => {
                            const element = viewModal.querySelector(selector);
                            if (element) element.textContent = value || 'N/A';
                        };

                        updateField('.resident-photo', resident.photo_path || 'img/default-profile.jpg');
                        updateField('.resident-name', `${resident.first_name} ${resident.last_name}`);
                        updateField('.resident-id', `Resident ID: BRGY-${resident.id.toString().padStart(4, '0')}`);
                        updateField('.resident-birthdate', formattedBirthdate);
                        updateField('.resident-sex', resident.sex === 'male' ? 'Male' : 'Female');
                        updateField('.resident-civil-status', resident.civil_status || 'N/A');
                        updateField('.resident-contact', resident.contact_number);
                        updateField('.resident-email', resident.email);
                        updateField('.resident-address', resident.address || 'N/A');
                        updateField('.resident-valid-id', resident.valid_id_path || 'img/default-id.jpg');

                        // Account information
                        const accountSection = viewModal.querySelector('.account-details');
                        if (accountSection) {
                            if (resident.account_status) {
                                accountSection.style.display = 'block';
                                
                                // Set account status badge
                                const accountStatusBadge = accountSection.querySelector('.account-status-badge');
                                if (accountStatusBadge) {
                                    accountStatusBadge.className = 'badge account-status-badge';
                                    if (resident.account_status === 'Approved') {
                                        accountStatusBadge.classList.add('account-approved');
                                    } else if (resident.account_status === 'Pending') {
                                        accountStatusBadge.classList.add('account-pending');
                                    } else if (resident.account_status === 'Disapproved') {
                                        accountStatusBadge.classList.add('account-disapproved');
                                    }
                                    accountStatusBadge.textContent = resident.account_status;
                                }
                                
                                updateField('.resident-username', resident.username || 'N/A');
                                updateField('.resident-processed-by', resident.account_processed_by || 'N/A');
                                updateField('.resident-date-processed', resident.account_date_processed || 'N/A');
                                updateField('.resident-account-notes', resident.account_notes || 'N/A');
                            } else {
                                accountSection.style.display = 'none';
                            }
                        }

                        // Set verify button state
                        const verifyBtn = document.getElementById('verifyResidentBtn');
                        if (verifyBtn) {
                            verifyBtn.dataset.id = resident.id;
                            verifyBtn.disabled = resident.verification_status === 'Verified';
                            verifyBtn.textContent = resident.verification_status === 'Verified' ? 'Verified' : 'Verify';
                        }

                        // Store current resident ID
                        currentResidentId = resident.id;
                        
                        // Show the modal
                        const modal = new bootstrap.Modal(viewModal);
                        modal.show();
                    } else {
                        showToast('Resident not found', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load resident details: ' + error.message, 'danger');
                });
        }

        // View request function
        function viewRequest(id) {
            fetch(`residents-backend.php?action=account_requests&id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        const request = data.data[0];
                        const viewModal = document.getElementById('viewRequestModal');
                        if (!viewModal) {
                            throw new Error('View request modal not found');
                        }

                        // Format birthdate
                        const birthdate = new Date(request.birthdate);
                        const formattedBirthdate = birthdate.toLocaleDateString('en-US', { 
                            year: 'numeric', month: 'long', day: 'numeric' 
                        }) + ` (${request.age} years old)`;
                        
                        // Set status badge class
                        let statusClass = '';
                        if (request.account_status === 'Approved') {
                            statusClass = 'account-approved';
                        } else if (request.account_status === 'Pending') {
                            statusClass = 'account-pending';
                        } else if (request.account_status === 'Disapproved') {
                            statusClass = 'account-disapproved';
                        }
                        
                        // Update modal content
                        const statusBadge = viewModal.querySelector('.request-status-badge');
                        if (statusBadge) {
                            statusBadge.className = `badge ${statusClass}`;
                            statusBadge.textContent = request.account_status;
                        }

                        // Update other fields
                        const updateField = (selector, value) => {
                            const element = viewModal.querySelector(selector);
                            if (element) element.textContent = value || 'N/A';
                        };

                        updateField('.request-photo', request.photo_path || 'img/default-profile.jpg');
                        updateField('.request-name', `${request.first_name} ${request.last_name}`);
                        updateField('.request-id', `Request ID: BRGY-REQ-${request.id.toString().padStart(4, '0')}`);
                        updateField('.request-birthdate', formattedBirthdate);
                        updateField('.request-sex', request.sex === 'male' ? 'Male' : 'Female');
                        updateField('.request-civil-status', request.civil_status || 'N/A');
                        updateField('.request-contact', request.contact_number);
                        updateField('.request-email', request.email);
                        updateField('.request-username', request.username);
                        updateField('.request-address', request.address || 'N/A');
                        updateField('.request-valid-id', request.valid_id_path || 'img/default-id.jpg');
                        updateField('.request-date-requested', request.date_requested || 'N/A');
                        updateField('.request-processed-by', request.processed_by || 'N/A');
                        updateField('.request-date-processed', request.date_processed || 'N/A');
                        updateField('.request-notes', request.notes || 'N/A');

                        // Show/hide processed info section
                        const processedInfo = viewModal.querySelector('#requestProcessedInfo');
                        if (processedInfo) {
                            processedInfo.style.display = request.account_status !== 'Pending' ? 'block' : 'none';
                        }

                        // Set buttons state
                        const approveBtn = document.getElementById('approveRequestBtn');
                        const rejectBtn = document.getElementById('rejectRequestBtn');
                        if (approveBtn && rejectBtn) {
                            approveBtn.dataset.id = request.id;
                            rejectBtn.dataset.id = request.id;
                            
                            if (request.account_status !== 'Pending') {
                                approveBtn.style.display = 'none';
                                rejectBtn.style.display = 'none';
                            } else {
                                approveBtn.style.display = 'inline-block';
                                rejectBtn.style.display = 'inline-block';
                            }
                        }

                        // Store current request ID
                        currentRequestId = request.id;
                        
                        // Show the modal
                        const modal = new bootstrap.Modal(viewModal);
                        modal.show();
                    } else {
                        showToast('Request not found', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load request details: ' + error.message, 'danger');
                });
        }

        // Edit resident function (basic implementation)
        function editResident(id) {
            fetch(`residents-backend.php?action=list&id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        const resident = data.data[0];
                        
                        // Populate the edit form (you'll need to create an edit modal)
                        // For now, we'll just show a toast
                        showToast(`Editing resident: ${resident.first_name} ${resident.last_name}`, 'info');
                        
                        // In a complete implementation, you would:
                        // 1. Open an edit modal
                        // 2. Populate the form with resident data
                        // 3. Handle the form submission
                    } else {
                        showToast('Resident not found', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load resident details: ' + error.message, 'danger');
                });
        }

        // Verify resident function
        function verifyResident(id) {
            if (confirm('Are you sure you want to verify this resident?')) {
                fetch('residents-backend.php?action=verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Resident verified successfully');
                        refreshResidentList();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('viewResidentModal'));
                        if (modal) modal.hide();
                    } else {
                        showToast(data.message || 'Error verifying resident', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to verify resident: ' + error.message, 'danger');
                });
            }
        }

        // Show delete confirmation modal
        function showDeleteModal(id) {
            fetch(`residents-backend.php?action=list&id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        const resident = data.data[0];
                        document.getElementById('deleteResidentName').textContent = `${resident.first_name} ${resident.last_name}`;
                        document.getElementById('deleteResidentId').textContent = `BRGY-${resident.id.toString().padStart(4, '0')}`;
                        document.getElementById('confirmDeleteBtn').dataset.id = resident.id;
                        
                        const modal = new bootstrap.Modal(document.getElementById('deleteResidentModal'));
                        modal.show();
                    } else {
                        showToast('Resident not found', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load resident details: ' + error.message, 'danger');
                });
        }

        // Delete resident function
        function deleteResident(id) {
            fetch('residents-backend.php?action=delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Resident deleted successfully');
                    refreshResidentList();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteResidentModal'));
                    if (modal) modal.hide();
                } else {
                    showToast(data.message || 'Error deleting resident', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to delete resident: ' + error.message, 'danger');
            });
        }

        // Show process request modal (approve/reject)
        function showProcessRequestModal(id, action) {
            currentRequestId = id;
            
            const modal = document.getElementById('processRequestModal');
            if (!modal) return;
            
            const header = modal.querySelector('#processRequestModalHeader');
            const title = modal.querySelector('#processRequestModalLabel');
            const message = modal.querySelector('#processRequestMessage');
            const submitBtn = modal.querySelector('#confirmProcessRequestBtn');
            const noteTextarea = modal.querySelector('#requestNote');
            
            if (action === 'approve') {
                header.className = 'modal-header bg-success text-white';
                title.textContent = 'Approve Account Request';
                message.textContent = 'You may provide optional notes for this approval:';
                submitBtn.className = 'btn btn-success';
                submitBtn.textContent = 'Approve';
                noteTextarea.required = false;
                noteTextarea.classList.remove('is-invalid');
            } else {
                header.className = 'modal-header bg-danger text-white';
                title.textContent = 'Reject Account Request';
                message.textContent = 'Please provide the reason for rejection (required):';
                submitBtn.className = 'btn btn-danger';
                submitBtn.textContent = 'Reject';
                noteTextarea.required = true;
            }
            
            document.getElementById('requestIdForProcess').value = id;
            document.getElementById('requestActionType').value = action;
            noteTextarea.value = '';
            
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }

        // Process account request (approve/reject)
        function processAccountRequest(id, action, note) {
            // Validate that rejection has a note
            if (action === 'reject' && !note.trim()) {
                document.getElementById('requestNote').classList.add('is-invalid');
                showToast('Please provide a reason for rejection', 'danger');
                return;
            }

            fetch('residents-backend.php?action=process_request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&action=${action}&note=${encodeURIComponent(note)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(`Account request ${action}d successfully`);
                    refreshAccountRequests();
                    refreshResidentList();
                    
                    // Close modals
                    const processModal = bootstrap.Modal.getInstance(document.getElementById('processRequestModal'));
                    if (processModal) processModal.hide();
                    
                    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRequestModal'));
                    if (viewModal) viewModal.hide();
                } else {
                    showToast(data.message || `Error ${action}ing request`, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(`Failed to ${action} request: ` + error.message, 'danger');
            });
        }

        // Export to Excel function
        function exportResidents() {
            window.location.href = 'residents-backend.php?action=export';
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial resident list and account requests
            refreshResidentList();
            refreshAccountRequests();
            
            // Toggle account creation fields
            const createAccountCheck = document.getElementById('createAccountCheck');
            const accountFields = document.getElementById('accountFields');
            if (createAccountCheck && accountFields) {
                createAccountCheck.addEventListener('change', function() {
                    accountFields.style.display = this.checked ? 'block' : 'none';
                    
                    // Toggle required attribute on account fields
                    const username = document.getElementById('username');
                    const password = document.getElementById('password');
                    if (username && password) {
                        username.required = this.checked;
                        password.required = this.checked;
                    }
                });
            }
            
            // Add resident form submission
            const saveResidentBtn = document.getElementById('saveResidentBtn');
            if (saveResidentBtn) {
                saveResidentBtn.addEventListener('click', async function() {
                    const form = document.getElementById('addResidentForm');
                    if (!form) return;
                    
                    if (!form.checkValidity()) {
                        form.classList.add('was-validated');
                        return;
                    }

                    const formData = new FormData(form);
                    const originalText = saveResidentBtn.innerHTML;
                    saveResidentBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                    saveResidentBtn.disabled = true;

                    try {
                        const response = await fetch('residents-backend.php?action=add', {
                            method: 'POST',
                            body: formData
                        });
                        
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showToast('Resident added successfully!', 'success');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addResidentModal'));
                            if (modal) modal.hide();
                            form.reset();
                            form.classList.remove('was-validated');
                            refreshResidentList();
                        } else {
                            throw new Error(data.message || 'Failed to save resident');
                        }
                    } catch (error) {
                        showToast(error.message, 'danger');
                        console.error('Error:', error);
                    } finally {
                        saveResidentBtn.innerHTML = originalText;
                        saveResidentBtn.disabled = false;
                    }
                });
            }
            
            // Delete resident confirmation
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    const residentId = this.dataset.id;
                    if (residentId) {
                        deleteResident(residentId);
                    }
                });
            }
            
            // Verify button in view modal
            const verifyResidentBtn = document.getElementById('verifyResidentBtn');
            if (verifyResidentBtn) {
                verifyResidentBtn.addEventListener('click', function() {
                    const residentId = this.dataset.id;
                    if (residentId) {
                        verifyResident(residentId);
                    }
                });
            }
            
            // Approve/Reject request buttons in view modal
            const approveRequestBtn = document.getElementById('approveRequestBtn');
            const rejectRequestBtn = document.getElementById('rejectRequestBtn');
            if (approveRequestBtn && rejectRequestBtn) {
                approveRequestBtn.addEventListener('click', function() {
                    const requestId = this.dataset.id;
                    if (requestId) {
                        showProcessRequestModal(requestId, 'approve');
                    }
                });
                
                rejectRequestBtn.addEventListener('click', function() {
                    const requestId = this.dataset.id;
                    if (requestId) {
                        showProcessRequestModal(requestId, 'reject');
                    }
                });
            }
            
            // Confirm process request button
            const confirmProcessRequestBtn = document.getElementById('confirmProcessRequestBtn');
            if (confirmProcessRequestBtn) {
                confirmProcessRequestBtn.addEventListener('click', function() {
                    const requestId = document.getElementById('requestIdForProcess').value;
                    const action = document.getElementById('requestActionType').value;
                    const note = document.getElementById('requestNote').value;
                    
                    if (requestId) {
                        processAccountRequest(requestId, action, note);
                    }
                });
            }
            
            // Calculate age when birthdate changes
            const birthdateInput = document.getElementById('birthdate');
            if (birthdateInput) {
                birthdateInput.addEventListener('change', function() {
                    if (this.value) {
                        const birthdate = new Date(this.value);
                        const today = new Date();
                        let age = today.getFullYear() - birthdate.getFullYear();
                        const monthDiff = today.getMonth() - birthdate.getMonth();
                        
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                            age--;
                        }
                        
                        const ageInput = document.getElementById('age');
                        if (ageInput) ageInput.value = age;
                    }
                });
            }
            
            // Reset forms when modal is closed
            const addResidentModal = document.getElementById('addResidentModal');
            if (addResidentModal) {
                addResidentModal.addEventListener('hidden.bs.modal', function() {
                    const form = document.getElementById('addResidentForm');
                    if (form) {
                        form.reset();
                        form.classList.remove('was-validated');
                        document.getElementById('accountFields').style.display = 'none';
                        document.getElementById('createAccountCheck').checked = false;
                    }
                });
            }
            
            const processRequestModal = document.getElementById('processRequestModal');
            if (processRequestModal) {
                processRequestModal.addEventListener('hidden.bs.modal', function() {
                    const noteInput = document.getElementById('requestNote');
                    if (noteInput) {
                        noteInput.value = '';
                        noteInput.classList.remove('is-invalid');
                    }
                });
            }
            
            // Tab change event to refresh data
            const residentTabs = document.getElementById('residentTabs');
            if (residentTabs) {
                residentTabs.addEventListener('shown.bs.tab', function(event) {
                    if (event.target.id === 'requests-tab') {
                        refreshAccountRequests();
                    } else if (event.target.id === 'verified-tab') {
                        refreshResidentList();
                    }
                });
            }
        });
    </script>
</body>
</html>