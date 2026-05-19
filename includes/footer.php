<?php // includes/footer.php ?>

<!-- Footer -->
<footer class="site-footer mt-auto">
    <div class="container">
        <div class="row align-items-center py-4">
            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                <a href="index.php" class="footer-brand d-flex align-items-center gap-2 text-decoration-none">
                    <i class="bi bi-hexagon-fill"></i>
                    <span>MyWebsite</span>
                </a>
                <p class="text-muted small mt-1 mb-0">A modern PHP web application.</p>
            </div>

            <div class="col-md-4 text-center mb-3 mb-md-0">
                <div class="footer-links">
                    <a href="index.php">Home</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="profile.php">Profile</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4 text-center text-md-end">
                <p class="text-muted small mb-0">
                    &copy; <?= date('Y') ?> MyWebsite. Built with
                    <i class="bi bi-heart-fill text-danger mx-1" style="font-size:0.75rem"></i>
                    and PHP + Bootstrap
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>
</body>
</html>
