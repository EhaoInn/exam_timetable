<?php

use Permission;

function createExam($subject_id, $exam_date, $start_time, $end_time, $venue, $notes) {
    global $db;
    
    // Security check: Only schedule owner or admin can add exams
    if (!Permission::checkExamPermission($subject_id)) return false;
    
    $query = $db->prepare("INSERT INTO exams (subject_id, exam_date, start_time, end_time, venue, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("isssss", $subject_id, $exam_date, $start_time, $end_time, $venue, $notes);
    $query->execute();

    return $db->affected_rows > 0;
}

function getExamById($id) {
    global $db;
    $query = $db->prepare("SELECT * FROM exams WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}

function updateExam($id, $subject_id, $exam_date, $start_time, $end_time, $venue, $notes) {
    global $db;

    // Security check: Only schedule owner or admin can update exams
    if (!Permission::checkExamPermission($subject_id)) return false;

    $query = $db->prepare("UPDATE exams SET subject_id = ?, exam_date = ?, start_time = ?, end_time = ?, venue = ?, notes = ? WHERE id = ?");
    $query->bind_param("isssssi", $subject_id, $exam_date, $start_time, $end_time, $venue, $notes, $id);
    $query->execute();

    // Check if affected_rows >= 0 because an update with identical values returns 0
    return $db->affected_rows >= 0;
}

function deleteExam($id) {
    global $db;

    $exam = getExamById($id);
    // Security check: Only schedule owner or admin can delete exams
    if (!$exam || !Permission::checkExamPermission($exam['subject_id'])) return false;

    $query = $db->prepare("DELETE FROM exams WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();

    return $db->affected_rows > 0;
}

function countTotalExams() {
    global $db;
    $result = $db->query("SELECT COUNT(*) as total FROM exams");
    return $result->fetch_object()->total;
}

?>