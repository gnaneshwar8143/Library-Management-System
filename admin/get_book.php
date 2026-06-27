<?php
session_start();
include "../config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['student_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing book ID"]);
    exit;
}

$book_id = intval($_GET['id']);

$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Book not found"]);
    exit;
}

$book = $result->fetch_assoc();

echo json_encode([
    "status" => "success",
    "data" => $book
]);
?>

