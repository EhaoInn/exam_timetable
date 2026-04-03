<?php
$all_users = getAllUsers('', false); // Get all, don't exclude self for counts
$total_schedules = countTotalSchedules();
$total_subjects = countTotalSubjects();
$total_exams = countTotalExams();
$recent_exams = getRecentExams(5);
$exam_stats = getExamStatusStats();
$user_stats = getUserStats();

$all_schedules = getAllSchedules();
?>

<div class="container py-5">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h2 class="fw-bold mb-1">Administrative Dashboard</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="./?page=admin/users" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                <i class="fas fa-user-shield me-2"></i>Manage Users
            </a>
        </div>
    </div>

    <!-- Stat Cards Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="background: linear-gradient(135deg, #4e54c8, #8f94fb); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75 fw-medium">Total Users</div>
                        <h2 class="fw-bold mb-0 mt-1"><?= $user_stats['total'] ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <span class="bg-white text-primary px-2 py-1 rounded fw-bold shadow-sm"><?= $user_stats['admins'] ?> Admins</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="background: linear-gradient(135deg, #11998e, #38ef7d); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75 fw-medium">Active Schedules</div>
                        <h2 class="fw-bold mb-0 mt-1"><?= $total_schedules ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <span class="bg-white text-success px-2 py-1 rounded fw-bold shadow-sm">Global visibility</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="background: linear-gradient(135deg, #FF8008, #FFC837); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75 fw-medium">Total Exams</div>
                        <h2 class="fw-bold mb-0 mt-1"><?= $total_exams ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-graduation-cap fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2 small">
                    <span class="bg-white text-warning px-2 py-1 rounded fw-bold shadow-sm"><?= $exam_stats['upcoming'] ?> Upcoming</span>
                    <span class="bg-white text-warning px-2 py-1 rounded fw-bold shadow-sm"><?= $exam_stats['completed'] ?> Done</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="background: linear-gradient(135deg, #eb3349, #f45c43); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75 fw-medium">Total Subjects</div>
                        <h2 class="fw-bold mb-0 mt-1"><?= $total_subjects ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-book-open fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <span class="bg-white text-danger px-2 py-1 rounded fw-bold shadow-sm">Linked to schedules</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Activity Area -->
        <div class="col-lg-8">
            <!-- Recent Exams -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i>Recent Exam Additions</h5>
                    <span class="badge bg-light text-dark text-uppercase border small">Last 5 Activities</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">Subject</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th class="pe-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recent_exams->num_rows > 0): ?>
                                    <?php while($e = $recent_exams->fetch_object()): 
                                        $isUpcoming = (strtotime($e->exam_date) >= strtotime(date('Y-m-d')));
                                    ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-3 me-3" style="width: 10px; height: 35px; background-color: <?= $e->color ?>;"></div>
                                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($e->subject_name) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium small"><?= date('M d, Y', strtotime($e->exam_date)) ?></div>
                                                <div class="text-muted" style="font-size: 11px;"><?= date('h:i A', strtotime($e->start_time)) ?></div>
                                            </td>
                                            <td class="small text-secondary"><?= htmlspecialchars($e->venue) ?></td>
                                            <td class="pe-4 text-center">
                                                <span class="badge rounded-pill <?= $isUpcoming ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' ?> px-3">
                                                    <?= $isUpcoming ? 'Upcoming' : 'Past' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted small">No recent activity recorded.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Access Schedules -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-th-large me-2 text-primary"></i>System Schedules</h5>
                    <a href="./?page=dashboard" class="small text-decoration-none">Explore All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">Schedule Title</th>
                                    <th>Creator</th>
                                    <th class="pe-4 text-center">Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($all_schedules->num_rows > 0): ?>
                                    <?php $i=0; while($s = $all_schedules->fetch_object()): 
                                        if($i >= 5) break; $i++;
                                    ?>
                                        <tr>
                                            <td class="ps-4 fw-medium"><?= htmlspecialchars($s->title) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 10px;">
                                                        <?= strtoupper(substr($s->owner_name, 0, 1)) ?>
                                                    </div>
                                                    <span class="small"><?= htmlspecialchars($s->owner_name) ?></span>
                                                </div>
                                            </td>
                                            <td class="pe-4 text-center">
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2"><?= $s->exam_count ?> Exams</span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center py-4 text-muted small">No schedules found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="col-lg-4">
            <!-- User Distribution -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0">User Distribution</h5>
                </div>
                <div class="card-body">
                    <?php 
                        $adminWidth = ($user_stats['total'] > 0) ? ($user_stats['admins'] / $user_stats['total']) * 100 : 0;
                        $userWidth = ($user_stats['total'] > 0) ? ($user_stats['users'] / $user_stats['total']) * 100 : 0;
                    ?>
                    <div class="progress rounded-pill mb-3" style="height: 12px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $adminWidth ?>%" title="Admins"></div>
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $userWidth ?>%" title="Standard Users"></div>
                    </div>
                    <div class="d-flex justify-content-between small px-1">
                        <div class="d-flex align-items-center"><div class="rounded-circle bg-danger me-2" style="width:10px;height:10px;"></div> Admins (<?= $user_stats['admins'] ?>)</div>
                        <div class="d-flex align-items-center"><div class="rounded-circle bg-primary me-2" style="width:10px;height:10px;"></div> Users (<?= $user_stats['users'] ?>)</div>
                    </div>
                </div>
            </div>

            <!-- System Notifications / Alerts -->
            <div class="card border-0 shadow-sm bg-dark text-white rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2 text-warning"></i>Security Note</h6>
                    <p class="small opacity-75 mb-4">You are currently logged in with Administrative privileges. Please ensure all data modifications comply with system policies.</p>
                    <a href="./?page=logout" class="btn btn-outline-light btn-sm w-100 rounded-pill py-2">System Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>