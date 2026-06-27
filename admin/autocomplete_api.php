<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

include "../config/db.php";

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query) || strlen($query) < 2) {
    echo json_encode(["status" => "success", "suggestions" => []]);
    exit;
}

$searchTerm = '%' . $query . '%';
$suggestions = [];

// Get suggestions from titles, authors, and categories using prepared statements
$sql = "SELECT DISTINCT title, author, category FROM books 
        WHERE title LIKE ? OR author LIKE ? OR category LIKE ? 
        LIMIT 15";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$seen = [];
while ($row = $result->fetch_assoc()) {
    // Add title if matches
    if (stripos($row['title'], $query) !== false && !in_array($row['title'], $seen)) {
        $suggestions[] = ['text' => $row['title'], 'type' => 'title'];
        $seen[] = $row['title'];
    }
    
    // Add author if matches
    if (stripos($row['author'], $query) !== false && !in_array($row['author'], $seen)) {
        $suggestions[] = ['text' => $row['author'], 'type' => 'author'];
        $seen[] = $row['author'];
    }
    
    // Add category if matches
    if (stripos($row['category'], $query) !== false && !in_array($row['category'], $seen)) {
        $suggestions[] = ['text' => $row['category'], 'type' => 'category'];
        $seen[] = $row['category'];
    }
    
    // Limit to 5 total
    if (count($suggestions) >= 5) break;
}

echo json_encode([
    "status" => "success",
    "suggestions" => array_slice($suggestions, 0, 5)
]);
?>

