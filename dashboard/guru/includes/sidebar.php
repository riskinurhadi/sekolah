<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-navy fw-bold">E-Learning</h4>
        <small class="text-muted">Guru Mata Pelajaran</small>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="schedules.php" class="<?= basename($_SERVER['PHP_SELF']) == 'schedules.php' || basename($_SERVER['PHP_SELF']) == 'class_detail.php' ? 'active' : '' ?>">
                <i class="fas fa-chalkboard-teacher me-2"></i> Jadwal Mengajar
            </a>
        </li>
        <!--
        <li>
            <a href="exams.php" class="<?= basename($_SERVER['PHP_SELF']) == 'exams.php' ? 'active' : '' ?>">
                <i class="fas fa-file-alt me-2"></i> Bank Soal
            </a>
        </li>
        -->
    </ul>
</div>

