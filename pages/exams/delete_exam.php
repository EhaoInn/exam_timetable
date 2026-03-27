<?php
$id = $_GET['id'] ?? 0;

if ($id) {
    // Get schedule id first to redirect back to the correct timetable
    $exam = getExamById($id);
    $subject = $exam ? getSubjectById($exam['subject_id']) : null;
    $schedule_id = $subject ? $subject['schedule_id'] : 0;

    if (deleteExam($id)) {
        $_SESSION['alert_success'] = 'Exam deleted successfully!';
    } else {
        $_SESSION['alert_error'] = "Failed to delete exam or you don't have permission";
    }

    if ($schedule_id) {
        header("Location: ./?page=timetable&id=" . $schedule_id);
    } else {
        header("Location: ./?page=dashboard");
    }
} else {
    header("Location: ./?page=dashboard");
}
exit;