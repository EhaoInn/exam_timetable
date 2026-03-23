<?php
$schedule_id = $_GET['id'] ?? 0;
$exams_result = getScheduleDetails($schedule_id);
$exams = [];
$schedule_title = "Schedule Details";

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
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Exam Timetable</h2>
            <p class="text-muted">Viewing your upcoming examination schedule.</p>
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
                            <th class="pe-4 py-3 text-center">Status</th>
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
                            <td class="pe-4 text-center">
                                <?php 
                                    $today = date('Y-m-d');
                                    $status = ($exam->exam_date < $today) ? 'Completed' : 'Upcoming';
                                    $status_class = ($status === 'Upcoming') ? 'success' : 'secondary';
                                ?>
                                <span class="badge rounded-pill bg-<?= $status_class ?> px-3"><?= $status ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($exams)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted opacity-25 mb-3"></i>
                                <p class="text-muted fs-6 mb-0">No exams scheduled for this timetable yet.</p>
                                <a href="./?page=add_exam&id=<?= $schedule_id ?>" class="btn btn-primary btn-sm mt-3 px-4">Add your first exam</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-0 text-center">
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm px-4">
                <i class="fas fa-print me-2"></i>Print Timetable
            </button>
        </div>
    </div>
</div>
