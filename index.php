<?php
session_start();
require_once('init/init.php');

include('includes/header.inc.php');
$user = loggedInUser();
$isAdmin = isAdmin();

// echo $isAdmin;
include('includes/navbar.inc.php');;

$logged_in_pages = ['dashboard','add_schedule','add_subject','add_exam','edit_exam','timetable'];
$none_logged_in_pages = ['login', 'register'];
$admin_pages = ['admin/panel','admin/users'];
// ... spread operator
// this called destructuring
// $available_pages = ['logout', ...$none_logged_in_pages, ...$logged_in_pages, ...$admin_pages];
// merge all arrays into one
$available_pages = array_merge(['logout'],$none_logged_in_pages,$logged_in_pages,$admin_pages);

if(isset($_GET['page'])){
    $page = $_GET['page'];
}

if (in_array($page, $logged_in_pages) && empty($user)) {
    header('location: ./?page=login');
}
if (in_array($page, $none_logged_in_pages) && !empty($user)) {
    header('location: ./?page=dashboard');
}
if (in_array($page, $available_pages)) {
    if (in_array($page, $admin_pages) && !$isAdmin) {
        header('location: ./?page=dashboard');
    }   
    include('pages/' . $page . '.php');
} else {
    header('location: ./?page=login');
}

include('includes/footer.inc.php')


?>