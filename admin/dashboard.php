<?php 
require_once __DIR__ . '/includes/auth.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Barangay Balas Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="sb-nav-fixed sb-sidenav-toggled">
    <?php include 'includes/navbar.php'; ?>
    
    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    
                    <div class="row">
                        <!-- Statistics Cards -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Total Residents</h6>
                                            <h3 class="mb-0">1,254</h3>
                                        </div>
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="residents.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Pending Requests</h6>
                                            <h3 class="mb-0">24</h3>
                                        </div>
                                        <i class="fas fa-file-alt fa-2x"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="document-requests.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Approved Today</h6>
                                            <h3 class="mb-0">12</h3>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="document-requests.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">New Announcements</h6>
                                            <h3 class="mb-0">5</h3>
                                        </div>
                                        <i class="fas fa-bullhorn fa-2x"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="announcements.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Recent Document Requests
                                </div>
                                <div class="card-body">
                                    <canvas id="requestsChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-pie me-1"></i>
                                    Resident Registration Status
                                </div>
                                <div class="card-body">
                                    <canvas id="residentsChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Recent Activities
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>Activity</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2023-06-15 14:30</td>
                                        <td>Approved Brgy. Clearance request for Juan Dela Cruz</td>
                                        <td>Admin User</td>
                                    </tr>
                                    <tr>
                                        <td>2023-06-15 13:45</td>
                                        <td>Added new announcement: "Community Meeting"</td>
                                        <td>Admin User</td>
                                    </tr>
                                    <tr>
                                        <td>2023-06-15 11:20</td>
                                        <td>Registered new resident: Maria Santos</td>
                                        <td>Admin User</td>
                                    </tr>
                                    <tr>
                                        <td>2023-06-15 10:15</td>
                                        <td>Updated profile information</td>
                                        <td>Admin User</td>
                                    </tr>
                                    <tr>
                                        <td>2023-06-15 09:30</td>
                                        <td>Logged in to the system</td>
                                        <td>Admin User</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        // Sample charts for dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Requests Chart
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            const requestsChart = new Chart(requestsCtx, {
                type: 'bar',
                data: {
                    labels: ['Brgy. Clearance', 'Business Permit', 'Indigency', 'Residency', 'Cedula'],
                    datasets: [{
                        label: 'Requests This Week',
                        data: [12, 8, 5, 7, 10],
                        backgroundColor: [
                            '#E63946',
                            '#1D3557',
                            '#FFD166',
                            '#A8DADC',
                            '#457B9D'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Residents Chart
            const residentsCtx = document.getElementById('residentsChart').getContext('2d');
            const residentsChart = new Chart(residentsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending', 'Rejected'],
                    datasets: [{
                        data: [856, 124, 32],
                        backgroundColor: [
                            '#1D3557',
                            '#FFD166',
                            '#E63946'
                        ],
                        hoverBackgroundColor: [
                            '#457B9D',
                            '#F4A261',
                            '#C1121F'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>