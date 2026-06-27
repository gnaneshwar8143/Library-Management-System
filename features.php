<?php
    include "./config/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Features - Library Management System</title>
    <link rel="stylesheet" href="assets/css/features.css" />
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="index.php" class="navbar-logo">
            <img src="assets/icons/book.svg" class="logo-icon" alt="Library Icon">
            LibraryManagement
        </a>
        <ul class="navbar-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="features.php">Features</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <a href="login.php" class="login-btn">Login</a>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Features that Make Learning Simple</h1>
        <p>Modern, fast, and designed for effortless borrowing.</p>
    </section>

    <!-- Features Grid -->
    <section class="features-section">
        <div class="features-container">
            <div class="feature-card">
                <img src="assets/icons/layout.svg" class="icon feature-icon" alt="UI Icon">
                <h3>Modern UI</h3>
                <p>Beautiful, intuitive interface designed with user experience in mind. Clean layouts and smooth animations make navigation effortless.</p>
            </div>

            <div class="feature-card">
                <img src="assets/icons/shield.svg" class="icon feature-icon" alt="Security Icon">
                <h3>Secure Authentication</h3>
                <p>Robust security system with role-based access control. Your data and library resources are protected with industry-standard encryption.</p>
            </div>

            <div class="feature-card">
                <img src="assets/icons/zap.svg" class="icon feature-icon" alt="Speed Icon">
                <h3>Fast & Lightweight</h3>
                <p>Optimized performance ensures quick page loads and smooth interactions. Experience lightning-fast searches and instant results.</p>
            </div>

            <div class="feature-card">
                <img src="assets/icons/layers.svg" class="icon feature-icon" alt="Package Icon">
                <h3>Multi-Format Support</h3>
                <p>Support for various content formats including books, audiobooks, documents, and digital resources. Access your preferred format seamlessly.</p>
            </div>

            <div class="feature-card">
                <img src="assets/icons/user.svg" class="icon feature-icon" alt="User Icon">
                <h3>Role-Based Access</h3>
                <p>Separate dashboards for students and administrators. Each role has tailored features and permissions for optimal functionality.</p>
            </div>

            <div class="feature-card">
                <img src="assets/icons/infinity.svg" class="icon feature-icon" alt="Infinity Icon">
                <h3>No Stock Limit</h3>
                <p>Unlimited digital resources with no physical constraints. Borrow multiple items simultaneously without worrying about availability.</p>
            </div>

            <div class="feature-card">
                <img src="assets/icons/refresh-ccw.svg" class="icon feature-icon" alt="Refresh Icon">
                <h3>AJAX Enhancements</h3>
                <p>Dynamic content loading without page refreshes. Real-time updates and seamless interactions enhance your browsing experience.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3 class="footer-logo">LibraryManagement</h3>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="features.php">Features</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="index.php">Login</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Contact Info</h4>
                <p>Email: support@librarymanagement.com</p>
            </div>
            <div class="footer-column">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" class="social-icon"><img src="assets/icons/book.svg" class="icon" alt="Facebook"></a>
                    <a href="#" class="social-icon"><img src="assets/icons/file-text.svg" class="icon" alt="Instagram"></a>
                    <a href="#" class="social-icon"><img src="assets/icons/search.svg" class="icon" alt="Twitter"></a>
                    <a href="#" class="social-icon"><img src="assets/icons/user.svg" class="icon" alt="LinkedIn"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 LibraryManagement — All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

