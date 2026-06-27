<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['student_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

include "../config/db.php";

// Get parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Build SQL query with filters using prepared statements
$whereConditions = [];
$params = [];
$types = '';

// Add search filter
if (!empty($search)) {
    $searchTerm = '%' . $search . '%';
    $whereConditions[] = "(title LIKE ? OR author LIKE ? OR category LIKE ? OR description LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ssss';
}

// Add category filter (ignore if "All Categories" or empty)
if (!empty($category) && $category !== 'All Categories') {
    $whereConditions[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}

// Build final query
if (!empty($whereConditions)) {
    $whereClause = "WHERE " . implode(" AND ", $whereConditions);
    $sql = "SELECT * FROM books $whereClause ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM books ORDER BY id DESC";
    $result = $conn->query($sql);
}

$books = [];
while ($row = $result->fetch_assoc()) {
    // Handle image source
    $img = $row['cover_image'];
    if ($img) {
        if (preg_match('/^http/i', $img)) {
            $imgSrc = $img;
        } else {
            $imgSrc = '../uploads/book_covers/' . $img;
        }
    } else {
        $imgSrc = '../assets/images/book_placeholder.svg';
    }
    
    $books[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'author' => $row['author'],
        'category' => $row['category'],
        'cover_image' => $imgSrc
    ];
}

echo json_encode([
    "status" => "success",
    "books" => $books
]);
?>

