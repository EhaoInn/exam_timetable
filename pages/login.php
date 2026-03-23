<?php
$email = '';
$emailErr = $passwordErr = '';

if (isset($_POST['email'], $_POST['password'])) {

  $email = $_POST['email'];
  $password = trim($_POST['password']);

  if (empty($email)) {
    $emailErr = 'Please input email!';
  }

  if (empty($password)) {
    $passwordErr = 'Please input password!';
  }

  if (empty($emailErr) && empty($passwordErr)) {
    $user = logUserIn($email, $password);

    if ($user !== false) {
      $_SESSION['user_id'] = $user->id;
      // print_r($user);
      // die();
      header('Location: ./?page=dashboard');
      exit;
    } else {
      echo '<div class="alert alert-danger text-center">
                Login failed!
            </div>';
    }
  }
}
?>

<div class="d-flex justify-content-center align-items-center"
  style="min-height:100vh;background:#f8f9fa">
  <div style="width:100%;max-width:420px;padding:16px">
    <h4 class="text-center mb-4">Exam Timetable</h4>
    <!--  -->
    <div class="card p-4">
      <h6 class="mb-3">Sign in</h6>
      <form action="./?page=login" method="POST">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email"
            value="<?php echo htmlspecialchars($email) ?>"
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <p class="text-center mt-3 mb-0 small">
        No account?
        <a href="?page=register">Register here</a>
      </p>
    </div>
  </div>
</div>