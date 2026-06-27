<?php
session_start();
include "../config/db.php";
include "../config/config.php";

header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["status"=>"error", "message"=>"Missing id"]);
    exit;
}

// Get file
$stmt = $conn->prepare("SELECT file_path FROM documents WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // Extract just filename (handle both old full paths and new filenames)
    $fileName = basename($row['file_path']);
    $file = $DOCS_PATH . $fileName;
    if (file_exists($file)) unlink($file);
}

// Delete DB row
$deleteStmt = $conn->prepare("DELETE FROM documents WHERE id=?");
$deleteStmt->bind_param("i", $id);
$deleteStmt->execute();

echo json_encode(["status"=>"success"]);
?>

