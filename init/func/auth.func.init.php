<?php
function emailExists($username)
{
    global $db;

    $query = $db->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }

    return false;
}
function registerUser($name, $email, $password)
{
    global $db;
    if (emailExists($email)) {
        return false;
    }

    $query = $db->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
    $query->bind_param('sss', $name, $email, $password);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
function logUserIn($email, $password)
{
    global $db;
    $query = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $query->bind_param('ss', $email, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return $result->fetch_object();
    }

    return false;
}
function loggedInUser()
{
    global $db;

    // Check if session exists return null if not
    // if exists store in $user_id
    // check user if exists in database
    // if exists return user object else return null

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $user_id = $_SESSION['user_id'];

    $query = $db->prepare("SELECT * FROM users WHERE id = ?");
    $query->bind_param('d', $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return $result->fetch_object();
    }

    return null;
}
function isUserHasPassword($password)
{
    global $db;

    $user = loggedInUser();

    $query = $db->prepare("SELECT * FROM users WHERE id = ? AND password = ?");
    $query->bind_param('ss', $user->id, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return true;
    }

    return false;
}
function setUserNewPassowrd($newPassword)
{
    global $db;
    $user = loggedInUser();
    $query = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $query->bind_param('ss', $newPassword, $user->id);
    $query->execute();

    if ($db->affected_rows) {
        return true;
    }

    return false;
}
function isAdmin()
{
    return loggedInUser() && loggedInUser()->role === 'admin';
}

function getAllUsers() {
    global $db;
    $current_user = loggedInUser();
    $current_id = $current_user ? $current_user->id : 0;

    $query = $db->prepare("SELECT id, name, email FROM users WHERE id != ?");
    $query->bind_param("i", $current_id);
    $query->execute();
    $result = $query->get_result();

    $users = [];
    while ($row = $result->fetch_object()) {
        $users[] = $row;
    }
    return $users;
}

?>
