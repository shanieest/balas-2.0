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
        <div class="sidebar">
            <div class="sidebar-header d-flex justify-content-between align-items-center">
                <h3 class="m-0">Barangay Balas</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="#dashboard">
                        <i class="fas fa-home"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#documents">
                        <i class="fas fa-file-alt"></i>
                        <span class="sidebar-text">Request Documents</span>
                    </a>
                </li>
                <li>
                    <a href="#announcements">
                        <i class="fas fa-bullhorn"></i>
                        <span class="sidebar-text">Announcements</span>
                    </a>
                </li>
                <li>
                    <a href="#profile">
                        <i class="fas fa-user"></i>
                        <span class="sidebar-text">My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="#history">
                        <i class="fas fa-history"></i>
                        <span class="sidebar-text">Request History</span>
                    </a>
                </li>
                <li>
                    <a href="#census">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Census Data</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="top-navbar navbar navbar-expand-lg navbar-light bg-white">
                <div class="container-fluid">
                    <button class="btn btn-link" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center ms-auto">
                        <div class="dropdown me-3">
                            <button class="btn btn-link position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="notification-badge">3</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
                                <li>
                                    <h6 class="dropdown-header">Notifications</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <i class="fas fa-file-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <div>Your Barangay Clearance is ready for pickup</div>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <i class="fas fa-bullhorn text-warning"></i>
                                        </div>
                                        <div>
                                            <div>New announcement: Barangay Meeting</div>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        </div>
                                        <div>
                                            <div>Your document request needs additional information</div>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-center text-primary" href="#">View All Notifications</a>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle d-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle me-2">
                                <span>Juan Dela Cruz</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="#profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#settings"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

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
                
                <!-- Request Documents Section (Hidden by default) -->
                <section id="documents" class="d-none">
                    <h2 class="mb-4">Request Documents</h2>
                    <div class="card">
                        <div class="card-header">
                            <span>Available Documents</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-certificate fa-3x text-primary mb-3"></i>
                                            <h5>Barangay Clearance</h5>
                                            <p class="text-muted">Required for various transactions</p>
                                            <button class="btn btn-primary btn-sm">Request</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-home fa-3x text-primary mb-3"></i>
                                            <h5>Certificate of Residency</h5>
                                            <p class="text-muted">Proof of residency in Barangay Balas</p>
                                            <button class="btn btn-primary btn-sm">Request</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-id-card fa-3x text-primary mb-3"></i>
                                            <h5>Barangay ID</h5>
                                            <p class="text-muted">Identification card for residents</p>
                                            <button class="btn btn-primary btn-sm">Request</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                                            <h5>Business Permit</h5>
                                            <p class="text-muted">Required for operating businesses</p>
                                            <button class="btn btn-primary btn-sm">Request</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-handshake fa-3x text-primary mb-3"></i>
                                            <h5>Certificate of Good Moral</h5>
                                            <p class="text-muted">Character certification</p>
                                            <button class="btn btn-primary btn-sm">Request</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Announcements Section (Hidden by default) -->
                <section id="announcements" class="d-none">
                    <h2 class="mb-4">Announcements</h2>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Latest Announcements</span>
                            <button class="btn btn-sm btn-warning">View Archive</button>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Barangay Assembly Meeting</h5>
                                        <small class="text-muted">July 8, 2023</small>
                                    </div>
                                    <p class="mb-1">All residents are invited to attend the barangay assembly meeting on July 15, 2023 at 9:00 AM at the barangay hall.</p>
                                    <small>Posted by: Kapitan Juan Dela Cruz</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Clean-up Drive</h5>
                                        <small class="text-muted">July 1, 2023</small>
                                    </div>
                                    <p class="mb-1">Join us for a community clean-up drive on July 10, 2023. Meet at the barangay hall at 7:00 AM. Gloves and garbage bags will be provided.</p>
                                    <small>Posted by: Kagawad Maria Santos</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Vaccination Schedule</h5>
                                        <small class="text-muted">June 25, 2023</small>
                                    </div>
                                    <p class="mb-1">COVID-19 booster shots will be available at the barangay health center every Wednesday from 8:00 AM to 3:00 PM starting July 5.</p>
                                    <small>Posted by: Barangay Health Worker</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Water Interruption</h5>
                                        <small class="text-muted">June 20, 2023</small>
                                    </div>
                                    <p class="mb-1">There will be a water service interruption on June 22 from 8:00 AM to 5:00 PM for pipeline maintenance. Please store water accordingly.</p>
                                    <small>Posted by: Barangay Secretary</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Profile Section (Hidden by default) -->
                <section id="profile" class="d-none">
                    <h2 class="mb-4">My Profile</h2>
                    <div class="card">
                        <div class="card-header">
                            <span>Personal Information</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img src="https://via.placeholder.com/150" alt="Profile" class="profile-img mb-3">
                                    <button class="btn btn-sm btn-outline-primary">Change Photo</button>
                                </div>
                                <div class="col-md-9">
                                    <form>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" value="Juan">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" value="Protacio">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" value="Dela Cruz">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Birthdate</label>
                                                <input type="date" class="form-control" value="1985-06-12">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Gender</label>
                                                <select class="form-select">
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Civil Status</label>
                                                <select class="form-select">
                                                    <option>Single</option>
                                                    <option selected>Married</option>
                                                    <option>Widowed</option>
                                                    <option>Separated</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Blood Type</label>
                                                <select class="form-select">
                                                    <option>A+</option>
                                                    <option>A-</option>
                                                    <option>B+</option>
                                                    <option>B-</option>
                                                    <option selected>O+</option>
                                                    <option>O-</option>
                                                    <option>AB+</option>
                                                    <option>AB-</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" value="juan.delacruz@example.com">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="tel" class="form-control" value="09123456789">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" value="123 Balas Street, Barangay Balas">
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Purok</label>
                                                <select class="form-select">
                                                    <option>1</option>
                                                    <option selected>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                    <option>7</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Voter Status</label>
                                                <select class="form-select">
                                                    <option selected>Registered Voter</option>
                                                    <option>Not Registered</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Occupation</label>
                                                <input type="text" class="form-control" value="Teacher">
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Family Members Section -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Family Members</span>
                            <button class="btn btn-sm btn-primary">Add Family Member</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Relationship</th>
                                            <th>Birthdate</th>
                                            <th>Civil Status</th>
                                            <th>Occupation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Maria Dela Cruz</td>
                                            <td>Spouse</td>
                                            <td>1988-03-15</td>
                                            <td>Married</td>
                                            <td>Nurse</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pedro Dela Cruz</td>
                                            <td>Son</td>
                                            <td>2010-11-22</td>
                                            <td>Single</td>
                                            <td>Student</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Juanita Dela Cruz</td>
                                            <td>Daughter</td>
                                            <td>2015-07-30</td>
                                            <td>Single</td>
                                            <td>Student</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Request History Section (Hidden by default) -->
                <section id="history" class="d-none">
                    <h2 class="mb-4">Request History</h2>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Document Requests</span>
                            <div>
                                <select class="form-select form-select-sm" style="width: 150px;">
                                    <option>All Status</option>
                                    <option>Pending</option>
                                    <option>Approved</option>
                                    <option>Completed</option>
                                    <option>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Request Date</th>
                                            <th>Document Type</th>
                                            <th>Purpose</th>
                                            <th>Status</th>
                                            <th>Action Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2023-07-08</td>
                                            <td>Certificate of Residency</td>
                                            <td>School Requirement</td>
                                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                                            <td>2023-07-08</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                                <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2023-07-05</td>
                                            <td>Barangay Clearance</td>
                                            <td>Business Permit</td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td>2023-07-07</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2023-06-28</td>
                                            <td>Business Permit</td>
                                            <td>Sari-sari Store</td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td>2023-06-30</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2023-06-15</td>
                                            <td>Barangay ID</td>
                                            <td>Renewal</td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td>2023-06-17</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2023-05-20</td>
                                            <td>Certificate of Good Moral</td>
                                            <td>Job Application</td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td>2023-05-22</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </section>
                
                <!-- Census Data Section (Hidden by default) -->
                <section id="census" class="d-none">
                    <h2 class="mb-4">Census Data</h2>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Household Information</span>
                            <button class="btn btn-sm btn-primary">Update Household</button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Household Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Household No:</strong> BL-2023-0456</p>
                                                    <p><strong>Purok:</strong> 2</p>
                                                    <p><strong>Address:</strong> 123 Balas Street</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>House Type:</strong> Single-detached</p>
                                                    <p><strong>Ownership:</strong> Owned</p>
                                                    <p><strong>Year Built:</strong> 2010</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Household Amenities</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Water Source:</strong> Level III (Piped)</p>
                                                    <p><strong>Electricity:</strong> With Meter</p>
                                                    <p><strong>Internet:</strong> DSL</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Toilet Facility:</strong> Water-sealed</p>
                                                    <p><strong>Waste Disposal:</strong> Garbage Collection</p>
                                                    <p><strong>Vehicle:</strong> Motorcycle, Car</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">Household Members</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Relationship to Head</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Civil Status</th>
                                            <th>Occupation</th>
                                            <th>Education</th>
                                            <th>Voter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Juan Dela Cruz</td>
                                            <td>Head</td>
                                            <td>38</td>
                                            <td>Male</td>
                                            <td>Married</td>
                                            <td>Teacher</td>
                                            <td>College Graduate</td>
                                            <td>Yes</td>
                                        </tr>
                                        <tr>
                                            <td>Maria Dela Cruz</td>
                                            <td>Spouse</td>
                                            <td>35</td>
                                            <td>Female</td>
                                            <td>Married</td>
                                            <td>Nurse</td>
                                            <td>College Graduate</td>
                                            <td>Yes</td>
                                        </tr>
                                        <tr>
                                            <td>Pedro Dela Cruz</td>
                                            <td>Son</td>
                                            <td>12</td>
                                            <td>Male</td>
                                            <td>Single</td>
                                            <td>Student</td>
                                            <td>Elementary</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>Juanita Dela Cruz</td>
                                            <td>Daughter</td>
                                            <td>7</td>
                                            <td>Female</td>
                                            <td>Single</td>
                                            <td>Student</td>
                                            <td>Elementary</td>
                                            <td>No</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Livelihood</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">Teaching (Public School)</li>
                                                <li class="list-group-item">Nursing (Private Hospital)</li>
                                                <li class="list-group-item">Sari-sari Store</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Government Assistance</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">4Ps Beneficiary (2018-2022)</li>
                                                <li class="list-group-item">TUPAD (2021)</li>
                                                <li class="list-group-item">DSWD Educational Assistance (2022)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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