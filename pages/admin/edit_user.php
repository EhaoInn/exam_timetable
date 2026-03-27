<?php

$id = $_GET["id"] ?? null;
$target_user = getUserById($id);

if (!$id || $target_user->role == 'admin') {
    header("Location: ./?page=admin/users");
    exit;
}


if (!$target_user) {
    echo "User not found!";
    exit;
}


$name = $target_user->name;
$email = $target_user->email;
$password = '';

$nameErr = $emailErr = $passErr = '';
$errMsg = '';
$success = false;

if (isset($_POST['name'], $_POST['email'], $_POST['password'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name)) {
        $nameErr = 'Please input name!';
    }

    if (empty($email)) {
        $emailErr = 'Please input email!';
    }

    if (emailExistsForOtherUser($email, $id)) {
        $emailErr = 'email exists!';
    }

    if (empty($nameErr) && empty($emailErr)) {
        try {
            if (editUser($id, $name, $email, $password)) {
                $success = true;
                $target_user = getUserById($id);
                $name = $target_user->name;
                $email = $target_user->email;
                $password = '';
            } else {
                $errMsg = "Failed to update user.";
            }
        } catch (Exception $e) {
            $success = false;
            $errMsg = $e->getMessage();
        }
    }
}
?>

<div class="d-flex justify-content-center align-items-center"
    style="min-height:100vh;background:#f8f9fa">
    <div style="width:100%;max-width:420px;padding:16px">
        <div class="card p-4">
            <h6 class="mb-3">Edit user account</h6>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 small mb-4 py-2 text-center shadow-sm">
                    <i class="fas fa-check-circle me-1"></i>User updated successfully
                </div>
            <?php elseif ($errMsg): ?>
                <div class="alert alert-danger border-0 small mb-4 py-2 text-center shadow-sm">
                    <i class="fas fa-exclamation-circle me-1"></i><?= $errMsg ?>
                </div>
            <?php endif; ?>

            <form action="./?page=admin/edit_user&id=<?= $id ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Full name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name) ?>"
                        class="form-control <?php echo $nameErr ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $nameErr ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email) ?>"
                        class="form-control <?php echo $emailErr ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $emailErr ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-control <?php echo $passErr ? 'is-invalid' : '' ?>"
                        placeholder="Leave blank to keep current password">
                    <div class="invalid-feedback">
                        <?php echo $passErr ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Update Account</button>
                <a href="./?page=admin/users" class="btn btn-secondary w-100">Back</a>
            </form>
        </div>
    </div>
</div>