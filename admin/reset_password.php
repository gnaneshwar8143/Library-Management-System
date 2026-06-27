<?php
session_start();
header("Content-Type: application/json");

// Verify admin session
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(["status" => "error", "message" => "Session expired. Please login again."]);
    exit;
}

// Get admin username from session
$admin_username = $_SESSION['admin_username'] ?? null;

if (!$admin_username) {
    echo json_encode(["status" => "error", "message" => "Invalid session."]);
    exit;
}

// Include database connection
include "../config/db.php";

// Get POST data
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';

// Validate input
if (empty($current_password) || empty($new_password)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all fields."]);
    exit;
}

// Fetch admin's current password hash from database
$stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Admin not found."]);
    exit;
}

$admin = $result->fetch_assoc();
$stored_password_hash = $admin['password'];

// Verify current password
// Admin passwords are stored as SHA256 hashes (based on login.php)
$current_password_hash = hash('sha256', $current_password);

if ($current_password_hash !== $stored_password_hash) {
    echo json_encode(["status" => "error", "message" => "Incorrect current password."]);
    exit;
}

// Hash the new password using SHA256 (same method as login)
$new_password_hash = hash('sha256', $new_password);

// Update password in database
$update_stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
$update_stmt->bind_param("ss", $new_password_hash, $admin_username);

if ($update_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update password. Please try again."]);
}

$update_stmt->close();
$conn->close();
?>

