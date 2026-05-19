<?php
// index.php
$page_title = 'Home';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i> PHP + Bootstrap 5
                </div>
                <h1>Build Modern<br><span>Web Applications</span><br>with PHP</h1>
                <p class="hero-subtitle">
                    A fully responsive, modular PHP web application featuring user
                    authentication, profile management, and a clean Bootstrap 5 interface.
                </p>
                <div class="hero-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-grid me-2"></i>Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="register.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Get Started
                        </a>
                        <a href="login.php" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="hero-card">
                        <div class="hero-stat">
                            <div class="hero-stat-icon purple"><i class="bi bi-shield-check"></i></div>
                            <div>
                                <div class="hero-stat-label">Security</div>
                                <div class="hero-stat-value">Password Hashing</div>
                            </div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-icon cyan"><i class="bi bi-layout-text-window"></i></div>
                            <div>
                                <div class="hero-stat-label">Design</div>
                                <div class="hero-stat-value">Responsive UI</div>
                            </div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-icon green"><i class="bi bi-database"></i></div>
                            <div>
                                <div class="hero-stat-label">Backend</div>
                                <div class="hero-stat-value">MySQL + PHP</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-tag">Features</span>
            <h2 class="section-title">Everything you need</h2>
            <p class="text-muted">A complete, production-ready PHP application with modern practices.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon fi-purple"><i class="bi bi-person-check"></i></div>
                    <h5>User Auth</h5>
                    <p>Secure registration and login with session management and hashed passwords.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon fi-cyan"><i class="bi bi-pencil-square"></i></div>
                    <h5>Profile Management</h5>
                    <p>Users can view and update their profile information or delete their account.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon fi-green"><i class="bi bi-table"></i></div>
                    <h5>User Directory</h5>
                    <p>Browse all registered users in a searchable, styled table with avatars.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon fi-orange"><i class="bi bi-shield-lock"></i></div>
                    <h5>Secure & Modular</h5>
                    <p>Includes/connection separated, password hashing, and form validation throughout.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
