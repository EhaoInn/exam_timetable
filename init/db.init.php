<?php

$host = 'localhost'; // 127.0.0.1
$dbname = 'exam_timetable';
$dbuser = 'root';
$password = '';

$db = new mysqli($host, $dbuser, $password, $dbname);

if ($db->connect_error) {
    echo 'Connection failed' . $db->connect_error;

    die();
}