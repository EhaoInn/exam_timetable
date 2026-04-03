<?php
$user = loggedInUser();
$schedules = getSchedules();

$success_msg = $_SESSION['alert_success'] ?? '';
$error_msg = $_SESSION['alert_error'] ?? '';
unset($_SESSION['alert_success'], $_SESSION['alert_error']);
?>

<div class="container py-5">
    <!-- Notifications -->
    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 animate-fade-in" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div><?= htmlspecialchars($success_msg) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 animate-fade-in" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                <div><?= htmlspecialchars($error_msg) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Welcome & Header -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold text-dark mb-1">Welcome back, <?= explode(' ', htmlspecialchars($user->name))[0] ?>!</h1>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="./?page=schedules/add_schedule" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm hover-lift">
                <i class="fas fa-calendar-plus me-2"></i>Create Schedule
            </a>
        </div>
    </div>

    <!-- Schedules Grid -->
    <div class="row g-4">
        <?php if ($schedules->num_rows > 0): ?>
            <?php while ($schedule = $schedules->fetch_object()): 
                $examCount = getScheduleExamsCount($schedule->id);
                $isOwner = ($schedule->owner_id == $user->id);
            ?>
                <div class="col-lg-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                        <!-- Card Header Accent -->
                        <div class="card-header border-0 bg-transparent pt-4 pb-0 px-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 mb-3">
                                    <i class="fas fa-calendar-alt fa-lg"></i>
                                </div>
                                <span class="badge rounded-pill <?= $examCount > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' ?> px-3 py-2">
                                    <?= $examCount ?> Exam<?= $examCount != 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-body px-4 pb-4">
                            <h5 class="fw-bold text-dark mb-1 d-block text-truncate" title="<?= htmlspecialchars($schedule->title) ?>">
                                <?= htmlspecialchars($schedule->title) ?>
                            </h5>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-user-circle me-1"></i> Organized by <span class="fw-semibold"><?= $isOwner ? 'You' : htmlspecialchars($schedule->owner_name) ?></span>
                            </p>

                            <!-- Member Avatar Group -->
                            <?php $members = getScheduleMembers($schedule->id); ?>
                            <div class="mb-4">
                                <label class="small text-muted d-block mb-2">Team Members</label>
                                <div class="avatar-group d-flex align-items-center">
                                    <?php 
                                    $m_count = 0;
                                    while ($member = $members->fetch_object()): 
                                        if($m_count < 4):
                                    ?>
                                        <div class="avatar-sm rounded-circle bg-white shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-primary fw-bold" 
                                             style="width: 32px; height: 32px; font-size: 10px; margin-left: <?= $m_count > 0 ? '-10' : '0' ?>px; z-index: <?= 10 - $m_count ?>;"
                                             title="<?= htmlspecialchars($member->name) ?>">
                                            <?= strtoupper(substr($member->name, 0, 1)) ?>
                                        </div>
                                    <?php 
                                        endif;
                                        $m_count++;
                                    endwhile; 
                                    ?>
                                    <?php if($m_count > 4): ?>
                                        <div class="avatar-sm rounded-circle bg-light border border-2 border-white d-flex align-items-center justify-content-center text-muted fw-bold" 
                                             style="width: 32px; height: 32px; font-size: 10px; margin-left: -10px; z-index: 5;">
                                            +<?= $m_count - 4 ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($m_count == 0): ?>
                                        <span class="text-muted small fst-italic">Private schedule</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="d-flex gap-2 pt-2">
                                <a href="./?page=timetable&id=<?= $schedule->id ?>" class="btn btn-primary flex-grow-1 rounded-3 py-2 shadow-sm">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                                <?php if ($isOwner): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-light rounded-3 py-2 border px-3" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 rounded-3">
                                            <li><a class="dropdown-item rounded-2 small" href="./?page=schedules/edit_schedule&id=<?= $schedule->id ?>"><i class="fas fa-edit me-2 text-warning"></i>Edit Global</a></li>
                                            <li><a class="dropdown-item rounded-2 small" href="./?page=subjects/add_subject&id=<?= $schedule->id ?>"><i class="fas fa-plus-circle me-2 text-success"></i>Add Subject</a></li>
                                            <li><a class="dropdown-item rounded-2 small" href="./?page=exams/add_exam&id=<?= $schedule->id ?>"><i class="fas fa-file-alt me-2 text-info"></i>Add Exam</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item rounded-2 small text-danger delete_schedule" href="./?page=schedules/delete_schedule&id=<?= $schedule->id ?>"><i class="fas fa-trash-alt me-2"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-5 py-5 text-center">
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px;">
                                <i class="fas fa-calendar-times text-primary fs-1"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-2">No Schedules Found</h4>
                        <p class="text-muted mx-auto mb-4" style="max-width: 400px;">It looks like your dashboard is empty. Start by creating a new schedule or wait to be added as a member to an existing one.</p>
                        <a href="./?page=schedules/add_schedule" class="btn btn-primary px-5 py-2 rounded-pill shadow">
                            <i class="fas fa-plus me-2"></i>Create New Schedule
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script type="module">
    import { confirmAction } from './assets/js/utils.js';

    confirmAction('.delete_schedule', {
        title: "Delete this schedule?",
        text: "All subjects and exams linked to this schedule will be permanently removed.",
        confirmText: "Yes, delete it",
        icon: "warning"
    });
</script>

<style>
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
    }
    .avatar-group div {
        transition: transform 0.2s;
        cursor: default;
    }
    .avatar-group div:hover {
        transform: scale(1.1);
        z-index: 100 !important;
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>