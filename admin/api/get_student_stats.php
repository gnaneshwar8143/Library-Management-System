<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

include "../../config/db.php";

try {
    // Total Students
    $totalQuery = "SELECT COUNT(*) AS total FROM students";
    $totalResult = $conn->query($totalQuery);
    $totalRow = $totalResult->fetch_assoc();
    $total_students = (int)$totalRow['total'];

    // Active Borrowers (students who currently have a book NOT returned)
    $activeQuery = "SELECT COUNT(DISTINCT student_roll) AS active 
                     FROM borrow_records 
                     WHERE status = 'borrowed'";
    $activeResult = $conn->query($activeQuery);
    $activeRow = $activeResult->fetch_assoc();
    $active_borrowers = (int)$activeRow['active'];

    // Overdue Borrowers (borrowed AND return_date < today)
    $overdueQuery = "SELECT COUNT(DISTINCT student_roll) AS overdue 
                      FROM borrow_records 
                      WHERE status = 'borrowed' 
                        AND return_date < CURDATE()";
    $overdueResult = $conn->query($overdueQuery);
    $overdueRow = $overdueResult->fetch_assoc();
    $overdue_borrowers = (int)$overdueRow['overdue'];

    echo json_encode([
        "status" => "success",
        "total_students" => $total_students,
        "active_borrowers" => $active_borrowers,
        "overdue_borrowers" => $overdue_borrowers
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}

$conn->close();
?>

