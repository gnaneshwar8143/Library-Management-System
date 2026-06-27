<?php
session_start();

// Validate that the student is logged in
if (!isset($_SESSION['student_logged_in']) || !$_SESSION['student_logged_in']) {
    header("Location: ../login.php");
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my_borrowings.php");
    exit;
}

include "../config/db.php";

// Get the logged-in student's roll number and ID
$roll = $_SESSION['student_roll'] ?? null;
$student_id = $_SESSION['student_id'] ?? null;

if (!$roll) {
    header("Location: my_borrowings.php?error=session_invalid");
    exit;
}

// Delete all borrow records for this student
// Using student_roll as that's the field in the borrow_records table
$stmt = $conn->prepare("DELETE FROM borrow_records WHERE student_roll = ?");
$stmt->bind_param("s", $roll);

if ($stmt->execute()) {
    // Redirect back with success message
    header("Location: my_borrowings.php?success=cleared");
    exit;
} else {
    // Redirect back with error message
    header("Location: my_borrowings.php?error=delete_failed");
    exit;
}
?>

