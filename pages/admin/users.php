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

$users = getAllUsers($search);
$stats = getUserStats();
?>

<div class="container mt-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark"><i class="fas fa-users-cog me-2 text-primary"></i>User Management</h2>
        </div>
        <div class="col-auto">
            <a href="./?page=admin/create_user" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Add New User</span>
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['alert_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['alert_success'] ?>
            <?php unset($_SESSION['alert_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0"><?= $stats['total'] ?></div>
                        <div class="small">Total Users</div>
                    </div>
                    <i class="fas fa-users fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0"><?= $stats['users'] ?></div>
                        <div class="small">Standard Users</div>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0"><?= $stats['admins'] ?></div>
                        <div class="small">Admins</div>
                    </div>
                    <i class="fas fa-user-shield fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-5">
        <div class="card-header bg-white py-3 border-0 border-bottom">
            <form method="GET" class="row align-items-center g-3">
                <input type="hidden" name="page" value="admin/users">
                <div class="col-12 col-md-4">
                    <h5 class="mb-0 fw-semibold">All Users</h5>
                </div>
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                        <input onchange="this.form.submit()" type="text" name="search" class="form-control bg-light border-0" placeholder="Search by name or email..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <?php if ($search): ?>
                        <a href="./?page=admin/users" class="btn btn-outline-danger border-0 bg-light text-danger"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase fw-bold sticky-top">
                        <tr>
                            <th class="ps-4 py-3">User</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Joined Date</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted fw-medium fs-6 mb-0">No users found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px; font-weight: 600;">
                                                <?= strtoupper(substr($u->name, 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($u->name) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($u->email) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $u->role === 'admin' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' ?> px-2 py-1">
                                            <?= ucfirst($u->role) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small"><?= date('M d, Y', strtotime($u->created_at)) ?></td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="./?page=admin/edit_user&id=<?= $u->id ?>" class="btn btn-outline-secondary border-0"><i class="fas fa-edit"></i></a>
                                            <a href="./?page=admin/users&delete_id=<?= $u->id ?>" class="btn btn-outline-danger border-0 btn_delete_user"><i class="fas fa-trash"></i></a>
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

confirmAction('.btn_delete_user',  {
    title: "Are you sure to delete this user?"
})

</script>