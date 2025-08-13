<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Balas Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-blue:  #0033cc;
            --secondary-blue: #3a7cb9;
            --accent-red: #e63946;
            --accent-yellow: #ffbe0b;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .sidebar-menu li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu li.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid var(--accent-yellow);
        }
        
        .sidebar-menu li a {
            color: white;
            text-decoration: none;
            display: block;
        }
        
        .sidebar-menu li i {
            margin-right: 10px;
            color: var(--accent-yellow);
        }
        
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        
        .top-navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
        }
        
        .content-area {
            padding: 20px;
            min-height: calc(100vh - 70px);
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
            padding: 15px 20px;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-red);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .btn-warning {
            background-color: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #333;
        }
        
        .btn-danger {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
        }
        
        .badge-primary {
            background-color: var(--primary-blue);
        }
        
        .badge-warning {
            background-color: var(--accent-yellow);
            color: #333;
        }
        
        .badge-danger {
            background-color: var(--accent-red);
        }
        
        .sidebar-collapsed .sidebar {
            width: 80px;
            overflow: hidden;
        }
        
        .sidebar-collapsed .sidebar .sidebar-text {
            display: none;
        }
        
        .sidebar-collapsed .main-content {
            margin-left: 80px;
        }
        
        .sidebar-collapsed .sidebar-header h3 {
            display: none;
        }
        
        .sidebar-collapsed .sidebar-menu li {
            text-align: center;
        }
        
        .sidebar-collapsed .sidebar-menu li i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        .document-request-card {
            border-left: 4px solid var(--primary-blue);
        }
        
        .announcement-card {
            border-left: 4px solid var(--accent-yellow);
        }
        
        .alert-card {
            border-left: 4px solid var(--accent-red);
        }
        
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-blue);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar .sidebar-text {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .sidebar-header h3 {
                display: none;
            }
            
            .sidebar-menu li {
                text-align: center;
            }
            
            .sidebar-menu li i {
                margin-right: 0;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'?>

        <!-- Main Content -->
        <div class="main-content">
          <?php include 'includes/navbar.php'?>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Dashboard Section -->
                <section id="dashboard">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Dashboard</h2>
                        <div>
                            <span class="badge bg-light text-dark"><i class="fas fa-calendar-alt me-2"></i>July 9, 2023</span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card document-request-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Document Requests</span>
                                    <span class="badge bg-primary">2 New</span>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between">
                                                <div>Barangay Clearance</div>
                                                <small class="text-success">Ready for pickup</small>
                                            </div>
                                            <small class="text-muted">Requested: July 5, 2023</small>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between">
                                                <div>Certificate of Residency</div>
                                                <small class="text-warning">Pending</small>
                                            </div>
                                            <small class="text-muted">Requested: July 8, 2023</small>
                                        </a>
                                    </div>
                                    <a href="#documents" class="btn btn-sm btn-primary mt-3">View All Requests</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card announcement-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Announcements</span>
                                    <span class="badge bg-warning text-dark">1 New</span>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <h6 class="mb-1">Barangay Assembly Meeting</h6>
                                            <small class="text-muted">Posted: July 8, 2023</small>
                                            <p class="mb-1 mt-1">All residents are invited to attend the barangay assembly meeting on July 15, 2023 at 9:00 AM at the barangay hall.</p>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <h6 class="mb-1">Clean-up Drive</h6>
                                            <small class="text-muted">Posted: July 1, 2023</small>
                                            <p class="mb-1 mt-1">Join us for a community clean-up drive on July 10, 2023. Meet at the barangay hall at 7:00 AM.</p>
                                        </a>
                                    </div>
                                    <a href="#announcements" class="btn btn-sm btn-warning mt-3">View All Announcements</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card alert-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Alerts</span>
                                    <span class="badge bg-danger">1 Action Required</span>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex">
                                                <i class="fas fa-exclamation-circle text-danger me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Document Verification Needed</h6>
                                                    <small class="text-muted">Your request for Certificate of Residency requires additional documents.</small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex">
                                                <i class="fas fa-info-circle text-primary me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Profile Update</h6>
                                                    <small class="text-muted">Please verify your contact information.</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <span>Recent Activities</span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Activity</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>July 8, 2023</td>
                                                    <td>Certificate of Residency Request</td>
                                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>July 5, 2023</td>
                                                    <td>Barangay Clearance Request</td>
                                                    <td><span class="badge bg-success">Completed</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>June 28, 2023</td>
                                                    <td>Business Permit Application</td>
                                                    <td><span class="badge bg-success">Completed</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>June 15, 2023</td>
                                                    <td>Barangay ID Renewal</td>
                                                    <td><span class="badge bg-success">Completed</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <span>Quick Actions</span>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary">
                                            <i class="fas fa-file-alt me-2"></i> Request Document
                                        </button>
                                        <button class="btn btn-warning">
                                            <i class="fas fa-calendar-alt me-2"></i> View Events
                                        </button>
                                        <button class="btn btn-danger">
                                            <i class="fas fa-exclamation-triangle me-2"></i> Report Issue
                                        </button>
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-user-edit me-2"></i> Update Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <?php include 'documents.php';  ?>
                <?php include 'announcements.php';  ?>                 
                <?php include 'profile.php';  ?>
                <?php include 'requestsHistory.php';  ?>
                <?php include 'census.php';  ?>
                
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.wrapper').classList.toggle('sidebar-collapsed');
        });
        
        // Simple navigation between sections
        document.querySelectorAll('.sidebar-menu li a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Hide all sections
                document.querySelectorAll('section').forEach(section => {
                    section.classList.add('d-none');
                });
                
                // Show the selected section
                const target = this.getAttribute('href').substring(1);
                document.getElementById(target).classList.remove('d-none');
                
                // Update active menu item
                document.querySelectorAll('.sidebar-menu li').forEach(item => {
                    item.classList.remove('active');
                });
                this.parentElement.classList.add('active');
            });
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>
</html>