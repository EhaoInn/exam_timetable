<?php

function createExam($subject_id, $exam_date, $start_time ,$end_time ,$venue ,$notes){
    global $db;
    
    $query = $db->prepare("INSERT INTO exams (subject_id, exam_date, start_time, end_time, venue, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("isssss", $subject_id, $exam_date, $start_time, $end_time, $venue, $notes);
    $query->execute();

    if ($db->affected_rows) {
        return true;
    }
    return false;
}

?>