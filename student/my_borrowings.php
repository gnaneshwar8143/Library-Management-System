<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/db.php";
$roll = $_SESSION['student_roll'];
$student_id = $_SESSION['student_id'] ?? null;

// Check for success message
$success_message = $_GET['success'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Borrowings</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: transparent;
        display: flex;
        color: #1a1a2e;
    }

    .content {
        margin-left: 260px;
        padding: 30px;
        width: calc(100% - 260px);
    }

    .topbar {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 20px 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(179, 192, 237, 0.2);
    }

    .topbar h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(90deg, #8fa0ff, #5d73ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(179, 192, 237, 0.2);
    }

    th, td {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(179, 192, 237, 0.2);
        text-align: left;
    }

    th {
        background: linear-gradient(90deg, #8fa0ff, #5d73ff);
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(93, 115, 255, 0.3);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: #f3f6ff;
        transition: background 0.2s ease;
    }

    td {
        color: #1a1a2e;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
    }

    .status-borrowed {
        background: linear-gradient(90deg, #9aa9ff, #6278ff);
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(98, 120, 255, 0.35);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .status-returned {
        background: linear-gradient(90deg, #9aa9ff, #6278ff);
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(98, 120, 255, 0.35);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }


    .success-alert {
        background: #d4edda;
        color: #155724;
        padding: 14px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .success-alert::before {
        content: "✓";
        font-size: 18px;
        font-weight: bold;
    }
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <h2>My Borrowings</h2>
    </div>

    <table>
        <tr>
            <th>Book Name</th>
            <th>Author</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
            <th>Status</th>
        </tr>

        <?php
        $query = "
            SELECT 
                b.title, 
                b.author, 
                r.borrow_date, 
                r.return_date, 
                r.status
            FROM borrow_records r
            JOIN books b ON r.book_id = b.id
            WHERE r.student_roll = ?
            ORDER BY r.id DESC
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $roll);
        $stmt->execute();
        $records = $stmt->get_result();

        if ($records->num_rows > 0):

            while ($r = $records->fetch_assoc()):

        ?>
        <tr>
            <td><?= htmlspecialchars($r['title'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($r['author'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($r['borrow_date'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($r['return_date'], ENT_QUOTES) ?></td>
            <td>
                <?php if ($r['status'] === "borrowed"): ?>
                    <span class="status-borrowed">Borrowed</span>
                <?php else: ?>
                    <span class="status-returned">Returned</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; else: ?>

        <tr>
            <td colspan="5" style="text-align:center;">No borrowings found.</td>
        </tr>
        <?php endif; ?>

    </table>

</div>


</body>
</html>

