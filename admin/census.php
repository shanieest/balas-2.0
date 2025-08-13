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
 <?php include 'modals/censusModal.php'; ?>

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