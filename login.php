<?php
session_start();
include "config/db.php";

$error = "";
$role = $_POST['role'] ?? 'admin'; // Default to admin

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'admin';
    
    if ($role === 'admin') {
        // Admin login logic
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            $sql = "SELECT * FROM admin WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();

                if (hash('sha256', $password) === $admin['password']) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $admin['username'];

                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    $error = "Incorrect password";
                }
            } else {
                $error = "Admin not found";
            }
        } else {
            $error = "Please fill in all fields";
        }
    } else {
        // Student login logic
        $roll = $_POST['roll'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($roll) && !empty($password)) {
            $sql = "SELECT * FROM students WHERE roll_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roll);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $student = $result->fetch_assoc();

                // Try modern password_verify() first (works if password was stored via password_hash)
                $login_ok = false;

                if (isset($student['password']) && password_verify($password, $student['password'])) {
                    $login_ok = true;
                } else {
                    // Fallback: compare SHA256 hex (works if password was inserted in SQL with SHA2(...,256))
                    if (isset($student['password']) && hash('sha256', $password) === $student['password']) {
                        $login_ok = true;
                    }
                }

                if ($login_ok) {
                    $_SESSION['student_logged_in'] = true;
                    $_SESSION['student_roll'] = $student['roll_number'];
                    $_SESSION['student_name'] = $student['name'];
                    $_SESSION['student_id'] = $student['id'];

                    header("Location: student/dashboard.php");
                    exit;
                } else {
                    $error = "Incorrect password";
                }
            } else {
                $error = "Student not found";
            }
        } else {
            $error = "Please fill in all fields";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            background-image: url('assets/images/login.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #333;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 126, 179, 0.1) 0%, rgba(255, 117, 140, 0.1) 100%);
            z-index: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 45px;
            width: 420px;
            max-width: 90%;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 12px 40px rgba(255, 126, 179, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.3);
            animation: fadeIn 0.6s ease-in-out;
            position: relative;
            z-index: 1;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h1 {
            color: #222;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: rgba(0, 0, 0, 0.7);
            font-size: 15px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid rgba(255, 126, 179, 0.3);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            box-sizing: border-box;
            color: #1a1a2e;
        }

        input:focus {
            outline: none;
            border-color: #ff7eb3;
            box-shadow: 0 0 0 4px rgba(255, 126, 179, 0.15);
            transform: translateY(-1px);
        }

        input::placeholder {
            color: #a0a0b0;
        }

        /* Custom Dropdown Styles - Scoped to login page */
        .custom-dropdown {
            position: relative;
            width: 100%;
        }

        .custom-dropdown-btn {
            width: 100%;
            padding: 14px 18px;
            background: linear-gradient(135deg, rgba(255, 126, 179, 0.1) 0%, rgba(255, 117, 140, 0.1) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 126, 179, 0.3);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #1a1a2e;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .custom-dropdown-btn:hover {
            background: linear-gradient(135deg, rgba(255, 126, 179, 0.15) 0%, rgba(255, 117, 140, 0.15) 100%);
            border-color: rgba(255, 126, 179, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 126, 179, 0.25);
        }

        .custom-dropdown-btn.active {
            background: linear-gradient(135deg, rgba(255, 126, 179, 0.2) 0%, rgba(255, 117, 140, 0.2) 100%);
            border-color: #ff7eb3;
            box-shadow: 0 0 0 4px rgba(255, 126, 179, 0.15);
        }

        .custom-dropdown-btn .selected-text {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .custom-dropdown-btn .dropdown-icon {
            font-size: 12px;
            transition: transform 0.3s ease;
            background: linear-gradient(135deg, #ff7eb3 0%, #ff758c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .custom-dropdown-btn.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .custom-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 250, 255, 0.95) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 126, 179, 0.2);
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(255, 126, 179, 0.2);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            max-height: 0;
        }

        .custom-dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            max-height: 300px;
        }

        .custom-dropdown-item {
            padding: 14px 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1a1a2e;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(255, 126, 179, 0.1);
        }

        .custom-dropdown-item:last-child {
            border-bottom: none;
        }

        .custom-dropdown-item:hover {
            background: linear-gradient(135deg, rgba(255, 126, 179, 0.1) 0%, rgba(255, 117, 140, 0.1) 100%);
            color: #ff7eb3;
            transform: translateX(4px);
        }

        .custom-dropdown-item.selected {
            background: linear-gradient(135deg, rgba(255, 126, 179, 0.15) 0%, rgba(255, 117, 140, 0.15) 100%);
            color: #ff7eb3;
            font-weight: 600;
        }

        .custom-dropdown-item .item-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .custom-dropdown-item .item-text {
            flex: 1;
        }

        .role-hidden-input {
            display: none;
        }

        .field-group {
            opacity: 1;
            max-height: 500px;
            overflow: hidden;
            transition: opacity 0.3s ease, max-height 0.3s ease;
        }

        .field-group.hidden {
            opacity: 0;
            max-height: 0;
            margin: 0;
            padding: 0;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff7eb3 0%, #ff758c 100%);
            color: #fff;
            border: none;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(255, 126, 179, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #ff6ba3 0%, #ff6a7a 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 126, 179, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .error-message {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(255, 82, 82, 0.1) 100%);
            color: #d32f2f;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            text-align: center;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            border: 2px solid rgba(255, 107, 107, 0.3);
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.1);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #000;
            text-decoration: none;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: #000;
            text-decoration: underline;
            opacity: 0.8;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to access your account</p>
        </div>

        <?php if($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="role">Select Role:</label>
                <div class="custom-dropdown" id="customRoleDropdown">
                    <input type="hidden" name="role" id="role" value="<?= htmlspecialchars($role) ?>" class="role-hidden-input" required>
                    <button type="button" class="custom-dropdown-btn" id="roleDropdownBtn">
                        <span class="selected-text">
                            <span class="item-icon"><?= $role === 'admin' ? '👨‍💼' : '🎓' ?></span>
                            <span class="item-text"><?= $role === 'admin' ? 'Admin' : 'Student' ?></span>
                        </span>
                        <span class="dropdown-icon">▼</span>
                    </button>
                    <div class="custom-dropdown-menu" id="roleDropdownMenu">
                        <div class="custom-dropdown-item <?= $role === 'admin' ? 'selected' : '' ?>" data-value="admin" data-icon="👨‍💼">
                            <span class="item-icon">👨‍💼</span>
                            <span class="item-text">Admin</span>
                        </div>
                        <div class="custom-dropdown-item <?= $role === 'student' ? 'selected' : '' ?>" data-value="student" data-icon="🎓">
                            <span class="item-icon">🎓</span>
                            <span class="item-text">Student</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Fields -->
            <div id="adminFields" class="form-group field-group" style="<?= $role === 'admin' ? '' : 'display: none;' ?>">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" 
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" 
                       <?= $role === 'admin' ? 'required' : '' ?> 
                       placeholder="Enter your username">
            </div>

            <!-- Student Fields -->
            <div id="studentFields" class="form-group field-group" style="<?= $role === 'student' ? '' : 'display: none;' ?>">
                <label for="roll">Roll Number</label>
                <input type="text" name="roll" id="roll" 
                       value="<?= isset($_POST['roll']) ? htmlspecialchars($_POST['roll']) : '' ?>" 
                       <?= $role === 'student' ? 'required' : '' ?> 
                       placeholder="Enter your roll number">
            </div>

            <!-- Password Field (Common) -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>

    <script>
        // Custom Dropdown Functionality
        const dropdownBtn = document.getElementById('roleDropdownBtn');
        const dropdownMenu = document.getElementById('roleDropdownMenu');
        const roleInput = document.getElementById('role');
        const dropdownItems = document.querySelectorAll('.custom-dropdown-item');

        // Toggle dropdown
        dropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
            dropdownBtn.classList.toggle('active');
        });

        // Select dropdown item
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const icon = this.getAttribute('data-icon');
                const text = this.querySelector('.item-text').textContent;

                // Update hidden input
                roleInput.value = value;

                // Update button text
                dropdownBtn.querySelector('.selected-text').innerHTML = `
                    <span class="item-icon">${icon}</span>
                    <span class="item-text">${text}</span>
                `;

                // Update selected state
                dropdownItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');

                // Close dropdown
                dropdownMenu.classList.remove('active');
                dropdownBtn.classList.remove('active');

                // Trigger role switch
                switchRole();
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                dropdownMenu.classList.remove('active');
                dropdownBtn.classList.remove('active');
            }
        });

        // Close dropdown on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdownMenu.classList.remove('active');
                dropdownBtn.classList.remove('active');
            }
        });

        function switchRole() {
            const role = roleInput.value;
            const adminFields = document.getElementById('adminFields');
            const studentFields = document.getElementById('studentFields');
            const usernameInput = document.getElementById('username');
            const rollInput = document.getElementById('roll');

            if (role === 'admin') {
                adminFields.style.display = 'block';
                studentFields.style.display = 'none';
                usernameInput.required = true;
                rollInput.required = false;
                rollInput.value = '';
            } else {
                adminFields.style.display = 'none';
                studentFields.style.display = 'block';
                usernameInput.required = false;
                rollInput.required = true;
                usernameInput.value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            switchRole();
        });
    </script>

</body>
</html>

