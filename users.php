<?php
// users.php — Feature 1: Styled User Directory Table
$page_title = 'All Users';
include 'includes/header.php';
include 'includes/connection.php';

// Auth guard
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
$count = mysqli_num_rows($users);
?>

<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-people me-2"></i>User Directory</h1>
        <p><?= $count ?> registered user<?= $count !== 1 ? 's' : '' ?></p>
    </div>
</div>

<div class="container pb-5">
    <div class="table-card">
        <div class="table-card-header">
            <h2 class="table-card-title"><i class="bi bi-list-ul me-2 text-primary"></i>All Users</h2>
            <!-- Live Search -->
            <div class="input-group" style="max-width:280px;">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="tableSearch" class="form-control border-start-0 ps-0"
                       placeholder="Search users..." style="border-left:none !important;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($count === 0): ?>
                        <tr><td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>No users found.</p>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php $i = 1; while ($u = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td class="text-muted small"><?= $i++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="background:hsl(<?= (crc32($u['name']) % 360) ?>,55%,50%)">
                                        <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-600 small"><?= htmlspecialchars($u['name']) ?></div>
                                        <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                            <span class="badge bg-primary badge-pill" style="font-size:0.65rem">You</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="text-muted small">
                                <?= isset($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '—' ?>
                            </td>
                            <td class="text-end">
                                <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                    <a href="profile.php" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
