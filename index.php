<?php
ob_start();
session_start();
require_once('init/init.php');

$page = $_GET['page'] ?? 'login';

$user = loggedInUser();
$isAdmin = isAdmin();

if ($page === 'export_calendar') {
    if (empty($user)) {
        header('location: ./?page=login');
        exit;
    }
    include('pages/export_calendar.php');
    exit;
}

include('includes/header.inc.php');
include('includes/navbar.inc.php');

$logged_in_pages = [
    'dashboard',
    'timetable',
    'schedules/add_schedule',
    'schedules/delete_schedule',
    'schedules/edit_schedule',
    'subjects/add_subject',
    'subjects/delete_subject',
    'subjects/edit_subject',
    'exams/add_exam',
    'exams/delete_exam',
    'exams/edit_exam',
    'export_calendar'
];

$none_logged_in_pages = ['login', 'register'];
$admin_pages = ['admin/panel', 'admin/users', 'admin/create_user', 'admin/edit_user'];
// ... spread operator
// this called destructuring
// $available_pages = ['logout', ...$none_logged_in_pages, ...$logged_in_pages, ...$admin_pages];
// merge all arrays into one
$available_pages = array_merge(['logout'], $none_logged_in_pages, $logged_in_pages, $admin_pages);

if (in_array($page, $logged_in_pages) && empty($user)) {
    header('location: ./?page=login');
    exit;
}
if (in_array($page, $none_logged_in_pages) && !empty($user)) {
    header('location: ./?page=dashboard');
    exit;
}

if (in_array($page, $available_pages)) {
    if (in_array($page, $admin_pages) && !$isAdmin) {
        header('location: ./?page=dashboard');
        exit;
    }
    include('pages/' . $page . '.php');
} else {
    header('location: ./?page=login');
    exit;
}

include('includes/footer.inc.php');
