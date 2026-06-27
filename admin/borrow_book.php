<?php
session_start();
header("Content-Type: application/json");
include "../config/db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$book_id = $_POST['book_id'] ?? null;
$roll = $_POST['roll'] ?? null;
$borrow_date = $_POST['borrow_date'] ?? null;
$return_date = $_POST['return_date'] ?? null;

if (!$book_id || !$roll || !$borrow_date || !$return_date) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// Check if student exists
$studentCheck = $conn->prepare("SELECT * FROM students WHERE roll_number = ?");
$studentCheck->bind_param("s", $roll);
$studentCheck->execute();
$student = $studentCheck->get_result();

if ($student->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Student roll number not found"]);
    exit;
}

// Insert borrow record
$stmt = $conn->prepare("
    INSERT INTO borrow_records (book_id, student_roll, borrow_date, return_date, status)
    VALUES (?, ?, ?, ?, 'borrowed')
");
$stmt->bind_param("isss", $book_id, $roll, $borrow_date, $return_date);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Borrowed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>

