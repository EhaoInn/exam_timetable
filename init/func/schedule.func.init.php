<?php

function createSchedule($title, $owner_id, $member_ids = [])
{
    global $db;

    $query = $db->prepare("INSERT INTO schedules (title, owner_id) VALUES (?, ?)");
    $query->bind_param("si", $title, $owner_id);
    $query->execute();

    if ($db->affected_rows) {
        $schedule_id = $db->insert_id;

        if (!empty($member_ids)) {
            $member_query = $db->prepare("INSERT INTO schedule_members (schedule_id, user_id) VALUES (?, ?)");
            foreach ($member_ids as $member_id) {
                $member_query->bind_param("ii", $schedule_id, $member_id);
                $member_query->execute();
            }
        }

        return true;
    }

    return false;
}

function getSchedules()
{
    global $db;

    $user = loggedInUser();
    $user_id = $user->id;

    // Fetch schedules where user is the owner OR a listed member
    $query = $db->prepare("SELECT DISTINCT schedules.*, users.name as owner_name 
                            FROM schedules 
                            JOIN users ON schedules.owner_id = users.id 
                            LEFT JOIN schedule_members ON schedules.id = schedule_members.schedule_id 
                            WHERE schedules.owner_id = ? OR schedule_members.user_id = ?");
    $query->bind_param("ii", $user_id, $user_id);
    $query->execute();
    return $query->get_result();
}

function getScheduleMembers($schedule_id)
{
    global $db;
    $query = $db->prepare("SELECT users.name FROM schedule_members JOIN users ON schedule_members.user_id = users.id WHERE schedule_members.schedule_id = ?");
    $query->bind_param("i", $schedule_id);
    $query->execute();
    return $query->get_result();
}

function getScheduleDetails($schedule_id)
{
    global $db;

    // Use INNER JOIN to only see subjects that have exams
    $query = $db->prepare("SELECT 
    sch.title AS schedule_title, 
    sub.name AS subject_name, 
    sub.lecturer, 
    sub.color,
    exm.exam_date, 
    exm.start_time, 
    exm.end_time, 
    exm.venue,
    DAYNAME(exm.exam_date) AS day_name,
    SEC_TO_TIME(
        TIMESTAMPDIFF(SECOND, exm.start_time, exm.end_time)
    ) AS Duration
FROM schedules AS sch 
JOIN subjects AS sub ON sch.id = sub.schedule_id 
INNER JOIN exams AS exm ON sub.id = exm.subject_id
WHERE sch.id = ?");

    $query->bind_param("i", $schedule_id);
    $query->execute();
    return $query->get_result();
}

function getScheduleExamsCount($schedule_id)
{
    global $db;
    $query = $db->prepare("SELECT COUNT(*) as total FROM exams JOIN subjects ON exams.subject_id = subjects.id WHERE subjects.schedule_id = ?");
    $query->bind_param("i", $schedule_id);
    $query->execute();
    $result = $query->get_result()->fetch_object();
    return $result->total;
}
