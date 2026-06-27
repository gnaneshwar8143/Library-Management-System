<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

include "../../config/db.php";

// Get parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$branch = isset($_GET['branch']) ? trim($_GET['branch']) : '';

// Build SQL query with filters using prepared statements
$whereConditions = ["1=1"];
$params = [];
$types = '';

// Add search filter
if (!empty($search)) {
    $searchTerm = '%' . $search . '%';
    $whereConditions[] = "(name LIKE ? OR roll_number LIKE ? OR email LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'sss';
}

// Add branch filter (ignore if "all" or "All Branches" or empty)
if (!empty($branch) && $branch !== 'all' && $branch !== 'All Branches') {
    $whereConditions[] = "branch = ?";
    $params[] = $branch;
    $types .= 's';
}

// Build final query
$whereClause = "WHERE " . implode(" AND ", $whereConditions);
$sql = "SELECT * FROM students $whereClause ORDER BY name ASC";

$students = [];

try {
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'id' => $row['id'],
            'roll_number' => $row['roll_number'] ?? '',
            'name' => $row['name'] ?? '',
            'branch' => $row['branch'] ?? 'Not set',
            'email' => $row['email'] ?? 'Not set',
            'mobile' => $row['mobile'] ?? 'Not set'
        ];
    }

    echo json_encode([
        "status" => "success",
        "students" => $students
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}

$conn->close();
?>

