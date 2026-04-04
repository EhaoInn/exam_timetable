<?php
class Permission
{
    /**
     * Check if the logged in user is an admin.
     * @return bool
     */
    public static function isAdmin()
    {
        $user = loggedInUser();
        return (isset($user->role) && $user->role === 'admin');
    }

    /**
     * Check if the user has permission to manage exams for a subject.
     * @param int $subject_id
     * @return bool
     */
    public static function checkExamPermission($subject_id)
    {
        if (!$subject_id) return false;

        $subject = getSubjectById($subject_id);
        if (!$subject) return false;

        $schedule = getScheduleById($subject['schedule_id']);

        return $schedule && ($schedule->owner_id == loggedInUser()->id || self::isAdmin());
    }

    /**
     * Check if the user has permission to manage a schedule.
     * @param int $schedule_id
     * @return bool
     */
    public static function checkSchedulePermission($schedule_id)
    {
        if (!$schedule_id) return false;

        $schedule = getScheduleById($schedule_id);
        if (!$schedule) return false;

        return $schedule && ($schedule->owner_id == loggedInUser()->id || self::isAdmin());
    }

    /**
     * Check if the user has permission to manage a subject.
     * @param int $subject_id
     * @return bool
     */
    public static function checkSubjectPermission($subject_id)
    {
        if (!$subject_id) return false;

        $subject = getSubjectById($subject_id);
        if (!$subject) return false;

        $schedule = getScheduleById($subject['schedule_id']);
        return $schedule && ($schedule->owner_id == loggedInUser()->id || self::isAdmin());
    }
}
