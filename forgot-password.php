<?php
// forgot-password.php — Step 1: Enter email → generate OTP
$page_title = 'Forgot Password';
include 'includes/header.php';
include 'includes/connection.php';

$error   = '';
$sent    = false;
$dev_otp = ''; // shown on screen (since no email server in XAMPP)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $e    = mysqli_real_escape_string($conn, $email);
        $user = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM users WHERE email='$e'"));

        if (!$user) {
            // Don't reveal whether email exists — generic message
            $error = 'If that email is registered, an OTP has been sent.';
            $sent  = true; // still show "sent" to avoid user enumeration
        } else {
            // Generate 6-digit OTP
            $otp     = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $otp_hash = password_hash($otp, PASSWORD_BCRYPT); // hash before storing

            mysqli_query($conn,
                "UPDATE users SET otp='$otp_hash', otp_expires='$expires' WHERE email='$e'");

            // Store email in session to carry to next step
            $_SESSION['otp_email'] = $email;

            // In a real app: send $otp via email (PHPMailer / SMTP)
            // For XAMPP/localhost: display it on screen
            $dev_otp = $otp;
            $sent    = true;
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card mx-auto">

            <?php if (!$sent): ?>
            <!-- ── Step 1: Email form ───────────────────────────── -->
            <div class="text-center">
                <div class="auth-icon"><i class="bi bi-envelope-paper"></i></div>
                <h2>Forgot Password</h2>
                <p class="subtitle">Enter your registered email and we'll send you a reset OTP.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3 small py-2">
                    <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" name="email"
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-send me-2"></i>Send OTP
                </button>
            </form>

            <hr class="divider">
            <p class="text-center text-muted small mb-0">
                Remember it? <a href="login.php" class="fw-600 text-primary text-decoration-none">Back to Login</a>
            </p>

            <?php else: ?>
            <!-- ── Step 2: OTP sent confirmation ──────────────── -->
            <div class="text-center">
                <div class="auth-icon" style="background:rgba(16,185,129,0.12);color:#059669;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h2>OTP Sent!</h2>
                <p class="subtitle">A 6-digit code was generated. Enter it on the next page.</p>
            </div>

            <?php if ($dev_otp): ?>
            <!-- ▼ DEV ONLY box — remove in production ▼ -->
            <div class="alert rounded-3 py-3 mb-4 text-center"
                 style="background:#fefce8;border:2px dashed #fbbf24;">
                <p class="small fw-600 text-warning mb-1">
                    <i class="bi bi-code-slash me-1"></i>
                    LOCALHOST MODE — In production, this would be emailed
                </p>
                <p class="mb-1 text-muted small">Your OTP is:</p>
                <div class="otp-display"><?= $dev_otp ?></div>
                <p class="small text-muted mt-2 mb-0">Valid for <strong>10 minutes</strong></p>
            </div>
            <?php endif; ?>

            <a href="verify-otp.php" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-shield-check me-2"></i>Enter OTP Code
            </a>

            <hr class="divider">
            <p class="text-center text-muted small mb-0">
                Didn't get it? <a href="forgot-password.php" class="fw-600 text-primary text-decoration-none">Try again</a>
            </p>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
.otp-display {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: 0.5rem;
    color: #d97706;
    font-family: monospace;
    background: #fff;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    display: inline-block;
    margin: 0.5rem 0;
    border: 1px solid #fde68a;
}
</style>

<?php include 'includes/footer.php'; ?>
