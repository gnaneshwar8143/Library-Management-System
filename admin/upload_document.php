<?php
session_start();
include "../config/db.php";
include "../config/config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

$name = $_POST['name'] ?? '';
$file = $_FILES['file'] ?? null;

if (!$file) {
    die("No file uploaded");
}

$filename = time() . "_" . basename($file["name"]);
$target = $DOCS_PATH . $filename;

move_uploaded_file($file['tmp_name'], $target);

$stmt = $conn->prepare("INSERT INTO documents (name, file_path) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $filename);
$stmt->execute();

header("Location: documents.php");
exit;
?>

