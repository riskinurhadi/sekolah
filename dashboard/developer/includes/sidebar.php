<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-navy fw-bold">E-Learning</h4>
        <small class="text-muted">Developer</small>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="px-3 mt-3 mb-1 text-muted small">MANAJEMEN DATA (via Akademik)</li>
        <li>
            <a href="../../dashboard/akademik/users.php">
                <i class="fas fa-users me-2"></i> Data Pengguna
            </a>
        </li>
        <li>
            <a href="../../dashboard/akademik/classes.php">
                <i class="fas fa-chalkboard me-2"></i> Data Kelas
            </a>
        </li>
        <li>
            <a href="../../dashboard/akademik/subjects.php">
                <i class="fas fa-book me-2"></i> Mata Pelajaran
            </a>
        </li>
        <li>
            <a href="../../dashboard/akademik/schedules.php">
                <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelajaran
            </a>
        </li>
    </ul>
</div>

