<?php
session_start();
include "../config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing ID"]);
    exit;
}

$book_id = intval($_GET['id']);

// Check if book is borrowed
$check = $conn->prepare("SELECT * FROM borrow_records WHERE book_id = ? AND status = 'borrowed'");
$check->bind_param("i", $book_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Cannot delete. Book has active borrowings."]);
    exit;
}

// Delete cover image
$imgQuery = $conn->prepare("SELECT cover_image FROM books WHERE id = ?");
$imgQuery->bind_param("i", $book_id);
$imgQuery->execute();
$imgRes = $imgQuery->get_result()->fetch_assoc();

if ($imgRes && $imgRes['cover_image']) {
    $file = "../uploads/book_covers/" . $imgRes['cover_image'];
    if (file_exists($file)) {
        unlink($file);
    }
}

// Delete book
$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();

echo json_encode(["status" => "success"]);
?>

