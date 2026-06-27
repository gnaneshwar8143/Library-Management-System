<?php
    include "./config/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Library Management System</title>
    <link rel="stylesheet" href="assets/css/landing.css" />
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
        <div class="hero-left">
            <h1>“ Discover books <span class="highlight-word">documents and  </span class="highlight-word"> audio learning — all in one place ”</h1>
            <p>Browse our extensive collection of books, documents, and audio resources designed to enhance your learning journey.</p>
            <div class="slider-container">
                <div class="slider-track">
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/book.svg" class="icon pill-icon" alt="Book Icon">
                        <span class="category-text">Browse Catalog</span>
                    </a>
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/search.svg" class="icon pill-icon" alt="Search Icon">
                        <span class="category-text">Advanced Search</span>
                    </a>
                    <a href="#" class="category-pill">
                        <img src="assets/icons/bookmark.svg" class="icon pill-icon" alt="Bookmark Icon">
                        <span class="category-text">My Reading List</span>
                    </a>
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/headphones.svg" class="icon pill-icon" alt="Headphones Icon">
                        <span class="category-text">Audio Books</span>
                    </a>
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/file-text.svg" class="icon pill-icon" alt="Document Icon">
                        <span class="category-text">E-Books & PDFs</span>
                    </a>
                    <!-- Duplicate for seamless loop -->
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/book.svg" class="icon pill-icon" alt="Book Icon">
                        <span class="category-text">Browse Catalog</span>
                    </a>
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/search.svg" class="icon pill-icon" alt="Search Icon">
                        <span class="category-text">Advanced Search</span>
                    </a>
                    <a href="#" class="category-pill">
                        <img src="assets/icons/bookmark.svg" class="icon pill-icon" alt="Bookmark Icon">
                        <span class="category-text">My Reading List</span>
                    </a>
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/headphones.svg" class="icon pill-icon" alt="Headphones Icon">
                        <span class="category-text">Audio Books</span>
                    </a>
                    <a href="services.php" class="category-pill">
                        <img src="assets/icons/file-text.svg" class="icon pill-icon" alt="Document Icon">
                        <span class="category-text">E-Books & PDFs</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-right">
            <img src="assets/images/landing_hero.webp" alt="Library Hero" class="hero-image" />
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-box">
                <img src="assets/icons/book.svg" class="icon stat-icon" alt="Books Icon">
                <div class="stat-number">1,000+</div>
                <div class="stat-label">Books Accessed</div>
            </div>
            <div class="stat-box">
                <img src="assets/icons/headphones.svg" class="icon stat-icon" alt="Audio Icon">
                <div class="stat-number">50+</div>
                <div class="stat-label">Audio Lessons</div>
            </div>
            <div class="stat-box">
                <img src="assets/icons/user.svg" class="icon stat-icon" alt="Users Icon">
                <div class="stat-number">1,200+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-box">
                <img src="assets/icons/shield.svg" class="icon stat-icon" alt="Security Icon">
                <div class="stat-number">100%</div>
                <div class="stat-label">Secure Access</div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <h2 class="section-title">What Students Say</h2>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <img src="assets/icons/user.svg" class="icon testimonial-image" alt="User Icon">
                <div class="testimonial-rating">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                </div>
                <p class="testimonial-text">"The easiest way to access books and audio learning material."</p>
                <div class="testimonial-author">Amit, CSE</div>
            </div>
            <div class="testimonial-card">
                <img src="assets/icons/user.svg" class="icon testimonial-image" alt="User Icon">
                <div class="testimonial-rating">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                </div>
                <p class="testimonial-text">"Simple interface and great collection of resources. Highly recommended!"</p>
                <div class="testimonial-author">Priya, ECE</div>
            </div>
            <div class="testimonial-card">
                <img src="assets/icons/user.svg" class="icon testimonial-image" alt="User Icon">
                <div class="testimonial-rating">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                    <img src="assets/icons/star.svg" class="icon" alt="Star">
                </div>
                <p class="testimonial-text">"Best library management system I've used. Fast and reliable!"</p>
                <div class="testimonial-author">Rahul, ME</div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-section">
        <h2 class="section-title">Why Choose Us</h2>
        <div class="why-choose-container">
            <div class="why-choose-item">
                <img src="assets/icons/layout.svg" class="icon why-choose-icon" alt="UI Icon">
                <h3>Modern UI</h3>
            </div>
            <div class="why-choose-item">
                <img src="assets/icons/shield.svg" class="icon why-choose-icon" alt="Security Icon">
                <h3>Secure Access</h3>
            </div>
            <div class="why-choose-item">
                <img src="assets/icons/layers.svg" class="icon why-choose-icon" alt="Package Icon">
                <h3>Audio & Document Support</h3>
            </div>
            <div class="why-choose-item">
                <img src="assets/icons/refresh-ccw.svg" class="icon why-choose-icon" alt="Refresh Icon">
                <h3>Smooth Borrowing System</h3>
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
                    <li><a href="login.php">Login</a></li>
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

