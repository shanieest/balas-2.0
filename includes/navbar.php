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
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>