<?php
// login.php
$page_title = 'Login';
include 'includes/header.php';
include 'includes/connection.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $email_safe = mysqli_real_escape_string($conn, $email);
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_safe'");
        $user   = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email']= $user['email'];
            $_SESSION['success']   = 'Welcome back, ' . htmlspecialchars($user['name']) . '!';
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center">
                <div class="auth-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                <h2>Welcome Back</h2>
                <p class="subtitle">Sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3 small py-2">
                    <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Your password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>

            <hr class="divider">
            <p class="text-center text-muted small mb-0">
                Don't have an account? <a href="register.php" class="fw-600 text-primary text-decoration-none">Create one</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
