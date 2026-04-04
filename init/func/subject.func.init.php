<?php

function createSubject($schedule_id, $code, $name, $lecturer, $color){
    global $db;

    $query = $db->prepare("INSERT INTO subjects (schedule_id, code, name, lecturer, color) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("issss", $schedule_id, $code, $name, $lecturer, $color);
    $query->execute();

    if ($db->affected_rows) {
        return true;
    }

    return false;
}

function getSubjectsBySchedule($schedule_id) {
    global $db;
    $query = $db->prepare("SELECT * FROM subjects WHERE schedule_id = ?");
    $query->bind_param("i", $schedule_id);
    $query->execute();
    $result = $query->get_result();

    $subjects = [];
    while ($row = $result->fetch_object()) {
        $subjects[] = $row;
    }
    return $subjects;
}

function getSubjects() {
    global $db;
    $query = $db->prepare("SELECT * FROM subjects");
    $query->execute();
    return $query->get_result();
}

function getSubjectById($id) {
    global $db;
    $query = $db->prepare("SELECT * FROM subjects WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}

function updateSubject($id, $code, $name, $lecturer, $color) {
    global $db;
    $query = $db->prepare("UPDATE subjects SET code = ?, name = ?, lecturer = ?, color = ? WHERE id = ?");
    $query->bind_param("ssssi", $code, $name, $lecturer, $color, $id);
    return $query->execute();
}

function deleteSubject($id) {
    global $db;
    
    $db->begin_transaction();
    try {
        // 1. Delete all exams associated with this subject
        $exam_query = $db->prepare("DELETE FROM exams WHERE subject_id = ?");
        $exam_query->bind_param("i", $id);
        $exam_query->execute();

        // 2. Delete the subject itself
        $subject_query = $db->prepare("DELETE FROM subjects WHERE id = ?");
        $subject_query->bind_param("i", $id);
        $subject_query->execute();

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollback();
        return false;
    }
}

function countTotalSubjects() {
    global $db;
    $result = $db->query("SELECT COUNT(*) as total FROM subjects");
    return $result->fetch_object()->total;
}

?>