<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Producer Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-music"></i> Music Producer Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="samples.php">Samples</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="collaborate.php">Collaborate</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="messages.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a href="profile.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-user"></i>
                        </a>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light me-2">Login</a>
                        <a href="register.php" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container-fluid hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Share Your Music with the World</h1>
                    <p class="lead">Connect with fellow producers, share your projects, and discover new sounds.</p>
                    <a href="register.php" class="btn btn-primary btn-lg">Get Started</a>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/hero-image.jpg" alt="Music Production" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Projects -->
    <div class="container py-5">
        <h2 class="mb-4">Featured Projects</h2>
        <div class="row">
            <!-- Project cards will be dynamically loaded here -->
        </div>
    </div>

    <!-- Latest Samples -->
    <div class="container py-5">
        <h2 class="mb-4">Latest Samples</h2>
        <div class="row">
            <!-- Sample cards will be dynamically loaded here -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Music Producer Hub</h5>
                    <p>Connect, create, and collaborate with music producers worldwide.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="contact.php" class="text-light">Contact</a></li>
                        <li><a href="terms.php" class="text-light">Terms of Service</a></li>
                        <li><a href="privacy.php" class="text-light">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                        <a href="#" class="text-light me-2"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html> 