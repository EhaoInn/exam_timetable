<div class="container py-5">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="fw-semibold mb-0">Admin Panel</h5>
            <p class="text-muted small mb-0">Overview of users and schedules</p>
        </div>
        <a href="./?page=admin/users" class="btn btn-success px-3">
            <i class="fa-solid fa-users-gear me-2"></i>Manage users
        </a>
    </div>

    <!-- Stat cards -->
    <div class="row g-3 mb-5">

        <div class="col-6 col-md-3">
            <div class="card border-0 bg-success bg-opacity-10 rounded-3 text-center py-4 px-3">
                <div class="mb-2">
                    <i class="fa-solid fa-users fa-lg text-success"></i>
                </div>
                <h4 class="fw-bold text-success mb-0">24</h4>
                <p class="text-muted small mb-0">Total users</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10 rounded-3 text-center py-4 px-3">
                <div class="mb-2">
                    <i class="fa-solid fa-calendar-days fa-lg text-primary"></i>
                </div>
                <h4 class="fw-bold text-primary mb-0">12</h4>
                <p class="text-muted small mb-0">Total schedules</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 bg-warning bg-opacity-10 rounded-3 text-center py-4 px-3">
                <div class="mb-2">
                    <i class="fa-solid fa-book fa-lg text-warning"></i>
                </div>
                <h4 class="fw-bold text-warning mb-0">58</h4>
                <p class="text-muted small mb-0">Total subjects</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 bg-danger bg-opacity-10 rounded-3 text-center py-4 px-3">
                <div class="mb-2">
                    <i class="fa-solid fa-file-circle-check fa-lg text-danger"></i>
                </div>
                <h4 class="fw-bold text-danger mb-0">37</h4>
                <p class="text-muted small mb-0">Total exams</p>
            </div>
        </div>

    </div>

    <!-- Tables -->
    <div class="row g-4">

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">All users</h6>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">24 users</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-muted fw-medium small">#</th>
                                    <th scope="col" class="text-muted fw-medium small">First</th>
                                    <th scope="col" class="text-muted fw-medium small">Last</th>
                                    <th scope="col" class="text-muted fw-medium small">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="small text-muted">1</td>
                                    <td class="small fw-medium">Mark</td>
                                    <td class="small">Otto</td>
                                    <td class="small text-muted">@mdo</td>
                                </tr>
                                <tr>
                                    <td class="small text-muted">2</td>
                                    <td class="small fw-medium">Jacob</td>
                                    <td class="small">Thornton</td>
                                    <td class="small text-muted">@fat</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">All schedules</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">12 schedules</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-muted fw-medium small">#</th>
                                    <th scope="col" class="text-muted fw-medium small">Title</th>
                                    <th scope="col" class="text-muted fw-medium small">Owner</th>
                                    <th scope="col" class="text-muted fw-medium small">Exams</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="small text-muted">1</td>
                                    <td class="small fw-medium">Biology Term 2</td>
                                    <td class="small">Ehao Inn</td>
                                    <td class="small"><span class="badge bg-primary bg-opacity-10 text-primary">5</span></td>
                                </tr>
                                <tr>
                                    <td class="small text-muted">2</td>
                                    <td class="small fw-medium">Math Finals</td>
                                    <td class="small">Makara Then</td>
                                    <td class="small"><span class="badge bg-primary bg-opacity-10 text-primary">3</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>