<?php
// register.php
$page_title = 'Register';
include 'includes/header.php';
include 'includes/connection.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($name))              $errors[] = 'Name is required.';
    if (strlen($name) < 2)         $errors[] = 'Name must be at least 2 characters.';
    if (empty($email))             $errors[] = 'Email is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if (strlen($password) < 6)     $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)    $errors[] = 'Passwords do not match.';

    // Check duplicate email
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'");
        if (mysqli_num_rows($check) > 0) $errors[] = 'This email is already registered.';
    }

    // Insert user
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $name_safe  = mysqli_real_escape_string($conn, $name);
        $email_safe = mysqli_real_escape_string($conn, $email);
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name_safe', '$email_safe', '$hashed')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = 'Account created! Please log in.';
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center">
                <div class="auth-icon"><i class="bi bi-person-plus"></i></div>
                <h2>Create Account</h2>
                <p class="subtitle">Join us today — it's free!</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 small py-2">
                    <?php foreach ($errors as $e): ?>
                        <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Your full name"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Min. 6 characters" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <!-- Password Strength Bar -->
                    <div class="mt-2">
                        <div style="background:#e2e8f0; border-radius:2px; height:4px;">
                            <div id="passwordStrength" class="password-strength" style="width:0;"></div>
                        </div>
                        <small id="passwordStrengthText" class="fw-600" style="font-size:0.75rem;"></small>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="confirm_password"
                               name="confirm_password" placeholder="Repeat your password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>
            </form>

            <hr class="divider">
            <p class="text-center text-muted small mb-0">
                Already have an account? <a href="login.php" class="fw-600 text-primary text-decoration-none">Sign In</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
