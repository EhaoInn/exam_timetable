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
    return $query->get_result();
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

function countTotalSubjects() {
    global $db;
    $result = $db->query("SELECT COUNT(*) as total FROM subjects");
    return $result->fetch_object()->total;
}

?>