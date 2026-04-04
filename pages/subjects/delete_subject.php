<?php
$id = $_GET['id'] ?? 0;
$schedule_id = $_GET['sid'] ?? 0;

if (!Permission::checkSubjectPermission($id)) {
    $_SESSION['alert_error'] = "You do not have permission to delete this subject.";
    header("Location: ./?page=dashboard");
    exit();
}

if (deleteSubject($id)) {
    $_SESSION['alert_success'] = "Subject has been permanently removed!";
} else {
    $_SESSION['alert_error'] = "Something went wrong. Please try again.";
}

header("Location: ./?page=subjects/add_subject&id=" . $schedule_id);
exit();
