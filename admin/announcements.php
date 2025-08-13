<?php 
require_once __DIR__ . '/includes/auth.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements | Barangay Balas Admin</title>
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
                    <h1 class="mt-4">Announcements</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Announcements</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Manage Announcements
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                    <i class="fas fa-plus me-1"></i> Add Announcement
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="announcementsTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Image</th>
                                        <th>Date Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Community Meeting</td>
                                        <td>Monthly community meeting on June 20, 2023 at the barangay hall.</td>
                                        <td><img src="assets/meeting.jpg" width="50" class="img-thumbnail"></td>
                                        <td>2023-06-15</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAnnouncementModal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Clean-up Drive</td>
                                        <td>Join us for a barangay clean-up drive this Saturday.</td>
                                        <td><img src="assets/cleanup.jpg" width="50" class="img-thumbnail"></td>
                                        <td>2023-06-10</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAnnouncementModal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
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
     <?php include 'modals/announcementsModal.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>