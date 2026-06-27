<?php
session_start();
include "../config/db.php";
include "../config/config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$category = $_POST['category'] ?? '';
$description = $_POST['description'] ?? '';

$filename = null;

if (!empty($_FILES['cover']['name'])) {
    $filename = time() . "_" . basename($_FILES["cover"]["name"]);
    move_uploaded_file($_FILES["cover"]["tmp_name"], $BOOK_COVER_PATH . $filename);
}

$stmt = $conn->prepare("INSERT INTO books (title, author, category, description, cover_image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $author, $category, $description, $filename);
$stmt->execute();

header("Location: my_library.php");
exit;
?>

