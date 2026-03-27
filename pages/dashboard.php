<?php
$user = loggedInUser(); 
$schedules = getSchedules();

$success_msg = '';
$error_msg = '';
?>

<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fs-4 fw-semibold text-success mb-0">My Schedules</h1>
            <p class="text-muted small mb-0">Manage your exam timetables</p>
        </div>
        <a href="./?page=add_schedule" role="button" class="btn btn-success px-3">
            <i class="fa-solid fa-plus me-2"></i>New schedule
        </a>
    </div>

    <?php if ($success_msg): ?>
    <div class="alert alert-success border-0 shadow-sm py-2 px-3 small mb-4 d-flex align-items-center animate-fade-in">
        <i class="fas fa-check-circle me-2"></i><?= $success_msg ?>
        <button type="button" class="btn-close ms-auto small" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.5rem;"></button>
    </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
    <div class="alert alert-danger border-0 shadow-sm py-2 px-3 small mb-4 d-flex align-items-center animate-fade-in">
        <i class="fas fa-exclamation-circle me-2"></i><?= $error_msg ?>
        <button type="button" class="btn-close ms-auto small" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.5rem;"></button>
    </div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php while ($schedule = $schedules->fetch_object()): ?>
            <div class="col" style="max-width: 600px;">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden card-hover">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <h5 class="card-title fw-semibold mb-0"><?= htmlspecialchars($schedule->title) ?></h5>
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary border-opacity-10">
                                <i class="fa-solid fa-file-signature me-1"></i>
                                <?= getScheduleExamsCount($schedule->id) ?> Exams
                            </span>
                        </div>

                        <p class="text-muted small mb-3">
                            <i class="fa-solid fa-user me-1"></i>Owner:
                            <span class="fw-medium text-body"><?= htmlspecialchars($schedule->owner_name) ?></span>
                        </p>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <?php 
                            $members = getScheduleMembers($schedule->id);
                            while ($member = $members->fetch_object()): 
                            ?>
                                <span class="badge rounded-pill border text-secondary fw-normal px-3 py-2 bg-light d-flex align-items-center gap-1">
                                    <div class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></div>
                                    <?= htmlspecialchars($member->name) ?>
                                </span>
                            <?php endwhile; ?>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <a href="./?page=timetable&id=<?= $schedule->id ?>" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                                <i class="fa-solid fa-table me-1"></i>View timetable
                            </a>
                            <?php if ($schedule->owner_id == $user->id): ?>
                                <a href="./?page=edit_schedule&id=<?= $schedule->id ?>" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                </a>
                                <a href="./?page=add_subject&id=<?= $schedule->id ?>" class="btn btn-outline-success btn-sm px-3 rounded-pill">
                                    <i class="fa-solid fa-plus me-1"></i>Subject
                                </a>
                                <a href="./?page=add_exam&id=<?= $schedule->id ?>" class="btn btn-outline-info btn-sm px-3 rounded-pill">
                                    <i class="fa-solid fa-plus me-1"></i>Exam
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if ($schedules->num_rows === 0): ?>
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-4 shadow-sm">
                        <i class="fa-solid fa-calendar-xmark fa-3x text-muted opacity-25"></i>
                    </div>
                </div>
                <h5 class="text-secondary fw-semibold">No schedules found</h5>
                <p class="text-muted small mb-4 mx-auto" style="max-width: 300px;">You haven't created or been added to any schedules yet. Create one to get started.</p>
                <a href="./?page=add_schedule" class="btn btn-success px-4 rounded-pill shadow-sm">Create your first schedule</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<style>
    .card-hover {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
