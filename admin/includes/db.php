<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'brgy_balas');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Create tables if they don't exist
function initializeDatabase($conn) {
    $queries = [
        // Admin users table
        "CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            position VARCHAR(50) NOT NULL,
            contact_number VARCHAR(20),
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Residents table
        "CREATE TABLE IF NOT EXISTS residents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            middle_name VARCHAR(50),
            last_name VARCHAR(50) NOT NULL,
            suffix VARCHAR(10),
            sex ENUM('male', 'female') NOT NULL,
            civil_status VARCHAR(20) NOT NULL,
            birthdate DATE NOT NULL,
            age INT,
            address TEXT NOT NULL,
            contact_number VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            photo_path VARCHAR(255),
            valid_id_path VARCHAR(255),
            verification_status ENUM('Verified', 'Pending', 'Unverified') DEFAULT 'Unverified',
            resident_status ENUM('Active', 'Inactive', 'Deceased') DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Resident accounts table
        "CREATE TABLE IF NOT EXISTS resident_accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            resident_id INT NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            account_status ENUM('Approved', 'Pending', 'Disapproved') DEFAULT 'Pending',
            processed_by INT,
            date_processed DATETIME,
            notes TEXT,
            FOREIGN KEY (resident_id) REFERENCES residents(id) ON DELETE CASCADE,
            FOREIGN KEY (processed_by) REFERENCES admin_users(id) ON DELETE SET NULL
        )",
        
        // Barangay officials table
        "CREATE TABLE IF NOT EXISTS barangay_officials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            middle_name VARCHAR(50),
            last_name VARCHAR(50) NOT NULL,
            position VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            contact_number VARCHAR(20),
            status ENUM('Active', 'Inactive') DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Announcements table
        "CREATE TABLE IF NOT EXISTS announcements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image_path VARCHAR(255),
            posted_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (posted_by) REFERENCES admin_users(id) ON DELETE CASCADE
        )",
        
        // Document requests table
        "CREATE TABLE IF NOT EXISTS document_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            resident_id INT NOT NULL,
            document_type ENUM('Barangay Clearance', 'Business Permit', 'Certificate of Indigency', 'Certificate of Residency', 'Community Tax Certificate') NOT NULL,
            purpose TEXT NOT NULL,
            requirements_submitted TEXT,
            status ENUM('Pending', 'Approved', 'Disapproved') DEFAULT 'Pending',
            processed_by INT,
            date_requested DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_processed DATETIME,
            notes TEXT,
            FOREIGN KEY (resident_id) REFERENCES residents(id) ON DELETE CASCADE,
            FOREIGN KEY (processed_by) REFERENCES admin_users(id) ON DELETE SET NULL
        )",
        
        // Activity logs table
        "CREATE TABLE IF NOT EXISTS activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            activity TEXT NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE
        )"
    ];
    
    foreach ($queries as $query) {
        if (!$conn->query($query)) {
            die("Error creating table: " . $conn->error);
        }
    }
    
    // Insert default admin user if none exists
    $result = $conn->query("SELECT id FROM admin_users LIMIT 1");
    if ($result->num_rows == 0) {
        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO admin_users (username, password, first_name, last_name, email, position) 
                      VALUES ('admin', '$hashed_password', 'Admin', 'User', 'admin@barangaybalas.com', 'Barangay Captain')");
    }
}

// Initialize the database
initializeDatabase($conn);

// Function to log activities
function logActivity($conn, $user_id, $activity) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, activity, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $activity, $ip_address, $user_agent);
    $stmt->execute();
    $stmt->close();
}
?>