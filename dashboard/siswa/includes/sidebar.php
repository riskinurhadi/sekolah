<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-navy fw-bold">E-Learning</h4>
        <small class="text-muted">Siswa</small>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="my_classes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'my_classes.php' || basename($_SERVER['PHP_SELF']) == 'class_view.php' ? 'active' : '' ?>">
                <i class="fas fa-book-reader me-2"></i> Kelas Saya
            </a>
        </li>
        <li>
            <a href="results.php" class="<?= basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line me-2"></i> Hasil Belajar
            </a>
        </li>
    </ul>
</div>

