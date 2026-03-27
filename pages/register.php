<?php
$name = $email = $password = '';
$nameErr = $emailErr = $passwordErr = '';
$success = false;
if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirmPassword'])) {

  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirmPassword = trim($_POST['confirmPassword']);

  if (empty($name)) {
    $nameErr = 'Please input name!';
  }

  if (empty($email)) {
    $emailErr = 'Please input email!';
  }

  if (empty($password)) {
    $passwordErr = 'Please input password!';
  }

  if ($password !== $confirmPassword) {
    $passwordErr = 'Password not match!';
  }

  if (emailExists($email)) {
    $emailErr = 'email exists!';
  }

  if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
    if (registerUser($name, $email, $password)) {
      $name = $email = $password = '';
      $success = true;
    }
  }
}
?>

<div class="d-flex justify-content-center align-items-center"
  style="min-height:100vh;background:#f8f9fa">
  <div style="width:100%;max-width:420px;padding:16px">
    <h4 class="text-center mb-4">Exam Timetable</h4>
    <?php if ($success): ?>
      <div class="alert alert-success">
        Account created!
        <a href="index.php?page=login">Login now</a>
      </div>
    <?php else: ?>
      <div class="card p-4">
        <h6 class="mb-3">Create account</h6>
        <form action="./?page=register" method="POST">
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
            <input type="password" name="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : '' ?>">
            <div class="invalid-feedback">
              <?php echo $passwordErr ?>
            </div>

          </div>

          <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input name="confirmPassword" type="password" class="form-control">
          </div>

          <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="text-center mt-3 mb-0 small">
          Already have an account?
          <a href="?page=login">Login</a>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>