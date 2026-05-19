<?php
// dashboard.php
$page_title = 'Dashboard';
include 'includes/header.php';
include 'includes/connection.php';

// Auth guard
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Stats
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$user_since  = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT created_at FROM users WHERE id=" . (int)$_SESSION['user_id']
))['created_at'];

// Recent 5 users
$recent = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-grid me-2"></i>Dashboard</h1>
                <p>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
            </div>
            <a href="users.php" class="btn btn-outline-light">
                <i class="bi bi-people me-2"></i>View All Users
            </a>
        </div>
    </div>
</div>

<div class="container pb-5">

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon si-purple"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label">Total Users</div>
                    <p class="stat-value"><?= $total_users ?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon si-green"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="stat-label">Your Status</div>
                    <p class="stat-value">Active</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon si-cyan"><i class="bi bi-calendar3"></i></div>
                <div>
                    <div class="stat-label">Member Since</div>
                    <p class="stat-value" style="font-size:1rem">
                        <?= $user_since ? date('M Y', strtotime($user_since)) : 'N/A' ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon si-orange"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="stat-label">Your Email</div>
                    <p class="stat-value" style="font-size:0.85rem; word-break:break-all">
                        <?= htmlspecialchars($_SESSION['user_email']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Users Table -->
        <div class="col-lg-8">
            <div class="table-card">
                <div class="table-card-header">
                    <h2 class="table-card-title"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Registrations</h2>
                    <a href="users.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="background:hsl(<?= (crc32($row['name']) % 360) ?>,60%,50%)">
                                            <?= strtoupper(substr($row['name'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-500"><?= htmlspecialchars($row['name']) ?></span>
                                    </div>
                                </td>
                                <td class="text-muted"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="text-muted small">
                                    <?= isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : 'N/A' ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-lightning-charge me-2 text-primary"></i>Quick Actions</div>
                <div class="card-body d-flex flex-column gap-3">
                    <a href="profile.php" class="btn btn-outline-primary w-100 text-start d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-5"></i>
                        <div>
                            <div class="fw-600">My Profile</div>
                            <small class="text-muted">View or edit your info</small>
                        </div>
                    </a>
                    <a href="users.php" class="btn btn-outline-primary w-100 text-start d-flex align-items-center gap-2">
                        <i class="bi bi-people fs-5"></i>
                        <div>
                            <div class="fw-600">All Users</div>
                            <small class="text-muted">Browse user directory</small>
                        </div>
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger w-100 text-start d-flex align-items-center gap-2 mt-auto">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                        <div>
                            <div class="fw-600">Logout</div>
                            <small>End your session</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
