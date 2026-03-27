<?php
$exam_id = $_GET['id'] ?? 0;
$exam = getExamById($exam_id);

if (!$exam) {
    echo "<script>window.location.href = './?page=dashboard';</script>";
    exit;
}

$subject = getSubjectById($exam['subject_id']);
$subjects = getSubjectsBySchedule($subject['schedule_id']);
$success_msg = '';
$error_msg = '';
$date_err = '';
$time_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_exam'])) {
    $subject_id = $_POST['subject_id'] ?? '';
    $exam_date = $_POST['exam_date'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $notes = trim($_POST['notes'] ?? '');

    $today = date('Y-m-d');
    if (!empty($exam_date) && $exam_date < $today) {
        $date_err = "Exam date cannot be in the past.";
    }

    if (!empty($start_time) && !empty($end_time)) {
        if (strtotime($start_time) >= strtotime($end_time)) {
            $time_err = "End time must be after start time.";
        }
    }

    if (empty($subject_id) || empty($exam_date) || empty($venue) || empty($start_time) || empty($end_time)) {
        $error_msg = "Please fill in all fields.";
    }

    if (empty($date_err) && empty($time_err) && empty($error_msg)) {
        if (updateExam($exam_id, $subject_id, $exam_date, $start_time, $end_time, $venue, $notes)) {
            $success_msg = "Exam updated successfully!";
            // Refresh exam data to reflect changes in the form
            $exam = getExamById($exam_id);
        } else {
            $error_msg = "Failed to update exam or you don't have permission";
        }
    }
}
?>

<div class="container py-5" style="max-width: 560px;">
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-4">

            <div class="mb-4 text-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                    <i class="fa-solid fa-pen-to-square fa-xl"></i>
                </div>
                <h5 class="fw-semibold mb-0">Edit Exam</h5>
            </div>

            <?php if ($success_msg): ?>
                <div class="alert alert-success border-0 small mb-4 py-2 text-center shadow-sm">
                    <i class="fas fa-check-circle me-1"></i><?= $success_msg ?>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="alert alert-danger border-0 small mb-4 py-2 text-center shadow-sm">
                    <i class="fas fa-exclamation-circle me-1"></i><?= $error_msg ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="mb-3">
                    <label for="subject_id" class="form-label fw-medium">
                        Subject
                        <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                    </label>
                    <select name="subject_id" id="subject_id" class="form-select bg-light border-0 py-2">
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $exam['subject_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['code'] . ' — ' . $s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exam_date" class="form-label fw-medium">
                        Date
                        <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                    </label>
                    <input type="date" name="exam_date" class="form-control <?php echo $date_err ? 'is-invalid' : '' ?> bg-light border-0 py-2" id="exam_date" value="<?= htmlspecialchars($exam['exam_date']) ?>">
                    <div class="invalid-feedback">
                        <?php echo $date_err ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="venue" class="form-label fw-medium">
                        Venue
                        <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                    </label>
                    <input type="text" name="venue" class="form-control bg-light border-0 py-2" id="venue" placeholder="e.g. Hall A, Room 204" value="<?= htmlspecialchars($exam['venue']) ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label for="start_time" class="form-label fw-medium">
                            Start time
                        </label>
                        <input type="time" name="start_time" class="form-control <?php echo $time_err ? 'is-invalid' : '' ?> bg-light border-0 py-2" id="start_time" value="<?= htmlspecialchars($exam['start_time']) ?>">
                        <div class="invalid-feedback">
                            <?php echo $time_err ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="end_time" class="form-label fw-medium">
                            End time
                        </label>
                        <input type="time" name="end_time" class="form-control <?php echo $time_err ? 'is-invalid' : '' ?> bg-light border-0 py-2" id="end_time" value="<?= htmlspecialchars($exam['end_time']) ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-medium">Notes <small class="text-muted fw-normal">(Optional)</small></label>
                    <textarea
                        class="form-control bg-light border-0"
                        id="notes"
                        name="notes"
                        rows="3"
                        placeholder="e.g. Bring student ID and calculator"><?= htmlspecialchars($exam['notes']) ?></textarea>
                </div>

                <hr class="my-4 opacity-50">

                <div class="d-flex align-items-center justify-content-end gap-2">
                    <a href="./?page=timetable&id=<?= $subject['schedule_id'] ?>" class="btn btn-outline-secondary px-4 border-0">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back
                    </a>
                    <button type="submit" name="edit_exam" class="btn btn-warning text-white px-4 shadow-sm">
                        <i class="fa-solid fa-save me-2"></i>Update exam
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>