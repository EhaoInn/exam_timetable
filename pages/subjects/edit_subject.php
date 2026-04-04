<?php
$subject_id = $_GET['id'] ?? 0;
$subject = getSubjectById($subject_id);

if (!$subject || !Permission::checkSubjectPermission((int)$subject_id)) {
    header("Location: ./?page=dashboard");
    exit();
}

$schedule_id = $subject['schedule_id']; // getSubjectById returns assoc array
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_subject'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $lecturer = trim($_POST['lecturer']);
    $color = $_POST['color'] ?? 'blue';

    if (empty($code) || empty($name) || empty($lecturer)) {
        $error_msg = "Please fill in all fields.";
    } else {
        if (updateSubject($subject_id, $code, $name, $lecturer, $color)) {
            $_SESSION['alert_success'] = "Subject updated successfully!";
            header("Location: ./?page=subjects/add_subject&id=" . $schedule_id);
            exit();
        } else {
            $error_msg = "Failed to update subject. Please try again.";
        }
    }
}

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
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <h5 class="fw-semibold mb-0">Edit Subject</h5>
                        <p class="text-muted small">Update the information for <?= htmlspecialchars($subject['name']) ?></p>
                    </div>

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
                            <input type="text" name="code" class="form-control" id="subject-code" 
                                   value="<?= htmlspecialchars($subject['code']) ?>" placeholder="e.g. BIO101">
                        </div>

                        <div class="mb-3">
                            <label for="subject-name" class="form-label fw-medium">
                                Subject name
                                <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                            </label>
                            <input type="text" name="name" class="form-control" id="subject-name" 
                                   value="<?= htmlspecialchars($subject['name']) ?>" placeholder="e.g. Biology">
                        </div>

                        <div class="mb-4">
                            <label for="lecturer" class="form-label fw-medium">
                                Lecturer
                                <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
                            </label>
                            <input type="text" name="lecturer" class="form-control" id="lecturer" 
                                   value="<?= htmlspecialchars($subject['lecturer']) ?>" placeholder="e.g. Dr. Chan Dara">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium d-block">Color</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($color_map as $name => $hex): ?>
                                <input type="radio" class="btn-check" name="color" id="color-<?= $name ?>" value="<?= $name ?>" autocomplete="off" 
                                       <?= $name === $subject['color'] ? 'checked' : '' ?>>
                                <label class="btn btn-sm rounded-circle p-0 border-0" for="color-<?= $name ?>" 
                                       style="width:32px;height:32px;background-color:<?= $hex ?>;" title="<?= ucfirst($name) ?>"></label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex align-items-center justify-content-end gap-2">
                            <a href="./?page=subjects/add_subject&id=<?= $schedule_id ?>" class="btn btn-outline-secondary px-4 border-0">
                                Cancel
                            </a>
                            <button type="submit" name="edit_subject" class="btn btn-primary px-4 shadow-sm">
                                <i class="fa-solid fa-save me-2"></i>Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
