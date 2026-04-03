<?php
$search = $_GET['search'] ?? '';

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    if (deleteUser($delete_id)) {
        $_SESSION['alert_success'] = 'User deleted successfully!';
        header("Location: ./?page=admin/users");
        exit;
    }
}

// Handle role toggle
if (isset($_GET['toggle_role_id'])) {
    $toggle_id = $_GET['toggle_role_id'];
    if (toggleUserRole($toggle_id)) {
        $_SESSION['alert_success'] = 'User role updated successfully!';
        header("Location: ./?page=admin/users");
        exit;
    }
}

$users = getAllUsers($search, false); // Show ALL users in the table, including admins
$stats = getUserStats();
?>

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-1"><i class="fas fa-users-cog me-2 text-primary"></i>User Governance</h2>
        </div>
        <div class="col-auto">
            <a href="./?page=admin/create_user" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span>Register New User</span>
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4 rounded-4 h-100 border-start border-primary border-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h3 fw-bold mb-0 text-primary"><?= $stats['total'] ?></div>
                        <div class="small fw-semibold text-muted text-uppercase">Total Registered</div>
                    </div>
                    <i class="fas fa-users fa-2x opacity-10 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4 rounded-4 h-100 border-start border-success border-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h3 fw-bold mb-0 text-success"><?= $stats['users'] ?></div>
                        <div class="small fw-semibold text-muted text-uppercase">Standard Users</div>
                    </div>
                    <i class="fas fa-user fa-2x opacity-10 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4 rounded-4 h-100 border-start border-danger border-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h3 fw-bold mb-0 text-danger"><?= $stats['admins'] ?></div>
                        <div class="small fw-semibold text-muted text-uppercase">Administrators</div>
                    </div>
                    <i class="fas fa-user-shield fa-2x opacity-10 text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['alert_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div><?= $_SESSION['alert_success'] ?></div>
            </div>
            <?php unset($_SESSION['alert_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white py-4 border-0">
            <form method="GET" class="row align-items-center g-3">
                <input type="hidden" name="page" value="admin/users">
                <div class="col-12 col-md-5">
                    <h5 class="mb-0 fw-bold">Active User Directory</h5>
                </div>
                <div class="col-12 col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2" placeholder="Search by name or email address..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-primary px-4">Search</button>
                        <?php if ($search): ?>
                        <a href="./?page=admin/users" class="btn btn-light border-0"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4 py-3">User Profile</th>
                            <th class="py-3">Role Status</th>
                            <th class="py-3">Created At</th>
                            <th class="pe-4 py-3 text-end">Management Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted fw-medium fs-5 mb-0">No matching users found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): 
                                $isSelf = ($u->id === loggedInUser()->id);
                            ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-primary-subtle" 
                                                 style="width: 45px; height: 45px; font-weight: 700;">
                                                <?= strtoupper(substr($u->name, 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($u->name) ?> <?= $isSelf ? '<span class="badge bg-dark-subtle text-dark ms-1 small" style="font-size: 10px;">You</span>' : '' ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($u->email) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="badge rounded-pill border-0 <?= $u->role === 'admin' ? 'bg-danger text-white' : 'bg-primary text-white' ?> px-3 py-2 dropdown-toggle <?= $isSelf ? 'disabled' : '' ?>" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px; cursor: <?= $isSelf ? 'default' : 'pointer' ?>;">
                                                <?= ucfirst($u->role) ?>
                                            </button>
                                            <?php if (!$isSelf): ?>
                                            <ul class="dropdown-menu shadow border-0 p-2 rounded-3">
                                                <li><h6 class="dropdown-header small text-uppercase opacity-50">Switch Role To</h6></li>
                                                <li><a class="dropdown-item rounded-2 small mb-1 <?= $u->role === 'admin' ? 'active disabled' : '' ?>" href="./?page=admin/users&toggle_role_id=<?= $u->id ?>"><i class="fas fa-user-shield me-2"></i>Admin</a></li>
                                                <li><a class="dropdown-item rounded-2 small <?= $u->role !== 'admin' ? 'active disabled' : '' ?>" href="./?page=admin/users&toggle_role_id=<?= $u->id ?>"><i class="fas fa-user me-2"></i>Standard User</a></li>
                                            </ul>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-muted small fw-medium"><?= date('F d, Y', strtotime($u->created_at)) ?></td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="./?page=admin/edit_user&id=<?= $u->id ?>" class="btn btn-outline-primary btn-sm rounded-3 px-3 shadow-sm" title="Edit Profile">
                                                <i class="fas fa-user-edit"></i>
                                            </a>
                                            <?php if (!$isSelf): ?>
                                            <a href="./?page=admin/users&delete_id=<?= $u->id ?>" class="btn btn-white btn-sm text-danger border border-danger-subtle rounded-3 px-3 shadow-sm btn_delete_user" title="Delete User">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <?php else: ?>
                                            <button class="btn btn-light btn-sm rounded-3 px-3 shadow-sm disabled" title="Cannot delete yourself">
                                                <i class="fas fa-trash-alt opacity-25"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { confirmAction } from './assets/js/utils.js'

confirmAction('.btn_delete_user', {
    title: "Permanently Delete User?",
    text: "This will remove the user and all their associated schedules. This action cannot be undone.",
    icon: "error",
    confirmText: "Yes, delete permanently!",
    confirmButtonColor: "#dc3545"
})
</script>