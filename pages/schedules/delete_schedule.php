<?php

$id = $_GET["id"] ?? null;

if ($id) {
    $schedule = getScheduleById($id);

    if (deleteSchedule($id)) {
        $_SESSION['alert_success'] = 'Schedule deleted successfully!';
    } else {
        $_SESSION['alert_error'] = "Failed to delete schedule.";
    }
    
} else {
    $_SESSION['alert_error'] = "You do not have permission to delete this schedule.";
}
header("Location: ./?page=dashboard");
exit;
