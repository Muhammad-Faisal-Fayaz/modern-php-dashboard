<?php
// reset-password.php — Step 3: Set new password after OTP verified
$page_title = 'Reset Password';
include 'includes/header.php';
include 'includes/connection.php';

// Must have passed OTP verification
if (empty($_SESSION['otp_verified']) || empty($_SESSION['otp_email'])) {
    header('Location: forgot-password.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pw  = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($new_pw))        $errors[] = 'New password is required.';
    elseif (strlen($new_pw)<6) $errors[] = 'Password must be at least 6 characters.';
    if ($new_pw !== $confirm)  $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $hashed = password_hash($new_pw, PASSWORD_BCRYPT);
        $e      = mysqli_real_escape_string($conn, $_SESSION['otp_email']);
        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE email='$e'");

        // Clean up session flags
        unset($_SESSION['otp_verified'], $_SESSION['otp_email']);

        $_SESSION['success'] = 'Password reset successfully! Please log in with your new password.';
        header('Location: login.php');
        exit;
    }
}
?>

<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center">
                <div class="auth-icon" style="background:rgba(16,185,129,0.12);color:#059669;">
                    <i class="bi bi-key"></i>
                </div>
                <h2>New Password</h2>
                <p class="subtitle">
                    OTP verified! Choose a strong new password for<br>
                    <strong><?= htmlspecialchars($_SESSION['otp_email']) ?></strong>
                </p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 small py-2 mb-3">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password"
                               name="new_password" placeholder="Min. 6 characters" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <!-- Strength bar -->
                    <div class="mt-2">
                        <div style="background:#e2e8f0;border-radius:2px;height:4px;">
                            <div id="passwordStrength" class="password-strength" style="width:0;"></div>
                        </div>
                        <small id="passwordStrengthText" class="fw-600" style="font-size:0.75rem;"></small>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="confirm_password"
                               name="confirm_password" placeholder="Repeat new password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="confirm_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div id="matchMsg" class="small mt-1"></div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Live confirm-match feedback
document.getElementById('confirm_password').addEventListener('input', function() {
    const pw  = document.getElementById('password').value;
    const msg = document.getElementById('matchMsg');
    if (this.value === '') { msg.textContent = ''; return; }
    if (this.value === pw) {
        msg.innerHTML = '<span style="color:#10b981"><i class="bi bi-check-circle me-1"></i>Passwords match</span>';
    } else {
        msg.innerHTML = '<span style="color:#ef4444"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
