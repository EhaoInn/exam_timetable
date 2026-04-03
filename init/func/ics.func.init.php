<?php

function generateICS($exams, $schedule_title = "Exam Schedule") {
    $ics_content = "BEGIN:VCALENDAR\r\n";
    $ics_content .= "VERSION:2.0\r\n";
    $ics_content .= "PRODID:-//Exam Timetable//EN\r\n";
    $ics_content .= "CALSCALE:GREGORIAN\r\n";
    $ics_content .= "METHOD:PUBLISH\r\n";

    foreach ($exams as $exam) {
        $dtstart = date('Ymd\THis', strtotime($exam->exam_date . ' ' . $exam->start_time));
        $dtend = date('Ymd\THis', strtotime($exam->exam_date . ' ' . $exam->end_time));
        $now = date('Ymd\THis');
        
        $ics_content .= "BEGIN:VEVENT\r\n";
        $ics_content .= "UID:" . uniqid() . "@examtimetable.com\r\n";
        $ics_content .= "DTSTAMP:" . $now . "Z\r\n";
        $ics_content .= "DTSTART:" . $dtstart . "\r\n";
        $ics_content .= "DTEND:" . $dtend . "\r\n";
        $ics_content .= "SUMMARY:" . escapeICS($exam->subject_name . " Exam") . "\r\n";
        $ics_content .= "DESCRIPTION:" . escapeICS("Lecturer: " . $exam->lecturer) . "\r\n";
        $ics_content .= "LOCATION:" . escapeICS($exam->venue) . "\r\n";
        $ics_content .= "END:VEVENT\r\n";
    }

    $ics_content .= "END:VCALENDAR\r\n";
    return $ics_content;
}

function escapeICS($string) {
    return str_replace(['\\', ',', ';', "\n"], ['\\\\', '\,', '\;', ' \n'], $string);
}
