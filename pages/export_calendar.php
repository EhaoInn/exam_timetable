<?php

$schedule_id = $_GET['id'] ?? 0;
if (!$schedule_id) {
    die("Invalid Schedule ID");
}

$exams_result = getScheduleDetails($schedule_id);
if (!$exams_result || $exams_result->num_rows == 0) {
    die("No exams found for this schedule.");
}

$exams = [];
while ($row = $exams_result->fetch_object()) {
    $exams[] = $row;
}

$schedule_title = $exams[0]->schedule_title ?? "Exam_Schedule";
$filename = str_replace(' ', '_', $schedule_title) . ".ics";

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

echo generateICS($exams, $schedule_title);
exit;
