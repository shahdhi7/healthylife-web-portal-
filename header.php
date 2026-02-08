<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthyLife Hospital</title>
    <!-- Use Base Tag or absolute paths for assets -->
    <?php
    // Use dynamic base from APPROOT constant defined in index.php
    $base = APPROOT . '/';
    ?>
    <base href="<?= $base ?>">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <header class="main-header">
        <div class="container navbar">
            <div class="logo">
                <a href="home" class="flex items-center">
                    <img src="images/logo.png" alt="Logo" class="logo-img" style="height: 50px;">
                    <span class="logo-text">HealthyLife</span>
                </a>
            </div>

            <!-- Search Bar Removed -->

            <nav class="nav-links">
                <a href="home" class="<?= $view == 'home' ? 'active' : '' ?>">Home</a>
                <a href="doctors" class="<?= $view == 'doctors' ? 'active' : '' ?>">Discover Medical Specialist</a>
                <a href="services" class="<?= $view == 'services' ? 'active' : '' ?>">Services</a>
                <a href="home#contact" class="<?= $view == 'contact' ? 'active' : '' ?>">Contact</a>
            </nav>

            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard" class="btn btn-primary">Dashboard</a>
                    <a href="logout" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="login" class="btn btn-secondary">Sign In</a>
                    <a href="register" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>

            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>
    <main>