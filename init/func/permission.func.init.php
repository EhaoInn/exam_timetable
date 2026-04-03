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

        // Check if user is owner or admin 
        return $schedule && ($schedule->owner_id == loggedInUser()->id || isAdmin());
    }

    public static function checkSchedulePermission($schedule_id)
    {
        if (!$schedule_id) return false;

        $schedule = getScheduleById($schedule_id);
        if (!$schedule) return false;

        return $schedule && ($schedule->owner_id == loggedInUser()->id || isAdmin());
    }
}
