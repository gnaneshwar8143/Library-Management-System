<?php
session_start();
header("Content-Type: application/json");
include "../config/db.php";
include "../config/config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$borrow_id = $_POST['borrow_id'] ?? null;
$pass = $_POST['password'] ?? null;

if (!$borrow_id || !$pass) {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}

if ($pass !== $ADMIN_RETURN_PASSWORD) {
    echo json_encode(["status" => "error", "message" => "Incorrect return password"]);
    exit;
}

$stmt = $conn->prepare("UPDATE borrow_records SET status='returned', actual_return_date=CURDATE() WHERE id=?");
$stmt->bind_param("i", $borrow_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>

