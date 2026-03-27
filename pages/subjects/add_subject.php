<?php
$schedule_id = $_GET['id'] ?? 0;
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subject'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $lecturer = trim($_POST['lecturer']);
    $color = $_POST['color'] ?? 'blue';

    if (empty($code) || empty($name) || empty($lecturer)) {
        $error_msg = "Please fill in all fields.";
    } else {
        if (createSubject($schedule_id, $code, $name, $lecturer, $color)) {
            $success_msg = "Subject added successfully!";
        } else {
            $error_msg = "Failed to add subject. Please try again.";
        }
    }
}

$existing_subjects = getSubjectsBySchedule($schedule_id);

$color_map = [
    'blue' => '#0d6efd',
    'green' => '#198754',
    'red' => '#dc3545',
    'orange' => '#fd7e14',
    'purple' => '#6f42c1',
    'teal' => '#20c997',
    'yellow' => '#ffc107',
    'pink' => '#d63384'
];
?>

<div class="container py-5">
    <div class="row g-4">

        <!-- Left: Form -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <h5 class="fw-semibold mb-0">New Subject</h5>
                        <p class="text-muted small mb-0">Fill in the details to add a new subject</p>
                    </div>

                    <?php if ($success_msg): ?>
                    <div class="alert alert-success border-0 small mb-4 py-2">
                        <i class="fas fa-check-circle me-1"></i><?= $success_msg ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($error_msg): ?>
                    <div class="alert alert-danger border-0 small mb-4 py-2">
                        <i class="fas fa-exclamation-circle me-1"></i><?= $error_msg ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label for="subject-code" class="form-label fw-medium">
                                Subject code
                                <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                            </label>
                            <input type="text" name="code" class="form-control" id="subject-code" placeholder="e.g. BIO101">
                        </div>

                        <div class="mb-3">
                            <label for="subject-name" class="form-label fw-medium">
                                Subject name
                                <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                            </label>
                            <input type="text" name="name" class="form-control" id="subject-name" placeholder="e.g. Biology">
                        </div>

                        <div class="mb-4">
                            <label for="lecturer" class="form-label fw-medium">
                                Lecturer
                                <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                            </label>
                            <input type="text" name="lecturer" class="form-control" id="lecturer" placeholder="e.g. Dr. Chan Dara">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium d-block">Color</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($color_map as $name => $hex): ?>
                                <input type="radio" class="btn-check" name="color" id="color-<?= $name ?>" value="<?= $name ?>" autocomplete="off" <?= $name==='blue'?'checked':'' ?>>
                                <label class="btn btn-sm rounded-circle p-0 border-0" for="color-<?= $name ?>" style="width:32px;height:32px;background-color:<?= $hex ?>;" title="<?= ucfirst($name) ?>"></label>
                                <?php endforeach; ?>
                            </div>
                            <div class="form-text">This color will be used to identify the subject on the timetable.</div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex align-items-center justify-content-end gap-2">
                            <a href="./?page=dashboard" class="btn btn-outline-secondary px-4 border-0">
                                <i class="fa-solid fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" name="add_subject" class="btn btn-success px-4 shadow-sm">
                                <i class="fa-solid fa-plus me-2"></i>Add subject
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Existing subjects -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-semibold mb-0">Existing subjects</h5>
                            <p class="text-muted small mb-0">Subjects already added to this schedule</p>
                        </div>
                        <span class="badge bg-body-secondary text-secondary border px-3 py-2 rounded-pill"><?= $existing_subjects->num_rows ?> subjects</span>
                    </div>

                    <div class="d-flex flex-column gap-2 overflow-y-auto" style="max-height: 500px;">

                        <?php while ($subject = $existing_subjects->fetch_object()): ?>
                        <div class="d-flex align-items-center justify-content-between border rounded-pill px-3 py-2 bg-body-secondary bg-opacity-50">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <div class="rounded-circle flex-shrink-0" style="width:14px;height:14px;background-color:<?= $color_map[$subject->color] ?? '#ddd' ?>;"></div>
                                <span class="fw-semibold small text-truncate" style="min-width: 60px;"><?= htmlspecialchars($subject->code) ?></span>
                                <span class="text-muted small text-truncate"><?= htmlspecialchars($subject->name) ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small d-none d-md-inline" style="font-size: 0.75rem;"><?= htmlspecialchars($subject->lecturer) ?></span>
                                <button class="btn btn-sm btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center border-0" style="width:26px;height:26px;" title="Remove">
                                    <i class="fa-solid fa-xmark" style="font-size:11px;"></i>
                                </button>
                            </div>
                        </div>
                        <?php endwhile; ?>

                        <?php if ($existing_subjects->num_rows === 0): ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-book fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted small mb-0">No subjects added to this schedule yet.</p>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>