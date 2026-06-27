<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Content -->
    <div class="content">

        <!-- Topbar -->
        <div class="topbar">
            <div class="profile">Welcome, <?= $_SESSION['student_name'] ?></div>
        </div>
        <div class="dashboard-content" style="text-align: center; padding: 60px 20px;">
            <h2 style="font-family: 'Poppins', sans-serif; background: linear-gradient(90deg, #8fa0ff, #5d73ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 600; font-size: 24px;">Select an option from the menu to begin.</h2>
        </div>

    </div>

</body>
</html>

