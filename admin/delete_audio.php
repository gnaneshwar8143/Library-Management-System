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
    echo json_encode(["status" => "error","message"=>"Missing id"]);
    exit;
}

// Get file and image
$stmt = $conn->prepare("SELECT audio_file, audio_image FROM audio_books WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // Delete audio file
    $file = $AUDIO_PATH . $row['audio_file'];
    if (file_exists($file)) unlink($file);
    
    // Delete image file (if not default)
    if (!empty($row['audio_image']) && $row['audio_image'] !== 'default_audio.jpg') {
        $imageFile = __DIR__ . "/../uploads/audio_images/" . $row['audio_image'];
        if (file_exists($imageFile)) unlink($imageFile);
    }
}

// Delete DB row
$deleteStmt = $conn->prepare("DELETE FROM audio_books WHERE id=?");
$deleteStmt->bind_param("i", $id);
$deleteStmt->execute();

echo json_encode(["status"=>"success"]);
?>

