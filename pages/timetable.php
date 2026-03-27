<?php
$schedule_id = $_GET['id'] ?? 0;
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';


$exams_result = getScheduleDetails($schedule_id, $search, $status);
$exams = [];
$schedule_title = "Schedule Details";

$schedule = getScheduleById($schedule_id);
$isOwner = $schedule && ($schedule->owner_id == loggedInUser()->id || isAdmin());

if ($exams_result->num_rows > 0) {
    while ($row = $exams_result->fetch_object()) {
        $exams[] = $row;
    }
    $schedule_title = $exams[0]->schedule_title;
}

$color_map = [
    'blue' => 'primary',
    'green' => 'success',
    'red' => 'danger',
    'orange' => 'warning',
    'purple' => 'info',
    'teal' => 'teal',
    'yellow' => 'warning',
    'pink' => 'danger'
];
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['alert_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['alert_success'] ?>
            <?php unset($_SESSION['alert_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['alert_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['alert_error'] ?>
            <?php unset($_SESSION['alert_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Exam Timetable</h2>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET">
                <input type="hidden" name="page" value="timetable">
                <input type="hidden" name="id" value="<?= htmlspecialchars($schedule_id) ?>">

                <div class="row g-2 align-items-center">
                    <!-- Search input -->
                    <div class="col-12 col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-magnifying-glass text-muted" style="font-size: 13px;"></i>
                            </span>
                            <input
                                name="search"
                                type="search"
                                class="form-control border-start-0 ps-0"
                                placeholder="Search by name, lecturer, venue..."
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <!-- Filter: Status -->
                    <div class="col-6 col-md-2">
                        <select name="status" class="form-select form-select-sm py-2">
                            <option value="">Status: All</option>
                            <option value="upcoming" <?= $status === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                            <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    <!-- Search button -->
                    <div class="col-6 col-md-1 d-grid">
                        <div class="d-flex gap-1 px-3">
                            <button type="submit" class="btn btn-success btn-sm py-2">
                                <i class="fa-solid fa-sliders"></i>
                            </button>
                            <a href="./?page=timetable&id=<?= htmlspecialchars($schedule_id) ?>" class="btn btn-danger btn-sm py-2">
                                <i class="fa-solid fa-filter-circle-xmark"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold text-secondary"><?= htmlspecialchars($schedule_title) ?></h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">Full Schedule</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4 py-3">Subject Name</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Time</th>
                            <th class="py-3">Room / Venue</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exams as $exam):
                            $bootstrap_color = $color_map[$exam->color] ?? 'primary';
                            $start_time_fmt = date("h:i A", strtotime($exam->start_time));
                            $end_time_fmt = date("h:i A", strtotime($exam->end_time));
                            $exam_date_fmt = date("M d, Y", strtotime($exam->exam_date));
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-<?= $bootstrap_color ?> bg-opacity-10 text-<?= $bootstrap_color ?> rounded p-2 me-3">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($exam->subject_name) ?></div>
                                            <div class="small text-muted"><?= $exam->lecturer ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium"><?= $exam_date_fmt ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($exam->day_name) ?></div>
                                </td>
                                <td>
                                    <div><?= $start_time_fmt ?> - <?= $end_time_fmt ?></div>
                                    <div class="small text-muted text-<?= $bootstrap_color ?>"><?= substr($exam->Duration, 0, 5) ?> Hours</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt me-1 text-<?= $bootstrap_color ?>"></i> <?= htmlspecialchars($exam->venue) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $today = date('Y-m-d');
                                    $status = ($exam->exam_date < $today) ? 'Completed' : 'Upcoming';
                                    $status_class = ($status === 'Upcoming') ? 'success' : 'secondary';
                                    ?>
                                    <span class="badge rounded-pill bg-<?= $status_class ?> px-3"><?= $status ?></span>
                                </td>
                                <td class="pe-4 text-end">
                                <?php if ($isOwner): ?>
                                    <a href="./?page=edit_exam&id=<?= $exam->id ?>" class="btn btn-light btn-sm text-warning border-0 shadow-sm" title="Edit Exam">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./?page=delete_exam&id=<?= $exam->id ?>" class="btn btn-light btn-sm text-danger border-0 shadow-sm ms-1 delete_button" title="Delete Exam">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php else: ?>
                                    <span class="badge bg-light text-muted border py-2 px-3">View Only</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($exams)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted fs-6 mb-0">No exams scheduled for this timetable yet.</p>
                                    <?php if ($isOwner): ?>
                                    <a href="./?page=add_exam&id=<?= $schedule_id ?>" class="btn btn-primary btn-sm mt-3 px-4">Add your first exam</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import { confirmAction } from './assets/js/utils.js';

    confirmAction('.delete_button', {
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        confirmText: "Yes, delete it!"
    });
</script>