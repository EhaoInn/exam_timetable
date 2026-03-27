<?php
$all_users = getAllUsers();
$all_schedules = getAllSchedules();
$total_schedules = countTotalSchedules();
$total_subjects = countTotalSubjects();
$total_exams = countTotalExams();
?>

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
  <div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="text-muted small">Total users</span>
          <div class="rounded-2 bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
            <i class="fa-solid fa-users text-success" style="font-size:14px;"></i>
          </div>
        </div>
        <h4 class="fw-bold mb-0"><?= count($all_users) + 1 ?></h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="text-muted small">Total schedules</span>
          <div class="rounded-2 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
            <i class="fa-solid fa-calendar-days text-primary" style="font-size:14px;"></i>
          </div>
        </div>
        <h4 class="fw-bold mb-0"><?= $total_schedules ?></h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="text-muted small">Total subjects</span>
          <div class="rounded-2 bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
            <i class="fa-solid fa-book text-warning" style="font-size:14px;"></i>
          </div>
        </div>
        <h4 class="fw-bold mb-0"><?= $total_subjects ?></h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="text-muted small">Total exams</span>
          <div class="rounded-2 bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
            <i class="fa-solid fa-file-circle-check text-danger" style="font-size:14px;"></i>
          </div>
        </div>
        <h4 class="fw-bold mb-0"><?= $total_exams ?></h4>
      </div>
    </div>

  </div>

  <!-- Tables -->
  <div class="row g-4">

    <!-- Users table -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h6 class="fw-semibold mb-0">All users</h6>
              <p class="text-muted small mb-0"><?= count($all_users) + 1 ?> registered</p>
            </div>
            <a href="./?page=admin/users" class="btn btn-sm btn-outline-success px-3">
              <i class="fa-solid fa-arrow-up-right-from-square me-1" style="font-size:11px;"></i>View all
            </a>
          </div>

          <div class="table-responsive" style="max-height: 400px;">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light sticky-top">
                <tr>
                  <th class="text-muted fw-medium small">#</th>
                  <th class="text-muted fw-medium small">Name</th>
                  <th class="text-muted fw-medium small">Email</th>
                  <th class="text-muted fw-medium small">Joined</th>
                </tr>
              </thead>
              <tbody>

                <?php if (empty($all_users)): ?>
                  <tr>
                    <td colspan="4" class="text-center py-4">
                      <i class="fa-solid fa-users-slash text-muted mb-2 d-block" style="font-size:20px;"></i>
                      <span class="text-muted small">No users found.</span>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($all_users as $i => $user): ?>
                    <tr>
                      <td class="small text-muted"><?= $i + 1 ?></td>
                      <td class="small">
                        <div class="d-flex align-items-center gap-2">
                          <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:28px;height:28px;font-size:11px;font-weight:600;color:#198754;">
                            <?= strtoupper(substr($user->name, 0, 1)) ?>
                          </div>
                          <span class="fw-medium"><?= htmlspecialchars($user->name) ?></span>
                        </div>
                      </td>
                      <td class="small text-muted"><?= htmlspecialchars($user->email) ?></td>
                      <td class="small text-muted"><?= date('M d, Y', strtotime($user->created_at)) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>

              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- Schedules table -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h6 class="fw-semibold mb-0">All schedules</h6>
              <p class="text-muted small mb-0"><?= $total_schedules ?> active schedules</p>
            </div>
            <a href="./?page=admin/schedules" class="btn btn-sm btn-outline-primary px-3">
              <i class="fa-solid fa-arrow-up-right-from-square me-1" style="font-size:11px;"></i>View all
            </a>
          </div>

          <div class="table-responsive" style="max-height: 400px;">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light sticky-top">
                <tr>
                  <th class="text-muted fw-medium small">#</th>
                  <th class="text-muted fw-medium small">Title</th>
                  <th class="text-muted fw-medium small">Owner</th>
                  <th class="text-muted fw-medium small">Exams</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($all_schedules->num_rows === 0): ?>
                  <tr>
                    <td colspan="4" class="text-center py-4">
                      <i class="fa-solid fa-calendar-xmark text-muted mb-2 d-block" style="font-size:20px;"></i>
                      <span class="text-muted small">No schedules found.</span>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php $s_i = 1; while ($s = $all_schedules->fetch_object()): ?>
                    <tr>
                      <td class="small text-muted"><?= $s_i++ ?></td>
                      <td class="small fw-medium"><?= htmlspecialchars($s->title) ?></td>
                      <td class="small">
                        <div class="d-flex align-items-center gap-2">
                          <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:28px;height:28px;font-size:11px;font-weight:600;color:#0d6efd;">
                            <?= strtoupper(substr($s->owner_name, 0, 1)) ?>
                          </div>
                          <?= htmlspecialchars($s->owner_name) ?>
                        </div>
                      </td>
                      <td class="small">
                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-2"><?= $s->exam_count ?> exams</span>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

  </div>

</div>