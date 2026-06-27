<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: ../login.php");
    exit;
}
include "../config/db.php";

// Get student ID from session
$student_id = $_SESSION['student_id'] ?? null;

if (!$student_id) {
    header("Location: dashboard.php");
    exit;
}

// Fetch student details from database
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit;
}

$student = $result->fetch_assoc();

// Get available fields (handle missing columns gracefully)
$name = $student['name'] ?? 'N/A';
$roll_number = $student['roll_number'] ?? 'N/A';
$branch = $student['branch'] ?? 'Not set';
$email = $student['email'] ?? 'Not set';
$mobile = $student['mobile'] ?? 'Not set';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Dashboard</title>
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

        .profile-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(179, 192, 237, 0.2);
            position: relative;
            max-width: 700px;
            margin: 0 auto;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #b3c0ed 0%, #d8e2ff 100%);
            border-radius: 16px 16px 0 0;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid rgba(179, 192, 237, 0.2);
        }

        .profile-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8fa0ff 0%, #5d73ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 20px rgba(93, 115, 255, 0.3);
        }

        .profile-icon svg {
            width: 50px;
            height: 50px;
            color: #ffffff;
        }

        .profile-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            background: linear-gradient(90deg, #8fa0ff, #5d73ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .profile-details {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7fd7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 500;
            color: #1a1a2e;
            padding: 12px 16px;
            background: #f8f9ff;
            border-radius: 10px;
            border: 1px solid rgba(179, 192, 237, 0.2);
        }

        .detail-value:empty::before {
            content: 'Not set';
            color: #999;
            font-style: italic;
        }

        .edit-button-container {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .edit-btn {
            background: linear-gradient(135deg, #8fa0ff 0%, #5d73ff 100%);
            color: #ffffff;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(93, 115, 255, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .edit-btn:hover {
            background: linear-gradient(135deg, #7c8dfc 0%, #4e60e6 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 96, 230, 0.5);
        }

        .profile-icon-link {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .profile-icon-link:hover {
            transform: scale(1.1);
        }

        .profile-icon-link svg {
            transition: all 0.3s ease;
        }

        .profile-icon-link:hover svg {
            color: #8fa0ff !important;
        }
    </style>
</head>
<body>

    <?php include "sidebar.php"; ?>

    <div class="content">

        <div class="topbar">
            <h2>My Profile</h2>
        </div>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h3><?= htmlspecialchars($name, ENT_QUOTES) ?></h3>
            </div>

            <div class="profile-details">
                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <div class="detail-value"><?= htmlspecialchars($name, ENT_QUOTES) ?></div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Roll Number</span>
                    <div class="detail-value"><?= htmlspecialchars($roll_number, ENT_QUOTES) ?></div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Branch</span>
                    <div class="detail-value"><?= htmlspecialchars($branch, ENT_QUOTES) ?></div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <div class="detail-value"><?= htmlspecialchars($email, ENT_QUOTES) ?></div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Mobile</span>
                    <div class="detail-value"><?= htmlspecialchars($mobile, ENT_QUOTES) ?></div>
                </div>
            </div>

            <div class="edit-button-container">
                <button class="edit-btn" onclick="openResetPasswordModal()">Reset Password</button>
            </div>
        </div>

    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="modal-bg" style="
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    ">
        <div class="modal-box" style="
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s ease;
            z-index: 10000;
            position: relative;
        ">
            <h3 style="margin-top:0; color:#4a6cf7; font-family: 'Poppins', sans-serif; font-weight: 600;">Reset Password</h3>

            <div id="resetPasswordMessage" style="
                display: none;
                padding: 12px;
                margin: 10px 0 15px 0;
                border-radius: 8px;
                font-size: 14px;
                font-family: 'Poppins', sans-serif;
            "></div>

            <form id="resetPasswordForm" onsubmit="submitResetPassword(event)">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333; font-family: 'Poppins', sans-serif;">Current Password</label>
                <input type="password" id="currentPassword" required style="
                    width: 100%; 
                    padding: 10px; 
                    margin: 5px 0 15px 0;
                    border: 1px solid #ccc; 
                    border-radius: 8px;
                    font-family: 'Poppins', sans-serif;
                    box-sizing: border-box;
                ">

                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333; font-family: 'Poppins', sans-serif;">New Password</label>
                <input type="password" id="newPassword" required style="
                    width: 100%; 
                    padding: 10px; 
                    margin: 5px 0 15px 0;
                    border: 1px solid #ccc; 
                    border-radius: 8px;
                    font-family: 'Poppins', sans-serif;
                    box-sizing: border-box;
                ">

                <button type="submit" style="
                    width: 100%; 
                    padding: 12px;
                    background: linear-gradient(135deg, #8fa0ff 0%, #5d73ff 100%);
                    color: white;
                    border: none; 
                    border-radius: 8px;
                    margin-top: 10px; 
                    font-size: 16px; 
                    cursor: pointer;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 600;
                    transition: all 0.3s ease;
                ">Update Password</button>

                <button type="button" onclick="closeResetPasswordModal()" style="
                    width: 100%; 
                    padding: 12px;
                    background: #ddd; 
                    color: #333;
                    border: none; 
                    border-radius: 8px;
                    margin-top: 10px; 
                    font-size: 16px; 
                    cursor: pointer;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 600;
                    transition: all 0.3s ease;
                ">Cancel</button>
            </form>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-bg button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>

    <script>
        function openResetPasswordModal() {
            document.getElementById("resetPasswordModal").style.display = "flex";
            document.getElementById("resetPasswordMessage").style.display = "none";
            document.getElementById("resetPasswordForm").reset();
        }

        function closeResetPasswordModal() {
            document.getElementById("resetPasswordModal").style.display = "none";
            document.getElementById("resetPasswordMessage").style.display = "none";
            document.getElementById("resetPasswordForm").reset();
        }

        function submitResetPassword(event) {
            event.preventDefault();
            
            const currentPassword = document.getElementById("currentPassword").value;
            const newPassword = document.getElementById("newPassword").value;
            const messageDiv = document.getElementById("resetPasswordMessage");

            if (!currentPassword || !newPassword) {
                messageDiv.style.display = "block";
                messageDiv.style.background = "#ffe5e5";
                messageDiv.style.color = "#d32f2f";
                messageDiv.style.border = "1px solid #ffcdd2";
                messageDiv.textContent = "Please fill in all fields.";
                return;
            }

            // Submit via AJAX
            fetch("reset_password.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    current_password: currentPassword,
                    new_password: newPassword
                })
            })
            .then(res => res.json())
            .then(data => {
                messageDiv.style.display = "block";
                
                if (data.status === "success") {
                    messageDiv.style.background = "#e8f5e9";
                    messageDiv.style.color = "#2e7d32";
                    messageDiv.style.border = "1px solid #c8e6c9";
                    messageDiv.textContent = data.message || "Password updated successfully.";
                    
                    // Clear form and close modal after 2 seconds
                    setTimeout(() => {
                        closeResetPasswordModal();
                    }, 2000);
                } else {
                    messageDiv.style.background = "#ffe5e5";
                    messageDiv.style.color = "#d32f2f";
                    messageDiv.style.border = "1px solid #ffcdd2";
                    messageDiv.textContent = data.message || "An error occurred.";
                }
            })
            .catch(err => {
                messageDiv.style.display = "block";
                messageDiv.style.background = "#ffe5e5";
                messageDiv.style.color = "#d32f2f";
                messageDiv.style.border = "1px solid #ffcdd2";
                messageDiv.textContent = "Network error. Please try again.";
            });
        }

        // Close modal when clicking outside
        document.getElementById("resetPasswordModal").addEventListener("click", function(e) {
            if (e.target === this) {
                closeResetPasswordModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                closeResetPasswordModal();
            }
        });
    </script>

</body>
</html>

