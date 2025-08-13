<?php 
require_once __DIR__ . '/includes/auth.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Requests | Barangay Balas Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="sb-nav-fixed">
    <?php include 'includes/navbar.php'; ?>
    
    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Document Requests</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Document Requests</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Manage Document Requests
                                </div>
                                <div>
                                    <button class="btn btn-success btn-sm me-2" onclick="exportRequests('all')">
                                        <i class="fas fa-file-excel me-1"></i> Export All
                                    </button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-1"></i> Export By Type
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="exportRequests('clearance')">Barangay Clearance</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportRequests('business')">Business Permit</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportRequests('indigency')">Certificate of Indigency</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportRequests('residency')">Certificate of Residency</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportRequests('cedula')">Community Tax Certificate (Cedula)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-4" id="requestsTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">Pending <span class="badge bg-warning ms-1">5</span></button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">Approved <span class="badge bg-success ms-1">12</span></button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="disapproved-tab" data-bs-toggle="tab" data-bs-target="#disapproved" type="button" role="tab">Disapproved <span class="badge bg-danger ms-1">3</span></button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="requestsTabContent">
                                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Queue #</th>
                                                    <th>Request ID</th>
                                                    <th>Resident</th>
                                                    <th>Document Type</th>
                                                    <th>Date Requested</th>
                                                    <th>Purpose</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>BRGY-2023-015</td>
                                                    <td>REQ-001</td>
                                                    <td>Juan Dela Cruz</td>
                                                    <td>Barangay Clearance</td>
                                                    <td>2023-06-15</td>
                                                    <td>Employment</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#approveRequestModal">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#disapproveRequestModal">
                                                            <i class="fas fa-times"></i> Disapprove
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>BRGY-2023-016</td>
                                                    <td>REQ-002</td>
                                                    <td>Maria Santos</td>
                                                    <td>Certificate of Residency</td>
                                                    <td>2023-06-15</td>
                                                    <td>School Requirement</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#approveRequestModal">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#disapproveRequestModal">
                                                            <i class="fas fa-times"></i> Disapprove
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="approved" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Queue #</th>
                                                    <th>Request ID</th>
                                                    <th>Resident</th>
                                                    <th>Document Type</th>
                                                    <th>Date Requested</th>
                                                    <th>Date Approved</th>
                                                    <th>Approved By</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>BRGY-2023-001</td>
                                                    <td>REQ-101</td>
                                                    <td>Pedro Reyes</td>
                                                    <td>Barangay Clearance</td>
                                                    <td>2023-06-10</td>
                                                    <td>2023-06-12</td>
                                                    <td>Admin User</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRequestModal">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="disapproved" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Queue #</th>
                                                    <th>Request ID</th>
                                                    <th>Resident</th>
                                                    <th>Document Type</th>
                                                    <th>Date Requested</th>
                                                    <th>Date Disapproved</th>
                                                    <th>Reason</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>BRGY-2023-005</td>
                                                    <td>REQ-201</td>
                                                    <td>Luis Garcia</td>
                                                    <td>Business Permit</td>
                                                    <td>2023-06-08</td>
                                                    <td>2023-06-09</td>
                                                    <td>Incomplete requirements</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRequestModal">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
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
 <?php include 'modals/documentsModal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        function exportRequests(type) {
            // In a real implementation, this would trigger a server-side export to Excel
            if (type === 'all') {
                alert("Exporting all document requests to Excel...");
            } else {
                alert(`Exporting ${type} requests to Excel...`);
            }
        }
        
        // Show/hide other reason textarea based on selection
        document.getElementById('disapprovalReason').addEventListener('change', function() {
            const otherReasonContainer = document.getElementById('otherReasonContainer');
            if (this.value === 'Other') {
                otherReasonContainer.style.display = 'block';
            } else {
                otherReasonContainer.style.display = 'none';
            }
        });
    </script>
</body>
</html>