<?php
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ./?page=dashboard');
    exit;
}

$schedule = getScheduleById($id);
if (!$schedule) {
    header('Location: ./?page=dashboard');
    exit;
}

// policy 
$user = loggedInUser();
if ($schedule->owner_id != $user->id) {
    header('Location: ./?page=dashboard');
    exit;
}
$members = getAllUsers(false);
// get members of that schedule
$current_members = getScheduleMembers($id);
$current_member_ids = [];
foreach ($current_members as $m) {
    // loop and store all members id in this array
    $current_member_ids[] = (int)$m->id;
}

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_schedule'])) {
    // get title when submit post 
    $title = trim($_POST['title']);
    // get member_ids when submit post 
    $member_ids = $_POST['members'] ?? [];

    if (empty($title)) {
        $error_msg = "Please provide a schedule title.";
    } else {

        // passed required parameters to our function 
        if (updateSchedule($id, $title, $member_ids)) {
            $success_msg = "Schedule updated successfully!";
            $schedule = getScheduleById($id); // Refetch
            // restore new member_ids // intval convert string element to integer
            $current_member_ids = array_map('intval', $member_ids);
        } else {
            $error_msg = "Failed to update schedule. Please try again.";
        }
    }
}
?>

<div class="container py-5" style="max-width: 560px;">
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-success bg-opacity-10 border-0 p-4">
      <h4 class="fw-bold text-success mb-0">Edit Schedule</h4>
    </div>
    <div class="card-body p-4">

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
            value="<?= htmlspecialchars($schedule->title) ?>"
            required
            placeholder="e.g. Biology Term 2">
        </div>

        <div class="mb-4">
          <label class="form-label fw-medium d-flex justify-content-between">
            <span>Manage members <small class="text-muted fw-normal">(Who can view this)</small></span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= count($members) ?> available</span>
          </label>
          <div class="border rounded-3 p-3 d-flex flex-column gap-2 bg-light bg-opacity-50" style="max-height: 250px; overflow-y: auto;">
            <?php foreach ($members as $member): ?>
            <div class="form-check mb-0">
              <input 
                type="checkbox" 
                name="members[]" 
                class="form-check-input" 
                value="<?= $member->id ?>" 
                id="member<?= $member->id ?>"
                <?= in_array((int)$member->id, $current_member_ids) ? 'checked' : '' ?>>
              <label class="form-check-label d-flex flex-column" for="member<?= $member->id ?>">
                <span class="fw-medium font-size-sm"><?= htmlspecialchars($member->name) ?></span>
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

        <div class="d-grid gap-2">
          <button type="submit" name="update_schedule" class="btn btn-success py-2 fw-semibold shadow-sm">
            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
          </button>
          <a href="./?page=dashboard" class="btn btn-light py-2 fw-semibold border shadow-sm text-muted">
            <i class="fa-solid fa-arrow-left me-2"></i>Cancel
          </a>
        </div>

      </form>
    </div>
  </div>
</div>

<style>
.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}
</style>