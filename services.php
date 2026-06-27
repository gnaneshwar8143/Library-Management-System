<?php
    include "./config/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Services - Library Management System</title>
    <link rel="stylesheet" href="assets/css/services.css" />
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
        <h1>Our Services</h1>
        <p>Comprehensive library management solutions designed to enhance your learning experience</p>
    </section>

    <!-- Services Grid -->
    <section class="services-section">
        <div class="services-container">
            <div class="service-card">
                <img src="assets/icons/book.svg" class="icon service-icon" alt="Book Icon">
                <h3>Book Catalog</h3>
                <p>Browse through our extensive collection of books across various genres and subjects. Search, filter, and discover your next great read.</p>
            </div>

            <div class="service-card">
                <img src="assets/icons/headphones.svg" class="icon service-icon" alt="Headphones Icon">
                <h3>Audio Book Library</h3>
                <p>Access a wide range of audiobooks for learning on the go. Listen to your favorite titles anytime, anywhere.</p>
            </div>

            <div class="service-card">
                <img src="assets/icons/file-text.svg" class="icon service-icon" alt="Document Icon">
                <h3>Document Library</h3>
                <p>Explore our digital document collection including research papers, study materials, and reference documents.</p>
            </div>

            <div class="service-card">
                <img src="assets/icons/refresh-ccw.svg" class="icon service-icon" alt="Refresh Icon">
                <h3>Borrowing System</h3>
                <p>Simple and efficient book borrowing system. Track your borrowed items, return dates, and manage your library account easily.</p>
            </div>

            <div class="service-card">
                <img src="assets/icons/user.svg" class="icon service-icon" alt="User Icon">
                <h3>Student Dashboard</h3>
                <p>Personalized dashboard for students to manage their library activities, view borrowing history, and access resources.</p>
            </div>

            <div class="service-card">
                <img src="assets/icons/settings.svg" class="icon service-icon" alt="Settings Icon">
                <h3>Admin Dashboard</h3>
                <p>Comprehensive admin panel for managing books, users, borrowing records, and maintaining the library system efficiently.</p>
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

