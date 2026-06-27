<?php
    include "./config/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About - Library Management System</title>
    <link rel="stylesheet" href="assets/css/about.css" />
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
        <h1>About Us</h1>
        <p>Empowering students with modern library management solutions</p>
    </section>

    <!-- History Section -->
    <section class="history-section">
        <div class="history-content">
            <h2 class="section-title">How It Started</h2>
            <p>LibraryManagement was created with a simple yet powerful vision: to make learning resources accessible to every student. We recognized the challenges students face in accessing books, documents, and audio materials in traditional library systems. Our platform bridges this gap by providing a modern, digital-first approach to library management that combines ease of use with comprehensive resource access.</p>
            <p>Built with cutting-edge technology and user-centric design, we've transformed the way students interact with library resources. From browsing extensive catalogs to seamless borrowing experiences, LibraryManagement is designed to support your academic journey every step of the way.</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <h2 class="section-title">Our Mission</h2>
        <div class="mission-container">
            <div class="mission-card">
                <img src="assets/icons/book.svg" class="icon mission-icon" alt="Book Icon">
                <h3>Improve Access</h3>
                <p>Make learning resources easily accessible to all students, breaking down barriers to knowledge and education.</p>
            </div>
            <div class="mission-card">
                <img src="assets/icons/user.svg" class="icon mission-icon" alt="User Icon">
                <h3>Support Students</h3>
                <p>Provide tools and resources that empower students to excel in their academic pursuits and personal growth.</p>
            </div>
            <div class="mission-card">
                <img src="assets/icons/globe.svg" class="icon mission-icon" alt="Globe Icon">
                <h3>Modern Digital Library</h3>
                <p>Create a seamless digital experience that combines traditional library values with modern technology.</p>
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

