<?php
$user = loggedInUser();
$members = getAllUsers(false);
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    $title = trim($_POST['title']);
    $member_ids = $_POST['members'] ?? [];

    if (empty($title)) {
        $error_msg = "Please provide a schedule title.";
    } else {
        if (createSchedule($title, $user->id, $member_ids)) {
            $success_msg = "Schedule created successfully!";
        } else {
            $error_msg = "Failed to create schedule. Please try again.";
        }
    }
}
?>

<div class="container py-5" style="max-width: 560px;">
  <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-4">

      <div class="mb-4">
        <h5 class="fw-semibold mb-0">New Schedule</h5>
      </div>

      <?php if ($success_msg): ?>
      <div class="alert alert-success border-0 shadow-sm py-2 px-3 small mb-4 d-flex align-items-center">
        <i class="fas fa-check-circle me-2"></i><?= $success_msg ?>
      </div>
      <?php endif; ?>

      <?php if ($error_msg): ?>
      <div class="alert alert-danger border-0 shadow-sm py-2 px-3 small mb-4 d-flex align-items-center">
        <i class="fas fa-exclamation-circle me-2"></i><?= $error_msg ?>
      </div>
      <?php endif; ?>

      <form method="POST">

        <div class="mb-4">
          <label for="title" class="form-label fw-medium">
            Schedule title
            <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
          </label>
          <input
            type="text"
            class="form-control bg-light border-0 py-2 px-3"
            id="title"
            name="title"
            required
            placeholder="e.g. Biology Term 2">
        </div>

        <div class="mb-4">
          <label class="form-label fw-medium d-flex justify-content-between">
            <span>Add members <small class="text-muted fw-normal">(Who can view this)</small></span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= count($members) ?> available</span>
          </label>
          <div class="border rounded-3 p-3 d-flex flex-column gap-2 bg-light bg-opacity-50" style="max-height: 200px; overflow-y: auto;">
            <?php foreach ($members as $member): ?>
            <div class="form-check mb-0">
              <input type="checkbox" name="members[]" class="form-check-input" value="<?= $member->id ?>" id="member<?= $member->id ?>">
              <label class="form-check-label d-flex flex-column" for="member<?= $member->id ?>">
                <span class="fw-medium"><?= htmlspecialchars($member->name) ?></span>
                <!-- <span class="text-muted small" style="font-size: 0.75rem;"><?= htmlspecialchars($member->email) ?></span> -->
              </label>
            </div>
            <?php endforeach; ?>

            <?php if (empty($members)): ?>
              <div class="text-center py-2">
                <p class="text-muted small mb-0">No other members discovered.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <hr class="my-4 opacity-50">

        <div class="d-flex align-items-center justify-content-end gap-2 mt-4">
          <a href="./?page=dashboard" class="btn btn-outline-secondary px-4 border-0">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
          </a>
          <button type="submit" name="add_schedule" class="btn btn-success px-4 shadow-sm">
            <i class="fa-solid fa-plus me-2"></i>Add schedule
          </button>
        </div>

      </form>
    </div>
  </div>
</div>