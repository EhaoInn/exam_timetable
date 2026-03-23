<div class="container mt-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark"><i class="fas fa-users-cog me-2 text-primary"></i>User Management</h2>
            <p class="text-muted">Manage system users, roles, and permissions.</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Add New User</span>
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0">124</div>
                        <div class="small">Total Users</div>
                    </div>
                    <i class="fas fa-users fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0">118</div>
                        <div class="small">Active Users</div>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0">6</div>
                        <div class="small">Inactive Users</div>
                    </div>
                    <i class="fas fa-user-slash fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 fw-bold mb-0">12</div>
                        <div class="small">Admins</div>
                    </div>
                    <i class="fas fa-user-shield fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 border-0 border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold">All Users</h5>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control bg-light border-0" placeholder="Search users...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4 py-3">User</th>
                            <th class="py-3">Role</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3">Joined Date</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        JD
                                    </div>
                                    <div>
                                        <div class="fw-bold">John Doe</div>
                                        <div class="small text-muted">john.doe@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Administrator</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success rounded-pill px-3">Active</span>
                            </td>
                            <td class="text-muted small">Oct 12, 2023</td>
                            <td class="pe-4 text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary border-0"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger border-0"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>