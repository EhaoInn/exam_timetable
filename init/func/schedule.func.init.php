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

function getScheduleById($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM schedules WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    return $query->get_result()->fetch_object();
}

function updateSchedule($id, $title, $member_ids = [])
{
    global $db;

    $query = $db->prepare("UPDATE schedules SET title = ? WHERE id = ?");
    $query->bind_param("si", $title, $id);
    $query->execute();

    if ($db->affected_rows >= 0) {

        // Sync members
        // delete and reinsert
        $delete_query = $db->prepare("DELETE FROM schedule_members WHERE schedule_id = ?");
        $delete_query->bind_param('i', $id);
        $delete_query->execute();

        if(!empty($member_ids)){
            $insert_members = $db->prepare("INSERT INTO schedule_members (schedule_id,user_id) VALUES (?,?)");
    
            // loop all members_ids we selected in a checkbox to insert
            foreach ($member_ids as $user_id){
                $insert_members->bind_param('ii', $id, $user_id);
                $insert_members->execute();
            }
        //    $insert_members->close();
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
    $query = $db->prepare("SELECT users.id, users.name, users.email FROM schedule_members JOIN users ON schedule_members.user_id = users.id WHERE schedule_members.schedule_id = ?");
    $query->bind_param("i", $schedule_id);
    $query->execute();
    return $query->get_result();
}

function getScheduleDetails($schedule_id, $search = '', $status = '')
{
    global $db;

    $sql = "SELECT 
        exm.id,
        sch.title AS schedule_title, 
        sub.name AS subject_name, 
        sub.code,
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
    WHERE sch.id = ?";

    $params = [$schedule_id];
    $types = "i";

    if ($search) {
        $sql .= " AND (sub.name LIKE ? OR sub.lecturer LIKE ? OR exm.venue LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "sss";
    }
    // var_dump($params);

    // die();

    if ($status === 'upcoming') {
        $sql .= " AND exm.exam_date >= CURDATE()";
    } elseif ($status === 'completed') {
        $sql .= " AND exm.exam_date < CURDATE()";
    }

    $query = $db->prepare($sql);
    $query->bind_param($types, ...$params);
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

function getAllSchedules() {
    global $db;
    $query = $db->prepare("SELECT schedules.*, users.name as owner_name, (SELECT COUNT(*) FROM exams JOIN subjects ON exams.subject_id = subjects.id WHERE subjects.schedule_id = schedules.id) as exam_count FROM schedules JOIN users ON schedules.owner_id = users.id");
    $query->execute();
    return $query->get_result();
}

function getMembers()
{
    global $db;

    $user = loggedInUser();
    $user_id = $user->id;

    // Fetch schedules where user is the owner OR a listed member
    $query = $db->prepare("SELECT id, name, email, role, created_at FROM users WHERE role <> 'admin' AND id <> ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    $users = [];
    while ($row = $result->fetch_object()) {
        $users[] = $row;
    }
    return $users;
}
function countTotalSchedules() {
    global $db;
    $query = $db->prepare("SELECT COUNT(*) as total FROM schedules");
    $query->execute();
    return $query->get_result()->fetch_object()->total;
}
