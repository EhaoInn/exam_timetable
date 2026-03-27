<?php
session_start();
require_once('init/init.php');

$page = $_GET['page'] ?? 'login';

include('includes/header.inc.php');
$user = loggedInUser();
$isAdmin = isAdmin();

// echo $isAdmin;
include('includes/navbar.inc.php');

$logged_in_pages = ['dashboard', 'add_schedule', 'edit_schedule', 'add_subject', 'add_exam', 'delete_exam', 'edit_exam', 'timetable'];
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
?>