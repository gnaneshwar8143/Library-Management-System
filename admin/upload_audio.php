<?php
session_start();
include "../config/db.php";
include "../config/config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

$title = $_POST['title'] ?? '';
$file = $_FILES['audio'] ?? null;
$imageFile = $_FILES['image'] ?? null;

if (!$file || $file['type'] !== "audio/mpeg") {
    die("Only MP3 allowed");
}

$filename = time() . "_" . basename($file["name"]);
$target = $AUDIO_PATH . $filename;

move_uploaded_file($file['tmp_name'], $target);

// Handle image upload
$imageFilename = 'default_audio.jpg'; // Default

if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
    
    if (in_array($imageFile['type'], $allowedTypes) && in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
        $imageFilename = time() . "_" . basename($imageFile["name"]);
        $imageTarget = __DIR__ . "/../uploads/audio_images/" . $imageFilename;
        
        if (move_uploaded_file($imageFile['tmp_name'], $imageTarget)) {
            // Image uploaded successfully
        } else {
            $imageFilename = 'default_audio.jpg';
        }
    }
}

$stmt = $conn->prepare("INSERT INTO audio_books (title, audio_file, audio_image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $filename, $imageFilename);
$stmt->execute();

header("Location: audio.php");
exit;
?>

