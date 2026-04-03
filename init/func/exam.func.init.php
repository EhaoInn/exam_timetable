<?php

function createExam($subject_id, $exam_date, $start_time, $end_time, $venue, $notes)
{
    global $db;

    // Only schedule owner or admin can add exams
    if (!Permission::checkExamPermission($subject_id)) return false;

    $query = $db->prepare("INSERT INTO exams (subject_id, exam_date, start_time, end_time, venue, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("isssss", $subject_id, $exam_date, $start_time, $end_time, $venue, $notes);
    $query->execute();

    return $db->affected_rows > 0;
}

function getExamById($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM exams WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}

function updateExam($id, $subject_id, $exam_date, $start_time, $end_time, $venue, $notes)
{
    global $db;

    // Only schedule owner or admin can update exams
    if (!Permission::checkExamPermission($subject_id)) return false;

    $query = $db->prepare("UPDATE exams SET subject_id = ?, exam_date = ?, start_time = ?, end_time = ?, venue = ?, notes = ? WHERE id = ?");
    $query->bind_param("isssssi", $subject_id, $exam_date, $start_time, $end_time, $venue, $notes, $id);
    $query->execute();

    return $db->affected_rows >= 0;
}

function deleteExam($id)
{
    global $db;

    $exam = getExamById($id);
    // Only schedule owner or admin can delete exams
    if (!$exam || !Permission::checkExamPermission($exam['subject_id'])) return false;

    $query = $db->prepare("DELETE FROM exams WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();

    return $db->affected_rows > 0;
}

function getUpComingExams()
{
    global $db;

    $user = loggedInUser();
    if (!$user) return [];

    $user_id = $user->id;

    $query = $db->prepare("
        SELECT DISTINCT ex.* , su.name subject_name, su.color, sc.id schedule_id
        FROM exams ex
        JOIN subjects su
        ON ex.subject_id = su.id
        JOIN schedules sc 
        ON sc.id = su.schedule_id
        LEFT JOIN schedule_members sm
        ON sm.user_id = sc.owner_id
        WHERE (sc.owner_id = ? OR sm.user_id = ?)
        AND ex.exam_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        ORDER BY ex.start_time ASC
    ");

    $query->bind_param("ii", $user_id, $user_id);
    $query->execute();
    $result = $query->get_result();

    $exams = [];
    while ($row = $result->fetch_object()) {
        $exams[] = $row;
    }

    return $exams;
}

function countTotalExams()
{
    global $db;
    $result = $db->query("SELECT COUNT(*) as total FROM exams");
    return $result->fetch_object()->total;
}

function getRecentExams($limit = 5) {
    global $db;
    $query = $db->prepare("SELECT e.*, s.name as subject_name, s.color FROM exams e JOIN subjects s ON e.subject_id = s.id ORDER BY e.exam_date DESC, e.start_time DESC LIMIT ?");
    $query->bind_param("i", $limit);
    $query->execute();
    return $query->get_result();
}

function getExamStatusStats() {
    global $db;
    $stats = ['upcoming' => 0, 'completed' => 0];
    $result = $db->query("SELECT (CASE WHEN exam_date >= CURDATE() THEN 'upcoming' ELSE 'completed' END) as status, COUNT(*) as count FROM exams GROUP BY status");
    while ($row = $result->fetch_assoc()) {
        $stats[$row['status']] = $row['count'];
    }
    return $stats;
}
