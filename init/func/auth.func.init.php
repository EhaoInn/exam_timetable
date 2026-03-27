<?php
function emailExists($email)
{
    global $db;

    $query = $db->prepare("SELECT id FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }

    return false;
}

function emailExistsForOtherUser($email, $user_id)
{
    global $db;
    $query = $db->prepare("SELECT id FROM users WHERE email = ? AND id <> ?");
    $query->bind_param("si", $email, $user_id);
    $query->execute();
    $result = $query->get_result();
    return $result->num_rows > 0;
}

function registerUser($name, $email, $password)
{
    global $db;
    if (emailExists($email)) {
        return false;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = $db->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
    $query->bind_param('sss', $name, $email, $hashed_password);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function logUserIn($email, $password)
{
    global $db;
    $query = $db->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        $user = $result->fetch_object();
        if (password_verify($password, $user->password)) {
            return $user;
        }
    }

    return false;
}

function logIn($email, $password)
{
    global $db;
    $query = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $query->bind_param('ss', $email, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        $user = $result->fetch_object();
        return $user;
        // if (password_verify($password, $user->password)) {
        //     return $user;
        // }
    }

    return false;
}

function loggedInUser()
{
    global $db;

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $user_id = $_SESSION['user_id'];

    $query = $db->prepare("SELECT * FROM users WHERE id = ?");
    $query->bind_param('i', $user_id);
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
    if (!$user) return false;

    return password_verify($password, $user->password);
}

function setUserNewPassword($newPassword)
{
    global $db;
    $user = loggedInUser();
    if (!$user) return false;

    $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $query->bind_param('si', $hashed_password, $user->id);
    $query->execute();

    return $db->affected_rows > 0;
}

function isAdmin()
{
    return Permission::isAdmin();
}
?>
