<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/db.php";

// Get roll number from URL parameter
$roll = isset($_GET['roll']) ? trim($_GET['roll']) : '';

if (empty($roll)) {
    header("Location: manage_students.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Borrowing History - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(90deg, #8fa0ff, #5d73ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .back-link {
            color: #6b7fd7;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #8fa0ff;
            transform: translateX(-2px);
        }

        .student-summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(179, 192, 237, 0.2);
            margin-bottom: 30px;
        }

        .student-summary-card h3 {
            margin: 0 0 20px 0;
            font-size: 20px;
            font-weight: 600;
            color: #1a1a2e;
            background: linear-gradient(90deg, #8fa0ff, #5d73ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .student-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .student-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .student-detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7fd7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .student-detail-value {
            font-size: 15px;
            color: #1a1a2e;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(179, 192, 237, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #b3c0ed 0%, #d8e2ff 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            color: #6b7fd7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            background: linear-gradient(90deg, #8fa0ff, #5d73ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:nth-child(even) {
            background: rgba(248, 249, 255, 0.5);
        }

        tbody tr:hover {
            background: #f3f6ff;
        }

        td {
            color: #1a1a2e;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-borrowed {
            background: linear-gradient(90deg, #8fa0ff, #5d73ff);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(143, 160, 255, 0.3);
        }

        .status-returned {
            background: linear-gradient(90deg, #4caf50, #45a049);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }

        .status-overdue {
            background: linear-gradient(90deg, #f44336, #e53935);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #6b7fd7;
        }
    </style>
</head>
<body>

    <?php include "sidebar.php"; ?>

    <div class="content">

        <div class="topbar">
            <h1>Student Borrowing History</h1>
            <a href="manage_students.php" class="back-link">← Back to Students</a>
        </div>

        <div class="student-summary-card" id="studentSummaryCard">
            <h3>Student Information</h3>
            <div class="student-details-grid">
                <div class="student-detail-item">
                    <span class="student-detail-label">Name</span>
                    <span class="student-detail-value" id="studentName">Loading...</span>
                </div>
                <div class="student-detail-item">
                    <span class="student-detail-label">Roll Number</span>
                    <span class="student-detail-value" id="studentRoll"><?= htmlspecialchars($roll, ENT_QUOTES) ?></span>
                </div>
                <div class="student-detail-item">
                    <span class="student-detail-label">Branch</span>
                    <span class="student-detail-value" id="studentBranch">Loading...</span>
                </div>
                <div class="student-detail-item">
                    <span class="student-detail-label">Email</span>
                    <span class="student-detail-value" id="studentEmail">Loading...</span>
                </div>
                <div class="student-detail-item">
                    <span class="student-detail-label">Mobile</span>
                    <span class="student-detail-value" id="studentMobile">Loading...</span>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Borrowed</h3>
                <div class="stat-value" id="totalBorrowed">...</div>
            </div>

            <div class="stat-card">
                <h3>Currently Borrowed</h3>
                <div class="stat-value" id="currentlyBorrowed">...</div>
            </div>

            <div class="stat-card">
                <h3>Returned</h3>
                <div class="stat-value" id="returned">...</div>
            </div>

            <div class="stat-card">
                <h3>Overdue</h3>
                <div class="stat-value" id="overdue">...</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Actual Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="historyTableBody">
                <tr>
                    <td colspan="5" class="loading">Loading history...</td>
                </tr>
            </tbody>
        </table>

    </div>

    <script>
        // Load student history on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStudentHistory();
        });

        function loadStudentHistory() {
            const roll = '<?= htmlspecialchars($roll, ENT_QUOTES) ?>';
            
            // Show loading states
            document.getElementById('studentName').textContent = 'Loading...';
            document.getElementById('studentBranch').textContent = 'Loading...';
            document.getElementById('studentEmail').textContent = 'Loading...';
            document.getElementById('studentMobile').textContent = 'Loading...';
            
            document.getElementById('totalBorrowed').textContent = '...';
            document.getElementById('currentlyBorrowed').textContent = '...';
            document.getElementById('returned').textContent = '...';
            document.getElementById('overdue').textContent = '...';

            // Fetch data from API
            fetch('api/get_student_history.php?roll=' + encodeURIComponent(roll))
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Populate student summary
                        document.getElementById('studentName').textContent = data.student.name || 'N/A';
                        document.getElementById('studentBranch').textContent = data.student.branch || 'Not set';
                        document.getElementById('studentEmail').textContent = data.student.email || 'Not set';
                        document.getElementById('studentMobile').textContent = data.student.mobile || 'Not set';

                        // Populate summary cards
                        document.getElementById('totalBorrowed').textContent = data.summary.total_borrowed || 0;
                        document.getElementById('currentlyBorrowed').textContent = data.summary.currently_borrowed || 0;
                        document.getElementById('returned').textContent = data.summary.returned || 0;
                        document.getElementById('overdue').textContent = data.summary.overdue || 0;

                        // Render history table
                        renderHistoryTable(data.history);
                    } else {
                        // Handle error
                        document.getElementById('historyTableBody').innerHTML = 
                            '<tr><td colspan="5" class="no-results">Error loading history: ' + (data.message || 'Unknown error') + '</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    document.getElementById('historyTableBody').innerHTML = 
                        '<tr><td colspan="5" class="no-results">Network error. Please try again.</td></tr>';
                });
        }

        function renderHistoryTable(history) {
            const tbody = document.getElementById('historyTableBody');
            
            if (history.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="no-results">No borrowing history found.</td></tr>';
                return;
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            tbody.innerHTML = history.map(record => {
                const bookTitle = escapeHtml(record.book_title || '');
                const borrowDate = escapeHtml(record.borrow_date || '');
                const returnDate = escapeHtml(record.return_date || '');
                const actualReturnDate = record.actual_return_date ? escapeHtml(record.actual_return_date) : '-';
                const status = record.status || 'borrowed';

                // Determine if overdue
                const returnDateObj = new Date(record.return_date);
                returnDateObj.setHours(0, 0, 0, 0);
                const isOverdue = status === 'borrowed' && returnDateObj < today;

                // Determine status badge
                let statusBadge = '';
                if (isOverdue) {
                    statusBadge = '<span class="status-badge status-overdue">Overdue</span>';
                } else if (status === 'borrowed') {
                    statusBadge = '<span class="status-badge status-borrowed">Borrowed</span>';
                } else {
                    statusBadge = '<span class="status-badge status-returned">Returned</span>';
                }

                return `
                    <tr>
                        <td>${bookTitle}</td>
                        <td>${borrowDate}</td>
                        <td>${returnDate}</td>
                        <td>${actualReturnDate}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            }).join('');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>

</body>
</html>

