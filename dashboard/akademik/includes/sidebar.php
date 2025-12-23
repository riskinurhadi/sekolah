<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-navy fw-bold">E-Learning</h4>
        <small class="text-muted">Akademik</small>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>">
                <i class="fas fa-users me-2"></i> Data Pengguna
            </a>
        </li>
        <li>
            <a href="classes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'active' : '' ?>">
                <i class="fas fa-chalkboard me-2"></i> Data Kelas
            </a>
        </li>
        <li>
            <a href="subjects.php" class="<?= basename($_SERVER['PHP_SELF']) == 'subjects.php' ? 'active' : '' ?>">
                <i class="fas fa-book me-2"></i> Mata Pelajaran
            </a>
        </li>
        <li>
            <a href="schedules.php" class="<?= basename($_SERVER['PHP_SELF']) == 'schedules.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelajaran
            </a>
        </li>
        <li>
            <a href="announcements.php" class="<?= basename($_SERVER['PHP_SELF']) == 'announcements.php' ? 'active' : '' ?>">
                <i class="fas fa-bullhorn me-2"></i> Pengumuman
            </a>
        </li>
    </ul>
</div>

