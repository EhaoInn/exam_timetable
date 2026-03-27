<?php
$current_page = $_GET['page'] ?? '';

$user = loggedInUser();
?>

<?php if (!empty($user)): ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-2">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="./?page=dashboard">
      Exam Timetable
    </a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?= $current_page==='dashboard'?'active':'' ?>"
             href="./?page=dashboard">My Schedules</a>
        </li>
        <?php if (isAdmin()): ?>
        <li class="nav-item">
          <a class="nav-link <?= $current_page==='admin/panel'?'active':'' ?>"
             href="./?page=admin/panel">Admin Panel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page==='admin/users'?'active':'' ?>"
             href="./?page=admin/users">Users</a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <span class="text-white-50 small"><?= htmlspecialchars(loggedInUser()->name ?? '') ?></span>
        </li>
        
        <li class="nav-item">
          <span class="badge <?= isAdmin() ? 'bg-success' : 'bg-danger' ?>">
            <?= htmlspecialchars(loggedInUser()->role ?? '') ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="btn btn-sm btn-outline-light logout_button"
             href="./?page=logout">Logout</a>
        </li>

        
      </ul>
    </div>
  </div>
</nav>
<?php endif ?>
<script type="module">
    import { confirmAction } from './assets/js/utils.js';
    
    confirmAction('.logout_button', {
        title: "Are you sure to logout?",
        icon: "question"
    });
</script>