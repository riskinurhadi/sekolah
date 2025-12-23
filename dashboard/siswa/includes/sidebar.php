<div class="sidebar">
    <div class="sidebar-header">
        <a href="index.php" class="sidebar-logo">E-Learning</a>
    </div>
    
    <div class="sidebar-section-title">MENU UTAMA</div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="my_classes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'my_classes.php' || basename($_SERVER['PHP_SELF']) == 'class_view.php' ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i> Kelas Saya
            </a>
        </li>
        <li>
            <a href="results.php" class="<?= basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i> Hasil Belajar
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">LAINNYA</div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="#">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </li>
        <li>
            <a href="../../logout.php">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </li>
    </ul>
</div>
