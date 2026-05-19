<?php
// profile.php — with current password required to change password
$page_title = 'My Profile';
include 'includes/header.php';
include 'includes/connection.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$uid    = (int)$_SESSION['user_id'];
$errors = [];

// ── Handle Update ─────────────────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $current_pw = $_POST['current_password'] ?? '';
    $new_pw     = $_POST['new_password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';

    if (empty($name))                               $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';

    if (empty($errors)) {
        $e   = mysqli_real_escape_string($conn, $email);
        $chk = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM users WHERE email='$e' AND id != $uid"));
        if ($chk) $errors[] = 'That email is already used by another account.';
    }

    // Only validate password section if user is trying to change it
    $changing_password = !empty($current_pw) || !empty($new_pw) || !empty($confirm);

    if ($changing_password) {
        if (empty($current_pw)) {
            $errors[] = 'Current password is required to set a new one.';
        } else {
            $row = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT password FROM users WHERE id=$uid"));
            if (!password_verify($current_pw, $row['password'])) {
                $errors[] = 'Your current password is incorrect.';
            }
        }
        if (empty($new_pw))        $errors[] = 'New password cannot be empty.';
        elseif (strlen($new_pw)<6) $errors[] = 'New password must be at least 6 characters.';
        if ($new_pw !== $confirm)  $errors[] = 'New passwords do not match.';
    }

    if (empty($errors)) {
        $n = mysqli_real_escape_string($conn, $name);
        $e = mysqli_real_escape_string($conn, $email);
        if ($changing_password) {
            $hashed = password_hash($new_pw, PASSWORD_BCRYPT);
            mysqli_query($conn, "UPDATE users SET name='$n', email='$e', password='$hashed' WHERE id=$uid");
        } else {
            mysqli_query($conn, "UPDATE users SET name='$n', email='$e' WHERE id=$uid");
        }
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['success']    = 'Profile updated successfully!';
        header('Location: profile.php'); exit;
    }
}

// ── Handle Delete ─────────────────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    mysqli_query($conn, "DELETE FROM users WHERE id=$uid");
    session_destroy();
    header('Location: index.php'); exit;
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));
?>

<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-person-circle me-2"></i>My Profile</h1>
        <p>View and manage your account details</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">

        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-banner"></div>
                <div class="profile-body">
                    <div class="profile-avatar-wrap">
                        <div class="profile-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
                    </div>
                    <h2 class="profile-name"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="profile-email"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($user['email']) ?></p>
                    <hr class="divider">
                    <div class="d-flex flex-column gap-2 text-muted small">
                        <div><i class="bi bi-hash me-2"></i>User ID: <strong class="text-dark">#<?= $user['id'] ?></strong></div>
                        <?php if (isset($user['created_at'])): ?>
                        <div><i class="bi bi-calendar3 me-2"></i>Joined: <strong class="text-dark"><?= date('M d, Y', strtotime($user['created_at'])) ?></strong></div>
                        <?php endif; ?>
                    </div>
                    <hr class="divider">
                    <button class="btn btn-outline-danger w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-2"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profile
                </div>
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger rounded-3 small py-2 mb-4">
                            <?php foreach ($errors as $err): ?>
                                <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($err) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" novalidate>
                        <input type="hidden" name="action" value="update">

                        <!-- Name + Email -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="name"
                                           value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" name="email"
                                           value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <hr class="divider">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="fw-600 small mb-0">
                                <i class="bi bi-shield-lock me-1 text-primary"></i>
                                Change Password
                                <span class="fw-400 text-muted">(leave blank to keep current)</span>
                            </p>
                            <a href="forgot-password.php" class="small text-primary text-decoration-none fw-600">
                                <i class="bi bi-question-circle me-1"></i>Forgot password?
                            </a>
                        </div>

                        <!-- Current Password (required to change) -->
                        <div class="mb-3">
                            <label class="form-label">
                                Current Password
                                <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem">Required to change</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control" id="current_password"
                                       name="current_password" placeholder="Enter your current password">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password"
                                           name="new_password" placeholder="Min. 6 characters">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <div style="background:#e2e8f0;border-radius:2px;height:4px;">
                                        <div id="passwordStrength" class="password-strength" style="width:0;"></div>
                                    </div>
                                    <small id="passwordStrengthText" class="fw-600" style="font-size:0.75rem;"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="confirm_password"
                                           name="confirm_password" placeholder="Repeat new password">
                                </div>
                            </div>
                        </div>

                        <div class="alert rounded-3 small py-2 mt-3 d-flex align-items-start gap-2"
                             style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <div>You must enter your <strong>current password</strong> before setting a new one.
                            Forgot it? Use the <a href="forgot-password.php" class="fw-600">Forgot Password</a> link to reset via OTP.</div>
                        </div>

                        <hr class="divider">
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check2 me-2"></i>Save Changes
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Are you sure? This action <strong>cannot be undone</strong>.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Delete My Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
