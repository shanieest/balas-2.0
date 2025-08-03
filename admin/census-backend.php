<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    $action = $_GET['action'] ?? '';
    $user_id = getUserId();

    switch ($action) {
        case 'list':
            handleListHouseholds();
            break;
        case 'view':
            handleViewHousehold();
            break;
        case 'add':
            handleAddHousehold();
            break;
        case 'edit':
            handleEditHousehold();
            break;
        case 'delete':
            handleDeleteHousehold();
            break;
        case 'add_member':
            handleAddMember();
            break;
        case 'delete_member':
            handleDeleteMember();
            break;
        case 'add_livelihood':
            handleAddLivelihood();
            break;
        case 'delete_livelihood':
            handleDeleteLivelihood();
            break;
        case 'add_assistance':
            handleAddAssistance();
            break;
        case 'delete_assistance':
            handleDeleteAssistance();
            break;
        default:
            $response['message'] = 'Invalid action';
            echo json_encode($response);
            exit();
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit();
}

function handleListHouseholds() {
    global $conn, $response;
    
    $page = $_GET['page'] ?? 1;
    $per_page = $_GET['per_page'] ?? 10;
    $search = $_GET['search'] ?? '';
    $purok = $_GET['purok'] ?? '';
    $house_type = $_GET['house_type'] ?? '';
    $water_source = $_GET['water_source'] ?? '';
    $status = $_GET['status'] ?? '';
    
    $offset = ($page - 1) * $per_page;
    
    $query = "SELECT h.*, 
              (SELECT CONCAT(first_name, ' ', last_name) 
               FROM household_members 
               WHERE household_id = h.id AND relationship = 'Head' LIMIT 1) as head_of_family,
              (SELECT COUNT(*) FROM household_members WHERE household_id = h.id) as member_count
              FROM households h";
    
    $where = [];
    $params = [];
    $types = '';
    
    if ($search) {
        $where[] = "(h.household_number LIKE ? OR h.address LIKE ? OR 
                    EXISTS (SELECT 1 FROM household_members hm 
                           WHERE hm.household_id = h.id 
                           AND CONCAT(hm.first_name, ' ', hm.last_name) LIKE ?))";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sss';
    }
    
    if ($purok) {
        $where[] = "h.purok = ?";
        $params[] = $purok;
        $types .= 's';
    }
    
    if ($house_type) {
        $where[] = "h.house_type = ?";
        $params[] = $house_type;
        $types .= 's';
    }
    
    if ($water_source) {
        $where[] = "h.water_source LIKE ?";
        $params[] = "$water_source%";
        $types .= 's';
    }
    
    if ($status) {
        $where[] = "h.status = ?";
        $params[] = $status;
        $types .= 's';
    }
    
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM households h";
    if (!empty($where)) {
        $countQuery .= " WHERE " . implode(" AND ", $where);
    }
    
    $stmt = $conn->prepare($countQuery);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'];
    
    // Get paginated data
    $query .= " ORDER BY h.household_number LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $households = [];
    while ($row = $result->fetch_assoc()) {
        $households[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $households;
    $response['pagination'] = [
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => ceil($total / $per_page)
    ];
    
    echo json_encode($response);
}

function handleViewHousehold() {
    global $conn, $response;
    
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        throw new Exception("Household ID is required");
    }
    
    // Get household info
    $stmt = $conn->prepare("SELECT * FROM households WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $household = $stmt->get_result()->fetch_assoc();
    
    if (!$household) {
        throw new Exception("Household not found");
    }
    
    // Get members
    $stmt = $conn->prepare("SELECT * FROM household_members WHERE household_id = ? ORDER BY 
                          CASE relationship 
                            WHEN 'Head' THEN 1
                            WHEN 'Spouse' THEN 2
                            WHEN 'Son' THEN 3
                            WHEN 'Daughter' THEN 4
                            ELSE 5
                          END, age DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Get livelihood
    $stmt = $conn->prepare("SELECT * FROM household_livelihood WHERE household_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $livelihood = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Get assistance
    $stmt = $conn->prepare("SELECT * FROM household_assistance WHERE household_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $assistance = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $response['success'] = true;
    $response['data'] = [
        'household' => $household,
        'members' => $members,
        'livelihood' => $livelihood,
        'assistance' => $assistance
    ];
    
    echo json_encode($response);
}

function handleAddHousehold() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['purok', 'address', 'house_type', 'ownership', 'water_source', 'electricity'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Generate household number
    $year = date('Y');
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM households WHERE household_number LIKE ?");
    $prefix = "BL-$year-";
    $likePrefix = "$prefix%";
    $stmt->bind_param("s", $likePrefix);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['count'] + 1;
    $household_number = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    
    // Insert household
    $stmt = $conn->prepare("INSERT INTO households 
        (household_number, purok, address, house_type, ownership, water_source, electricity)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssss", 
        $household_number, $data['purok'], $data['address'], 
        $data['house_type'], $data['ownership'], $data['water_source'], $data['electricity']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add household: " . $stmt->error);
    }
    
    $household_id = $stmt->insert_id;
    
    $response['success'] = true;
    $response['message'] = 'Household added successfully';
    $response['household_id'] = $household_id;
    echo json_encode($response);
}

function handleEditHousehold() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Household ID is required");
    }
    
    // Validate required fields
    $required = ['purok', 'address', 'house_type', 'ownership', 'water_source', 'electricity', 'status'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Update household
    $stmt = $conn->prepare("UPDATE households SET 
        purok = ?, address = ?, house_type = ?, ownership = ?, 
        water_source = ?, electricity = ?, status = ?
        WHERE id = ?");
    
    $stmt->bind_param("sssssssi", 
        $data['purok'], $data['address'], $data['house_type'], 
        $data['ownership'], $data['water_source'], $data['electricity'], 
        $data['status'], $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update household: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Household updated successfully';
    echo json_encode($response);
}

function handleDeleteHousehold() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Household ID is required");
    }
    
    $stmt = $conn->prepare("DELETE FROM households WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete household: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Household deleted successfully';
    echo json_encode($response);
}

function handleAddMember() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['household_id', 'first_name', 'last_name', 'relationship', 'age', 'sex', 'civil_status'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO household_members 
        (household_id, first_name, last_name, middle_name, relationship, age, sex, civil_status, occupation, education, voter)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("issssisssss", 
        $data['household_id'], $data['first_name'], $data['last_name'], $data['middle_name'] ?? '',
        $data['relationship'], $data['age'], $data['sex'], $data['civil_status'],
        $data['occupation'] ?? '', $data['education'] ?? '', $data['voter'] ?? 'No');
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add household member: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Household member added successfully';
    echo json_encode($response);
}

function handleDeleteMember() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Member ID is required");
    }
    
    $stmt = $conn->prepare("DELETE FROM household_members WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete household member: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Household member deleted successfully';
    echo json_encode($response);
}

function handleAddLivelihood() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['household_id']) || empty($data['description'])) {
        throw new Exception("Household ID and description are required");
    }
    
    $stmt = $conn->prepare("INSERT INTO household_livelihood (household_id, description) VALUES (?, ?)");
    $stmt->bind_param("is", $data['household_id'], $data['description']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add livelihood: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Livelihood added successfully';
    echo json_encode($response);
}

function handleDeleteLivelihood() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Livelihood ID is required");
    }
    
    $stmt = $conn->prepare("DELETE FROM household_livelihood WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete livelihood: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Livelihood deleted successfully';
    echo json_encode($response);
}

function handleAddAssistance() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['household_id']) || empty($data['description'])) {
        throw new Exception("Household ID and description are required");
    }
    
    $stmt = $conn->prepare("INSERT INTO household_assistance (household_id, description) VALUES (?, ?)");
    $stmt->bind_param("is", $data['household_id'], $data['description']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add assistance: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Assistance added successfully';
    echo json_encode($response);
}

function handleDeleteAssistance() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Assistance ID is required");
    }
    
    $stmt = $conn->prepare("DELETE FROM household_assistance WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete assistance: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Assistance deleted successfully';
    echo json_encode($response);
}
?>