<?php
class Permission
{
    public static function isAdmin()
    {
        return (isset(loggedInUser()->role) && loggedInUser()->role === 'admin');
    }

    public static function checkExamPermission($subject_id)
    {
        if (!$subject_id) return false;

        $subject = getSubjectById($subject_id);
        if (!$subject) return false;

        // getScheduleById is assumed to return an object with an 'owner_id' property
        $schedule = getScheduleById($subject['schedule_id']);

        // Check if user is owner or admin (isAdmin() and loggedInUser() are global helpers)
        return $schedule && ($schedule->owner_id == loggedInUser()->id || isAdmin());
    }
}
