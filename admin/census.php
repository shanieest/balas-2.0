<?php 
require_once __DIR__ . '/includes/auth.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Census Data | Barangay Balas Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        .detail-view {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .detail-view.show {
            max-height: 1000px;
            transition: max-height 0.5s ease-in;
        }
        .household-badge {
            font-size: 0.75rem;
        }
        .card-header {
            background-color: #f8f9fa;
        }
        .table th {
            background-color: #f8f9fa;
            white-space: nowrap;
        }
        .bg-light-custom {
            background-color: #f8f9fa;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
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
                    <h1 class="mt-4">Census Data</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Census Data</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users me-1"></i>
                                    Household Records
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHouseholdModal">
                                    <i class="fas fa-plus me-1"></i> Add Household
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filters Section -->
                            <div class="card mb-4 bg-light-custom">
                                <div class="card-header py-2 bg-light-custom">
                                    <button class="btn btn-sm btn-link text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                        <i class="fas fa-filter me-1"></i> Advanced Filters
                                    </button>
                                </div>
                                <div class="collapse show" id="filterCollapse">
                                    <div class="card-body py-3">
                                        <form id="filterForm">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Purok</label>
                                                    <select class="form-select" name="purok">
                                                        <option value="" selected>All Puroks</option>
                                                        <option value="1">Purok 1</option>
                                                        <option value="2">Purok 2</option>
                                                        <option value="3">Purok 3</option>
                                                        <option value="4">Purok 4</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">House Type</label>
                                                    <select class="form-select" name="house_type">
                                                        <option value="" selected>All Types</option>
                                                        <option value="Single-detached">Single-detached</option>
                                                        <option value="Duplex">Duplex</option>
                                                        <option value="Apartment">Apartment</option>
                                                        <option value="Shanty">Shanty</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Water Source</label>
                                                    <select class="form-select" name="water_source">
                                                        <option value="" selected>All Sources</option>
                                                        <option value="Level I">Level I (Well)</option>
                                                        <option value="Level II">Level II (Deep Well)</option>
                                                        <option value="Level III">Level III (Piped)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="" selected>All Statuses</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                        <option value="Incomplete">Incomplete</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="reset" class="btn btn-outline-secondary me-2">
                                                        <i class="fas fa-undo me-1"></i> Reset
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Search and Table Controls -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-pill household-badge me-3">
                                        <span id="totalHouseholds">0</span> Total Households
                                    </span>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <span class="input-group-text">Show</span>
                                        <select class="form-select form-select-sm" id="rowsPerPage">
                                            <option value="10">10</option>
                                            <option value="25" selected>25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search...">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="searchButton">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Main Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="householdsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Household No.</th>
                                            <th>Head of Family</th>
                                            <th>Purok</th>
                                            <th>Members</th>
                                            <th>House Type</th>
                                            <th>Status</th>
                                            <th width="120">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="householdsTableBody">
                                        <!-- Data will be loaded via JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Showing <span id="showingFrom">1</span> to <span id="showingTo">5</span> of <span id="totalRecords">0</span> entries
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm mb-0" id="pagination">
                                        <li class="page-item disabled" id="prevPage">
                                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item" id="nextPage">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- Add Household Modal -->
    <div class="modal fade" id="addHouseholdModal" tabindex="-1" aria-labelledby="addHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addHouseholdModalLabel">Add New Household</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addHouseholdForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Household Number</label>
                                <input type="text" class="form-control" id="householdNumber" value="BL-<?= date('Y') ?>-0001" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purok <span class="text-danger">*</span></label>
                                <select class="form-select" id="purok" required>
                                    <option value="" selected disabled>Select Purok</option>
                                    <option value="1">Purok 1</option>
                                    <option value="2">Purok 2</option>
                                    <option value="3">Purok 3</option>
                                    <option value="4">Purok 4</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" placeholder="Enter complete address" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">House Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="houseType" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Shanty">Shanty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ownership <span class="text-danger">*</span></label>
                                <select class="form-select" id="ownership" required>
                                    <option value="" selected disabled>Select Ownership</option>
                                    <option value="Owned">Owned</option>
                                    <option value="Rented">Rented</option>
                                    <option value="Leased">Leased</option>
                                    <option value="Informal Settler">Informal Settler</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Water Source <span class="text-danger">*</span></label>
                                <select class="form-select" id="waterSource" required>
                                    <option value="" selected disabled>Select Water Source</option>
                                    <option value="Level I">Level I (Well)</option>
                                    <option value="Level II">Level II (Deep Well)</option>
                                    <option value="Level III">Level III (Piped)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Electricity <span class="text-danger">*</span></label>
                                <select class="form-select" id="electricity" required>
                                    <option value="" selected disabled>Select Electricity</option>
                                    <option value="With Meter">With Meter</option>
                                    <option value="Without Meter">Without Meter</option>
                                    <option value="No Electricity">No Electricity</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Household</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Household Modal -->
    <div class="modal fade" id="viewHouseholdModal" tabindex="-1" aria-labelledby="viewHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewHouseholdModalLabel">Household Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Household Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Household No:</strong> <span id="viewHouseholdNumber">BL-2023-0456</span></p>
                                            <p class="mb-1"><strong>Purok:</strong> <span id="viewPurok">2</span></p>
                                            <p class="mb-1"><strong>Address:</strong> <span id="viewAddress">123 Balas Street</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>House Type:</strong> <span id="viewHouseType">Single-detached</span></p>
                                            <p class="mb-1"><strong>Ownership:</strong> <span id="viewOwnership">Owned</span></p>
                                            <p class="mb-1"><strong>Year Built:</strong> <span id="viewYearBuilt">2010</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Household Amenities</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Water Source:</strong> <span id="viewWaterSource">Level III (Piped)</span></p>
                                            <p class="mb-1"><strong>Electricity:</strong> <span id="viewElectricity">With Meter</span></p>
                                            <p class="mb-1"><strong>Internet:</strong> <span id="viewInternet">DSL</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Toilet Facility:</strong> <span id="viewToiletFacility">Water-sealed</span></p>
                                            <p class="mb-1"><strong>Waste Disposal:</strong> <span id="viewWasteDisposal">Garbage Collection</span></p>
                                            <p class="mb-1"><strong>Vehicle:</strong> <span id="viewVehicle">Motorcycle, Car</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Household Members</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered" id="membersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Civil Status</th>
                                    <th>Occupation</th>
                                    <th>Education</th>
                                    <th>Voter</th>
                                </tr>
                            </thead>
                            <tbody id="membersTableBody">
                                <!-- Members will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Livelihood</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="livelihoodList">
                                        <!-- Livelihood items will be loaded here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Government Assistance</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="assistanceList">
                                        <!-- Assistance items will be loaded here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editHouseholdBtn">Edit Household</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Household Modal -->
    <div class="modal fade" id="editHouseholdModal" tabindex="-1" aria-labelledby="editHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editHouseholdModalLabel">Edit Household</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editHouseholdForm">
                    <input type="hidden" id="editHouseholdId">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Household Number</label>
                                <input type="text" class="form-control" id="editHouseholdNumber" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purok <span class="text-danger">*</span></label>
                                <select class="form-select" id="editPurok" required>
                                    <option value="" selected disabled>Select Purok</option>
                                    <option value="1">Purok 1</option>
                                    <option value="2">Purok 2</option>
                                    <option value="3">Purok 3</option>
                                    <option value="4">Purok 4</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editAddress" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">House Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="editHouseType" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Shanty">Shanty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ownership <span class="text-danger">*</span></label>
                                <select class="form-select" id="editOwnership" required>
                                    <option value="" selected disabled>Select Ownership</option>
                                    <option value="Owned">Owned</option>
                                    <option value="Rented">Rented</option>
                                    <option value="Leased">Leased</option>
                                    <option value="Informal Settler">Informal Settler</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Water Source <span class="text-danger">*</span></label>
                                <select class="form-select" id="editWaterSource" required>
                                    <option value="" selected disabled>Select Water Source</option>
                                    <option value="Level I">Level I (Well)</option>
                                    <option value="Level II">Level II (Deep Well)</option>
                                    <option value="Level III">Level III (Piped)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Electricity <span class="text-danger">*</span></label>
                                <select class="form-select" id="editElectricity" required>
                                    <option value="" selected disabled>Select Electricity</option>
                                    <option value="With Meter">With Meter</option>
                                    <option value="Without Meter">Without Meter</option>
                                    <option value="No Electricity">No Electricity</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="editStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Incomplete">Incomplete</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Household Members</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-bordered" id="editMembersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Civil Status</th>
                                        <th>Occupation</th>
                                        <th>Education</th>
                                        <th>Voter</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="editMembersTableBody">
                                    <!-- Members will be loaded here for editing -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="addMemberBtn">
                                <i class="fas fa-plus me-1"></i> Add Member
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light-custom py-2">
                                        <h6 class="mb-0">Livelihood</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="livelihoodItems">
                                            <!-- Livelihood items will be loaded here -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="addLivelihoodBtn">
                                            <i class="fas fa-plus me-1"></i> Add Livelihood
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light-custom py-2">
                                        <h6 class="mb-0">Government Assistance</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="assistanceItems">
                                            <!-- Assistance items will be loaded here -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="addAssistanceBtn">
                                            <i class="fas fa-plus me-1"></i> Add Assistance
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white">Update Household</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addMemberModalLabel">Add Household Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addMemberForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="memberFirstName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="memberLastName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="memberMiddleName">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberRelationship" required>
                                    <option value="" selected disabled>Select Relationship</option>
                                    <option value="Head">Head</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Other Relative">Other Relative</option>
                                    <option value="Non-relative">Non-relative</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="memberAge" min="0" max="120" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberGender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberCivilStatus" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="memberOccupation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Education</label>
                                <select class="form-select" id="memberEducation">
                                    <option value="" selected disabled>Select Education</option>
                                    <option value="None">None</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="High School">High School</option>
                                    <option value="College">College</option>
                                    <option value="Vocational">Vocational</option>
                                    <option value="Post Graduate">Post Graduate</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Voter</label>
                                <select class="form-select" id="memberVoter">
                                    <option value="Yes">Yes</option>
                                    <option value="No" selected>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this household record?</p>
                    <p class="fw-bold" id="deleteItemName"></p>
                    <p class="text-danger">This action cannot be undone.</p>
                    <input type="hidden" id="deleteItemId">
                    <input type="hidden" id="deleteItemType">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals
            const addHouseholdModal = new bootstrap.Modal(document.getElementById('addHouseholdModal'));
            const viewHouseholdModal = new bootstrap.Modal(document.getElementById('viewHouseholdModal'));
            const editHouseholdModal = new bootstrap.Modal(document.getElementById('editHouseholdModal'));
            const addMemberModal = new bootstrap.Modal(document.getElementById('addMemberModal'));
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            
            // Sample data - in a real app, this would come from your backend
            const sampleHouseholds = [
                {
                    id: 1,
                    household_number: 'BL-2023-0456',
                    head_of_family: 'Juan Dela Cruz',
                    purok: '2',
                    address: '123 Balas Street',
                    members: 4,
                    house_type: 'Single-detached',
                    status: 'Active',
                    ownership: 'Owned',
                    year_built: '2010',
                    water_source: 'Level III (Piped)',
                    electricity: 'With Meter',
                    internet: 'DSL',
                    toilet_facility: 'Water-sealed',
                    waste_disposal: 'Garbage Collection',
                    vehicle: 'Motorcycle, Car',
                    members_details: [
                        {
                            name: 'Juan Dela Cruz',
                            relationship: 'Head',
                            age: 38,
                            gender: 'Male',
                            civil_status: 'Married',
                            occupation: 'Teacher',
                            education: 'College Graduate',
                            voter: 'Yes'
                        },
                        {
                            name: 'Maria Dela Cruz',
                            relationship: 'Spouse',
                            age: 35,
                            gender: 'Female',
                            civil_status: 'Married',
                            occupation: 'Nurse',
                            education: 'College Graduate',
                            voter: 'Yes'
                        },
                        {
                            name: 'Pedro Dela Cruz',
                            relationship: 'Son',
                            age: 12,
                            gender: 'Male',
                            civil_status: 'Single',
                            occupation: 'Student',
                            education: 'Elementary',
                            voter: 'No'
                        },
                        {
                            name: 'Juanita Dela Cruz',
                            relationship: 'Daughter',
                            age: 7,
                            gender: 'Female',
                            civil_status: 'Single',
                            occupation: 'Student',
                            education: 'Elementary',
                            voter: 'No'
                        }
                    ],
                    livelihood: [
                        'Teaching (Public School)',
                        'Nursing (Private Hospital)',
                        'Sari-sari Store'
                    ],
                    government_assistance: [
                        '4Ps Beneficiary (2018-2022)',
                        'TUPAD (2021)',
                        'DSWD Educational Assistance (2022)'
                    ]
                },
                {
                    id: 2,
                    household_number: 'BL-2023-0457',
                    head_of_family: 'Maria Santos',
                    purok: '1',
                    address: '456 Mangga Street',
                    members: 3,
                    house_type: 'Duplex',
                    status: 'Active',
                    ownership: 'Rented',
                    year_built: '2015',
                    water_source: 'Level II (Deep Well)',
                    electricity: 'With Meter',
                    internet: 'None',
                    toilet_facility: 'Water-sealed',
                    waste_disposal: 'Garbage Collection',
                    vehicle: 'Motorcycle',
                    members_details: [
                        {
                            name: 'Maria Santos',
                            relationship: 'Head',
                            age: 42,
                            gender: 'Female',
                            civil_status: 'Widowed',
                            occupation: 'Vendor',
                            education: 'High School',
                            voter: 'Yes'
                        },
                        {
                            name: 'Luis Santos',
                            relationship: 'Son',
                            age: 18,
                            gender: 'Male',
                            civil_status: 'Single',
                            occupation: 'Student',
                            education: 'High School',
                            voter: 'Yes'
                        },
                        {
                            name: 'Ana Santos',
                            relationship: 'Daughter',
                            age: 15,
                            gender: 'Female',
                            civil_status: 'Single',
                            occupation: 'Student',
                            education: 'High School',
                            voter: 'No'
                        }
                    ],
                    livelihood: [
                        'Street Vendor',
                        'Laundry Service'
                    ],
                    government_assistance: [
                        '4Ps Beneficiary (2020-present)'
                    ]
                }
            ];
            
            let currentPage = 1;
            let rowsPerPage = 25;
            let filteredHouseholds = [...sampleHouseholds];
            
            // Initialize the table
            function initTable() {
                updateTable();
                updatePagination();
                document.getElementById('totalHouseholds').textContent = sampleHouseholds.length;
                document.getElementById('totalRecords').textContent = sampleHouseholds.length;
            }
            
            // Update the table with current data
            function updateTable() {
                const startIdx = (currentPage - 1) * rowsPerPage;
                const endIdx = startIdx + rowsPerPage;
                const paginatedData = filteredHouseholds.slice(startIdx, endIdx);
                
                const tableBody = document.getElementById('householdsTableBody');
                tableBody.innerHTML = '';
                
                paginatedData.forEach((household, index) => {
                    const rowIdx = startIdx + index + 1;
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>${rowIdx}</td>
                        <td>
                            <span class="fw-bold">${household.household_number}</span>
                            <div class="text-muted small">${household.address}</div>
                        </td>
                        <td>${household.head_of_family}</td>
                        <td>Purok ${household.purok}</td>
                        <td>${household.members}</td>
                        <td>${household.house_type}</td>
                        <td><span class="badge ${getStatusBadgeClass(household.status)}">${household.status}</span></td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary view-btn" data-id="${household.id}" data-bs-toggle="tooltip" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning edit-btn" data-id="${household.id}" data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${household.id}" data-bs-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    
                    tableBody.appendChild(row);
                });
                
                // Update showing counts
                document.getElementById('showingFrom').textContent = startIdx + 1;
                document.getElementById('showingTo').textContent = Math.min(startIdx + paginatedData.length, filteredHouseholds.length);
                
                // Add event listeners to buttons
                addButtonEventListeners();
            }
            
            // Get badge class based on status
            function getStatusBadgeClass(status) {
                switch(status) {
                    case 'Active': return 'bg-success';
                    case 'Inactive': return 'bg-secondary';
                    case 'Incomplete': return 'bg-warning text-dark';
                    default: return 'bg-primary';
                }
            }
            
            // Add event listeners to table buttons
            function addButtonEventListeners() {
                // View buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        viewHousehold(id);
                    });
                });
                
                // Edit buttons
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        editHousehold(id);
                    });
                });
                
                // Delete buttons
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        const household = sampleHouseholds.find(h => h.id === id);
                        showDeleteConfirmation(id, 'household', household.household_number);
                    });
                });
            }
            
            // View household details
            function viewHousehold(id) {
                const household = sampleHouseholds.find(h => h.id === id);
                if (!household) return;
                
                // Set basic info
                document.getElementById('viewHouseholdNumber').textContent = household.household_number;
                document.getElementById('viewPurok').textContent = household.purok;
                document.getElementById('viewAddress').textContent = household.address;
                document.getElementById('viewHouseType').textContent = household.house_type;
                document.getElementById('viewOwnership').textContent = household.ownership;
                document.getElementById('viewYearBuilt').textContent = household.year_built;
                document.getElementById('viewWaterSource').textContent = household.water_source;
                document.getElementById('viewElectricity').textContent = household.electricity;
                document.getElementById('viewInternet').textContent = household.internet || 'None';
                document.getElementById('viewToiletFacility').textContent = household.toilet_facility;
                document.getElementById('viewWasteDisposal').textContent = household.waste_disposal;
                document.getElementById('viewVehicle').textContent = household.vehicle || 'None';
                
                // Set members table
                const membersTableBody = document.getElementById('membersTableBody');
                membersTableBody.innerHTML = '';
                
                household.members_details.forEach(member => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${member.name}</td>
                        <td>${member.relationship}</td>
                        <td>${member.age}</td>
                        <td>${member.gender}</td>
                        <td>${member.civil_status}</td>
                        <td>${member.occupation}</td>
                        <td>${member.education}</td>
                        <td>${member.voter}</td>
                    `;
                    membersTableBody.appendChild(row);
                });
                
                // Set livelihood list
                const livelihoodList = document.getElementById('livelihoodList');
                livelihoodList.innerHTML = '';
                
                household.livelihood.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item py-1 px-0 border-0';
                    li.textContent = item;
                    livelihoodList.appendChild(li);
                });
                
                // Set assistance list
                const assistanceList = document.getElementById('assistanceList');
                assistanceList.innerHTML = '';
                
                household.government_assistance.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item py-1 px-0 border-0';
                    li.textContent = item;
                    assistanceList.appendChild(li);
                });
                
                // Set edit button to pass the household ID
                document.getElementById('editHouseholdBtn').setAttribute('data-id', id);
                
                // Show the modal
                viewHouseholdModal.show();
            }
            
            // Edit household
            function editHousehold(id) {
                const household = sampleHouseholds.find(h => h.id === id);
                if (!household) return;
                
                // Set basic info
                document.getElementById('editHouseholdId').value = household.id;
                document.getElementById('editHouseholdNumber').value = household.household_number;
                document.getElementById('editPurok').value = household.purok;
                document.getElementById('editAddress').value = household.address;
                document.getElementById('editHouseType').value = household.house_type;
                document.getElementById('editOwnership').value = household.ownership;
                document.getElementById('editWaterSource').value = household.water_source.split(' ')[0]; // Gets "Level" part
                document.getElementById('editElectricity').value = household.electricity;
                document.getElementById('editStatus').value = household.status;
                
                // Set members table
                const membersTableBody = document.getElementById('editMembersTableBody');
                membersTableBody.innerHTML = '';
                
                household.members_details.forEach((member, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${member.name}</td>
                        <td>${member.relationship}</td>
                        <td>${member.age}</td>
                        <td>${member.gender}</td>
                        <td>${member.civil_status}</td>
                        <td>${member.occupation}</td>
                        <td>${member.education}</td>
                        <td>${member.voter}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger delete-member-btn" data-id="${index}" data-bs-toggle="tooltip" title="Delete Member">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    membersTableBody.appendChild(row);
                });
                
                // Set livelihood items
                const livelihoodItems = document.getElementById('livelihoodItems');
                livelihoodItems.innerHTML = '';
                
                household.livelihood.forEach((item, index) => {
                    const div = document.createElement('div');
                    div.className = 'd-flex mb-2';
                    div.innerHTML = `
                        <input type="text" class="form-control form-control-sm me-2" value="${item}">
                        <button class="btn btn-sm btn-outline-danger delete-livelihood-btn" data-id="${index}" data-bs-toggle="tooltip" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    livelihoodItems.appendChild(div);
                });
                
                // Set assistance items
                const assistanceItems = document.getElementById('assistanceItems');
                assistanceItems.innerHTML = '';
                
                household.government_assistance.forEach((item, index) => {
                    const div = document.createElement('div');
                    div.className = 'd-flex mb-2';
                    div.innerHTML = `
                        <input type="text" class="form-control form-control-sm me-2" value="${item}">
                        <button class="btn btn-sm btn-outline-danger delete-assistance-btn" data-id="${index}" data-bs-toggle="tooltip" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    assistanceItems.appendChild(div);
                });
                
                // Show the modal
                editHouseholdModal.show();
            }
            
            // Show delete confirmation
            function showDeleteConfirmation(id, type, name) {
                document.getElementById('deleteItemId').value = id;
                document.getElementById('deleteItemType').value = type;
                document.getElementById('deleteItemName').textContent = name;
                confirmDeleteModal.show();
            }
            
            // Update pagination
            function updatePagination() {
                const totalPages = Math.ceil(filteredHouseholds.length / rowsPerPage);
                const pagination = document.getElementById('pagination');
                
                // Clear existing pagination except prev/next
                const prevItem = document.getElementById('prevPage');
                const nextItem = document.getElementById('nextPage');
                pagination.innerHTML = '';
                pagination.appendChild(prevItem);
                
                // Add page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    
                    li.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentPage = i;
                        updateTable();
                        updatePagination();
                    });
                    
                    pagination.insertBefore(li, nextItem);
                }
                
                // Add next button
                pagination.appendChild(nextItem);
                
                // Update prev/next button states
                prevItem.classList.toggle('disabled', currentPage === 1);
                nextItem.classList.toggle('disabled', currentPage === totalPages);
                
                // Add event listeners to prev/next buttons
                prevItem.addEventListener('click', function(e) {
                    if (currentPage > 1) {
                        currentPage--;
                        updateTable();
                        updatePagination();
                    }
                });
                
                nextItem.addEventListener('click', function(e) {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTable();
                        updatePagination();
                    }
                });
            }
            
            // Initialize tooltips
            function initTooltips() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
            
            // Event listeners for modal buttons
            document.getElementById('editHouseholdBtn').addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                viewHouseholdModal.hide();
                editHousehold(id);
            });
            
            document.getElementById('addMemberBtn').addEventListener('click', function() {
                addMemberModal.show();
            });
            
            document.getElementById('addLivelihoodBtn').addEventListener('click', function() {
                const livelihoodItems = document.getElementById('livelihoodItems');
                const div = document.createElement('div');
                div.className = 'd-flex mb-2';
                div.innerHTML = `
                    <input type="text" class="form-control form-control-sm me-2" placeholder="Enter livelihood">
                    <button class="btn btn-sm btn-outline-danger delete-livelihood-btn" data-bs-toggle="tooltip" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                livelihoodItems.appendChild(div);
                initTooltips();
            });
            
            document.getElementById('addAssistanceBtn').addEventListener('click', function() {
                const assistanceItems = document.getElementById('assistanceItems');
                const div = document.createElement('div');
                div.className = 'd-flex mb-2';
                div.innerHTML = `
                    <input type="text" class="form-control form-control-sm me-2" placeholder="Enter assistance">
                    <button class="btn btn-sm btn-outline-danger delete-assistance-btn" data-bs-toggle="tooltip" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                assistanceItems.appendChild(div);
                initTooltips();
            });
            
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                const id = parseInt(document.getElementById('deleteItemId').value);
                const type = document.getElementById('deleteItemType').value;
                
                if (type === 'household') {
                    // In a real app, you would make an API call to delete the household
                    const index = sampleHouseholds.findIndex(h => h.id === id);
                    if (index !== -1) {
                        sampleHouseholds.splice(index, 1);
                        showAlert('Household deleted successfully!', 'success');
                        initTable();
                    }
                }
                
                confirmDeleteModal.hide();
            });
            
            // Form submissions
            document.getElementById('addHouseholdForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real app, you would make an API call to add the household
                const newId = sampleHouseholds.length > 0 ? Math.max(...sampleHouseholds.map(h => h.id)) + 1 : 1;
                
                const newHousehold = {
                    id: newId,
                    household_number: document.getElementById('householdNumber').value,
                    head_of_family: '', // Would be set when adding members
                    purok: document.getElementById('purok').value,
                    address: document.getElementById('address').value,
                    members: 0,
                    house_type: document.getElementById('houseType').value,
                    status: 'Active',
                    ownership: document.getElementById('ownership').value,
                    year_built: new Date().getFullYear().toString(),
                    water_source: document.getElementById('waterSource').value,
                    electricity: document.getElementById('electricity').value,
                    internet: 'None',
                    toilet_facility: 'Water-sealed',
                    waste_disposal: 'Garbage Collection',
                    vehicle: 'None',
                    members_details: [],
                    livelihood: [],
                    government_assistance: []
                };
                
                sampleHouseholds.push(newHousehold);
                addHouseholdModal.hide();
                showAlert('Household added successfully!', 'success');
                initTable();
            });
            
            document.getElementById('editHouseholdForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = parseInt(document.getElementById('editHouseholdId').value);
                const household = sampleHouseholds.find(h => h.id === id);
                if (!household) return;
                
                // Update household info
                household.purok = document.getElementById('editPurok').value;
                household.address = document.getElementById('editAddress').value;
                household.house_type = document.getElementById('editHouseType').value;
                household.ownership = document.getElementById('editOwnership').value;
                household.water_source = document.getElementById('editWaterSource').value;
                household.electricity = document.getElementById('editElectricity').value;
                household.status = document.getElementById('editStatus').value;
                
                // In a real app, you would also update members, livelihood, and assistance
                
                editHouseholdModal.hide();
                showAlert('Household updated successfully!', 'success');
                initTable();
            });
            
            document.getElementById('addMemberForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real app, you would add this member to the household
                const newMember = {
                    name: `${document.getElementById('memberFirstName').value} ${document.getElementById('memberMiddleName').value ? document.getElementById('memberMiddleName').value + ' ' : ''}${document.getElementById('memberLastName').value}`,
                    relationship: document.getElementById('memberRelationship').value,
                    age: document.getElementById('memberAge').value,
                    gender: document.getElementById('memberGender').value,
                    civil_status: document.getElementById('memberCivilStatus').value,
                    occupation: document.getElementById('memberOccupation').value,
                    education: document.getElementById('memberEducation').value,
                    voter: document.getElementById('memberVoter').value
                };
                
                // For demo purposes, we'll add to the first household
                if (sampleHouseholds.length > 0) {
                    sampleHouseholds[0].members_details.push(newMember);
                    sampleHouseholds[0].members = sampleHouseholds[0].members_details.length;
                    
                    if (newMember.relationship === 'Head') {
                        sampleHouseholds[0].head_of_family = newMember.name;
                    }
                }
                
                addMemberModal.hide();
                showAlert('Member added successfully!', 'success');
                initTable();
            });
            
            // Filter form submission
            document.getElementById('filterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const filters = Object.fromEntries(formData.entries());
                
                // Apply filters
                filteredHouseholds = sampleHouseholds.filter(household => {
                    return (
                        (!filters.purok || household.purok === filters.purok) &&
                        (!filters.house_type || household.house_type === filters.house_type) &&
                        (!filters.water_source || household.water_source.includes(filters.water_source)) &&
                        (!filters.status || household.status === filters.status)
                    );
                });
                
                currentPage = 1;
                initTable();
            });
            
            // Reset filters
            document.getElementById('filterForm').addEventListener('reset', function() {
                filteredHouseholds = [...sampleHouseholds];
                currentPage = 1;
                initTable();
            });
            
            // Rows per page change
            document.getElementById('rowsPerPage').addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                initTable();
            });
            
            // Search functionality
            document.getElementById('searchButton').addEventListener('click', function() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                
                if (!searchTerm) {
                    filteredHouseholds = [...sampleHouseholds];
                } else {
                    filteredHouseholds = sampleHouseholds.filter(household => {
                        return (
                            household.household_number.toLowerCase().includes(searchTerm) ||
                            household.head_of_family.toLowerCase().includes(searchTerm) ||
                            household.address.toLowerCase().includes(searchTerm)
                        );
                    });
                }
                
                currentPage = 1;
                initTable();
            });
            
            // Show alert
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
            
            // Initialize the page
            initTable();
            initTooltips();
        });
    </script>
</body>
</html>