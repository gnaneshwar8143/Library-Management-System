<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

include "../../config/db.php";

// Get roll number from GET parameter
$roll = isset($_GET['roll']) ? trim($_GET['roll']) : '';

if (empty($roll)) {
    echo json_encode(["status" => "error", "message" => "Roll number is required"]);
    exit;
}

try {
    // Fetch student details
    $studentStmt = $conn->prepare("SELECT * FROM students WHERE roll_number = ?");
    $studentStmt->bind_param("s", $roll);
    $studentStmt->execute();
    $studentResult = $studentStmt->get_result();

    if ($studentResult->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Student not found"]);
        exit;
    }

    $student = $studentResult->fetch_assoc();

    // Fetch borrowing history
    $historyQuery = "SELECT br.*, b.title 
                     FROM borrow_records br
                     JOIN books b ON b.id = br.book_id
                     WHERE br.student_roll = ?
                     ORDER BY br.borrow_date DESC";
    $historyStmt = $conn->prepare($historyQuery);
    $historyStmt->bind_param("s", $roll);
    $historyStmt->execute();
    $historyResult = $historyStmt->get_result();

    $history = [];
    while ($row = $historyResult->fetch_assoc()) {
        $history[] = [
            'id' => $row['id'],
            'book_title' => $row['title'] ?? '',
            'borrow_date' => $row['borrow_date'] ?? '',
            'return_date' => $row['return_date'] ?? '',
            'actual_return_date' => $row['actual_return_date'] ?? null,
            'status' => $row['status'] ?? 'borrowed'
        ];
    }

    // Calculate summary statistics
    $totalBorrowed = count($history);
    
    $currentlyBorrowedStmt = $conn->prepare("SELECT COUNT(*) AS count 
                                             FROM borrow_records 
                                             WHERE student_roll = ? AND status = 'borrowed'");
    $currentlyBorrowedStmt->bind_param("s", $roll);
    $currentlyBorrowedStmt->execute();
    $currentlyBorrowedResult = $currentlyBorrowedStmt->get_result();
    $currentlyBorrowedRow = $currentlyBorrowedResult->fetch_assoc();
    $currentlyBorrowed = (int)$currentlyBorrowedRow['count'];

    $overdueStmt = $conn->prepare("SELECT COUNT(*) AS count 
                                   FROM borrow_records 
                                   WHERE student_roll = ? 
                                     AND status = 'borrowed' 
                                     AND return_date < CURDATE()");
    $overdueStmt->bind_param("s", $roll);
    $overdueStmt->execute();
    $overdueResult = $overdueStmt->get_result();
    $overdueRow = $overdueResult->fetch_assoc();
    $overdueCount = (int)$overdueRow['count'];

    $returnedStmt = $conn->prepare("SELECT COUNT(*) AS count 
                                    FROM borrow_records 
                                    WHERE student_roll = ? AND status = 'returned'");
    $returnedStmt->bind_param("s", $roll);
    $returnedStmt->execute();
    $returnedResult = $returnedStmt->get_result();
    $returnedRow = $returnedResult->fetch_assoc();
    $returnedCount = (int)$returnedRow['count'];

    echo json_encode([
        "status" => "success",
        "student" => [
            "id" => $student['id'],
            "name" => $student['name'] ?? '',
            "roll_number" => $student['roll_number'] ?? '',
            "branch" => $student['branch'] ?? 'Not set',
            "email" => $student['email'] ?? 'Not set',
            "mobile" => $student['mobile'] ?? 'Not set'
        ],
        "summary" => [
            "total_borrowed" => $totalBorrowed,
            "currently_borrowed" => $currentlyBorrowed,
            "overdue" => $overdueCount,
            "returned" => $returnedCount
        ],
        "history" => $history
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}

$conn->close();
?>

