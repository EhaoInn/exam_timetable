<?php
$current_page = $_GET['page'] ?? '';

$user = loggedInUser();
$upcomingExams = !empty($user) ? getUpComingExams() : [];
$upcomingCount = count($upcomingExams);

if (!empty($user)): 
?>
<style>
    .navbar-floating {
        position: fixed;
        top: 15px;
        left: 50%;
        transform: translateX(-50%);
        width: 95%;
        max-width: 1200px;
        z-index: 1050;
        transition: all 0.3s ease;
    }

    .navbar-glass {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(15px) saturate(180%);
        -webkit-backdrop-filter: blur(15px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    }

    [data-bs-theme="dark"] .navbar-glass {
        background: rgba(30, 30, 30, 0.75) !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }

    .nav-link {
        font-weight: 500;
        color: var(--bs-secondary-color) !important;
        padding: 8px 16px !important;
        border-radius: 12px;
        transition: all 0.2s ease;
        margin: 0 4px;
    }

    .nav-link:hover {
        background: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary) !important;
    }

    .nav-link.active {
        background: var(--bs-primary) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.25);
    }

    .navbar-brand {
        font-weight: 700;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--bs-primary) !important;
    }

    /* Notification Bell Pulse */
    .bell-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .notification-dropdown {
        min-width: 340px;
        border-radius: 18px !important;
        border: 0 !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
        overflow: hidden;
    }

    .user-pill {
        background: rgba(var(--bs-primary-rgb), 0.05);
        border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
        border-radius: 14px;
        padding: 5px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    body {
        padding-top: 100px !important; /* Offset for floating navbar */
    }

    /* Icons inside buttons */
    #theme-toggle i, .logout_button i {
        color: var(--bs-primary);
        transition: transform 0.2s ease;
    }

    #theme-toggle:hover i, .logout_button:hover i {
        transform: scale(1.2);
    }
</style>

<nav class="navbar-floating">
    <div class="navbar navbar-expand-lg navbar-glass py-2 px-3">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand me-4" href="./?page=dashboard">
                <div class="rounded-circle bg-primary-subtle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fas fa-calendar-alt text-primary fs-5"></i>
                </div>
                <span class="d-none d-sm-inline">Exam Timetable</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="fs-4"><i class="fas fa-bars"></i></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>" href="./?page=dashboard">
                            <i class="fas fa-th-large me-1 small"></i> Schedules
                        </a>
                    </li>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($current_page, 'admin/') === 0 ? 'active' : '' ?>" href="./?page=admin/panel">
                                <i class="fas fa-crown me-1 small"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Action Items -->
                <ul class="navbar-nav ms-auto align-items-center gap-2 mt-3 mt-lg-0">
                    
                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <button class="btn btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center" 
                                id="theme-toggle" style="width: 38px; height: 38px;" title="Switch Mode">
                            <i class="fas fa-moon" id="theme-icon"></i>
                        </button>
                    </li>

                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="btn btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center position-relative" 
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 38px; height: 38px;">
                            <i class="fas fa-bell <?= $upcomingCount > 0 ? 'bell-pulse text-primary' : '' ?>"></i>
                            <?php if ($upcomingCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 4px; margin-left: -5px;">
                                    <?= $upcomingCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0 shadow-lg border-0 animate-fade-in mt-3">
                            <div class="bg-primary p-3 text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Alerts & Notifications</h6>
                                <span class="badge bg-white text-primary rounded-pill small"><?= $upcomingCount ?> Upcoming</span>
                            </div>
                            <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                                <?php if ($upcomingCount > 0): ?>
                                    <?php foreach ($upcomingExams as $exam): ?>
                                        <a href="./?page=timetable&id=<?= $exam->schedule_id ?>" class="list-group-item list-group-item-action p-3 border-0 border-bottom">
                                            <div class="d-flex w-100 justify-content-between mb-1">
                                                <h6 class="mb-1 text-primary fw-bold" style="font-size: 0.95rem;"><?= htmlspecialchars($exam->subject_name) ?></h6>
                                                <i class="fas fa-chevron-right text-muted small mt-1"></i>
                                            </div>
                                            <div class="d-flex gap-3 small text-secondary">
                                                <span><i class="far fa-clock text-primary me-1"></i> <?= date('h:i A', strtotime($exam->start_time)) ?></span>
                                                <span><i class="fas fa-map-marker-alt text-primary me-1"></i> <?= htmlspecialchars($exam->venue) ?></span>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-secondary">
                                        <i class="fas fa-calendar-check fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0 fw-medium">All caught up!</p>
                                        <small class="opacity-50">No exams scheduled for tomorrow.</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>

                    <!-- User Pill -->
                    <li class="nav-item">
                        <div class="user-pill shadow-sm border-0 bg-white">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 11px;">
                                <?= strtoupper(substr($user->name, 0, 1)) ?>
                            </div>
                            <span class="small fw-bold text-dark d-none d-md-inline"><?= htmlspecialchars($user->name) ?></span>
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary small d-none d-xl-inline" style="font-size: 0.7rem;">
                                <?= strtoupper($user->role) ?>
                            </span>
                        </div>
                    </li>

                    <!-- Logout Button -->
                    <li class="nav-item">
                        <a href="./?page=logout" class="logout_button btn btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center text-danger" 
                           style="width: 38px; height: 38px;" title="Logout">
                            <i class="fas fa-power-off"></i>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>

<script type="module">
  import { confirmAction } from './assets/js/utils.js';

  confirmAction('.logout_button', {
    title: "Leaving so soon?",
    text: "Make sure you've saved your progress before logging out.",
    icon: "question",
    confirmText: "Yes, logout",
    cancelText: "Stay"
  });

  // Dark Mode Toggle Logic
  const $themeToggle = $('#theme-toggle');
  const $themeIcon = $('#theme-icon');
  
  const updateIcon = (theme) => {
    if (theme === 'dark') {
      $themeIcon.removeClass('fa-moon').addClass('fa-sun');
    } else {
      $themeIcon.removeClass('fa-sun').addClass('fa-moon');
    }
  };

  updateIcon($('html').attr('data-bs-theme'));

  $themeToggle.on('click', function() {
    const currentTheme = $('html').attr('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    $('html').attr('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateIcon(newTheme);
  });
</script>
<?php endif ?>