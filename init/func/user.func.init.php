<?php

function getAllUsers($search = '', $excludeSelf = true) {
    global $db;
    $sql = "SELECT id, name, email, role, created_at FROM users WHERE role <> 'admin'";
    $params = [];
    $types = "";

    if ($search) {
        $sql .= " AND (name LIKE ? OR email LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;

        // var_dump($params);

        $types .= "ss";
    }

    if($excludeSelf){
        $sql .= " AND id <> ?";
        $params[] = loggedInUser()->id;
        $types .= "i";
    }

    $query = $db->prepare($sql);

        // echo $sql;
        // var_dump($params);

    // die();
    if (!empty($params)) {
        $query->bind_param($types, ...$params);
    }
    $query->execute();
    $result = $query->get_result();

    $users = [];
    while ($row = $result->fetch_object()) {
        $users[] = $row;
    }
    return $users;
}

function createUser($name, $email, $password)
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

function getUserById($id)
{
    global $db;
    $query = $db->prepare('SELECT * FROM users WHERE id=?');
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}


function editUser($id, $name, $email, $password = '')
{
    global $db;

    $target_user = getUserById($id);
    if (!$target_user) return false;

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = $db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $query->bind_param('sssi', $name, $email, $password, $id);
    } else {
        $query = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $query->bind_param('ssi', $name, $email, $id);
    }
    
    return $query->execute();
}

function getUserStats() {
    global $db;
    $stats = [
        'total' => 0,
        'admins' => 0,
        'users' => 0
    ];

    $result = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    while ($row = $result->fetch_assoc()) {
        if ($row['role'] === 'admin') {
            $stats['admins'] = $row['count'];
        } else {
            $stats['users'] += $row['count'];
        }
        $stats['total'] += $row['count'];
    }
    return $stats;
}

function deleteUser($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param('i', $id);
    return $query->execute();
}

function toggleUserRole($id) {
    global $db;
    $user = getUserById($id);
    if (!$user) return false;
    
    $newRole = ($user->role === 'admin') ? 'user' : 'admin';
    $query = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
    $query->bind_param('si', $newRole, $id);
    return $query->execute();
}

?>