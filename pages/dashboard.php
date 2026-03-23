<?php

$schedules = getSchedules();

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

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php while ($schedule = $schedules->fetch_object()): ?>
        <div class="col" style="max-width: 600px;">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">

                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <h5 class="card-title fw-semibold mb-0"><?= htmlspecialchars($schedule->title) ?></h5>
                        <!-- Placeholder for exam count if available later -->
                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
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
                        <span class="badge rounded-pill border text-secondary fw-normal px-3 py-2 bg-body-secondary">
                            <i class="fa-solid fa-circle-user me-1"></i><?= htmlspecialchars($member->name) ?>
                        </span>
                        <?php endwhile; ?>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="./?page=timetable&id=<?= $schedule->id ?>" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fa-solid fa-table me-1"></i>View timetable
                        </a>
                        <a href="./?page=add_subject&id=<?= $schedule->id ?>" class="btn btn-outline-success btn-sm px-3">
                            <i class="fa-solid fa-plus me-1"></i>Subject
                        </a>
                        <a href="./?page=add_exam&id=<?= $schedule->id ?>" class="btn btn-outline-info btn-sm px-3">
                            <i class="fa-solid fa-plus me-1"></i>Exam
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <?php endwhile; ?>

        <?php if ($schedules->num_rows === 0): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 py-5 text-center">
                <div class="card-body">
                    <i class="fa-solid fa-calendar-xmark fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-secondary">No schedules found</h5>
                    <p class="text-muted small mb-4">You haven't created or been added to any schedules yet.</p>
                    <a href="./?page=add_schedule" class="btn btn-success px-4">Create your first schedule</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>