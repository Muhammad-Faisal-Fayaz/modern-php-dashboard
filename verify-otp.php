<?php
// verify-otp.php — Step 2: Enter the 6-digit OTP
$page_title = 'Verify OTP';
include 'includes/header.php';
include 'includes/connection.php';

// Must have an email in session from previous step
if (empty($_SESSION['otp_email'])) {
    header('Location: forgot-password.php');
    exit;
}

$email = $_SESSION['otp_email'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Combine 6 individual digit inputs into one string
    $digits = [];
    for ($i = 1; $i <= 6; $i++) {
        $digits[] = trim($_POST["d$i"] ?? '');
    }
    $entered_otp = implode('', $digits);

    if (strlen($entered_otp) !== 6 || !ctype_digit($entered_otp)) {
        $error = 'Please enter all 6 digits of your OTP.';
    } else {
        $e    = mysqli_real_escape_string($conn, $email);
        $row  = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT otp, otp_expires FROM users WHERE email='$e'"));

        if (!$row || empty($row['otp'])) {
            $error = 'No OTP found. Please request a new one.';
        } elseif (strtotime($row['otp_expires']) < time()) {
            $error = 'This OTP has expired. Please request a new one.';
            mysqli_query($conn, "UPDATE users SET otp=NULL, otp_expires=NULL WHERE email='$e'");
        } elseif (!password_verify($entered_otp, $row['otp'])) {
            $error = 'Incorrect OTP. Please try again.';
        } else {
            // OTP verified — clear it and allow reset
            mysqli_query($conn, "UPDATE users SET otp=NULL, otp_expires=NULL WHERE email='$e'");
            $_SESSION['otp_verified'] = true; // flag for reset page
            header('Location: reset-password.php');
            exit;
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center">
                <div class="auth-icon"><i class="bi bi-shield-check"></i></div>
                <h2>Enter OTP</h2>
                <p class="subtitle">
                    Enter the 6-digit code sent for<br>
                    <strong><?= htmlspecialchars($email) ?></strong>
                </p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3 small py-2">
                    <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="otpForm" novalidate>
                <!-- 6 individual digit boxes -->
                <div class="otp-boxes mb-4">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <input type="text" class="otp-box" name="d<?= $i ?>"
                               maxlength="1" pattern="[0-9]" inputmode="numeric"
                               autocomplete="off" required>
                    <?php endfor; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Verify OTP
                </button>
            </form>

            <hr class="divider">
            <p class="text-center text-muted small mb-0">
                Didn't receive it?
                <a href="forgot-password.php" class="fw-600 text-primary text-decoration-none">
                    Resend OTP
                </a>
            </p>
        </div>
    </div>
</div>

<style>
.otp-boxes {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.otp-box {
    width: 52px;
    height: 60px;
    text-align: center;
    font-size: 1.6rem;
    font-weight: 700;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    outline: none;
    font-family: monospace;
    background: #fafafa;
    transition: border-color 0.2s, box-shadow 0.2s;
    color: #0f172a;
}

.otp-box:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
    background: #fff;
}

.otp-box.filled {
    border-color: #4f46e5;
    background: #eef2ff;
}
</style>

<script>
// Auto-advance between OTP boxes
const boxes = document.querySelectorAll('.otp-box');

boxes.forEach((box, idx) => {
    box.addEventListener('input', () => {
        // Allow only digits
        box.value = box.value.replace(/\D/g, '').slice(0, 1);

        if (box.value) {
            box.classList.add('filled');
            if (idx < boxes.length - 1) boxes[idx + 1].focus();
        } else {
            box.classList.remove('filled');
        }

        // Auto-submit when all filled
        if ([...boxes].every(b => b.value)) {
            document.getElementById('otpForm').submit();
        }
    });

    // Backspace goes to previous box
    box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && idx > 0) {
            boxes[idx - 1].focus();
            boxes[idx - 1].value = '';
            boxes[idx - 1].classList.remove('filled');
        }
    });

    // Handle paste of full OTP
    box.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
        [...pasted].forEach((char, i) => {
            if (boxes[i]) {
                boxes[i].value = char;
                boxes[i].classList.add('filled');
            }
        });
        if (pasted.length === 6) document.getElementById('otpForm').submit();
    });
});

// Focus first box on load
boxes[0].focus();
</script>

<?php include 'includes/footer.php'; ?>
