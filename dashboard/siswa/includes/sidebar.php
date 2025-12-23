<div class="sidebar">
    <div class="sidebar-header">
        <a href="index.php" class="sidebar-logo">SmartLearn</a>
    </div>
    
    <!-- Sidebar Search -->
    <div class="sidebar-search">
        <input type="text" placeholder="Search..." class="sidebar-search-input">
    </div>
    
    <!-- Menu Categories -->
    <div class="sidebar-section-title">AKADEMIK</div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="has-dropdown <?= (basename($_SERVER['PHP_SELF']) == 'my_classes.php' || basename($_SERVER['PHP_SELF']) == 'class_view.php') ? 'active' : '' ?>">
            <a href="my_classes.php" class="dropdown-toggle">
                <i class="fas fa-book-open"></i>
                <span>Kelas Saya</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </a>
            <ul class="dropdown-menu">
                <li><a href="my_classes.php">Semua Kelas</a></li>
                <li><a href="class_view.php">Akses Kelas</a></li>
            </ul>
        </li>
        <li>
            <a href="results.php" class="<?= basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i>
                <span>Hasil Belajar</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">AKTIVITAS</div>
    <ul class="sidebar-menu">
        <li>
            <a href="#">
                <i class="fas fa-tasks"></i>
                <span>Tugas Saya</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-clipboard-check"></i>
                <span>Presensi</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-file-alt"></i>
                <span>Ujian</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-bullhorn"></i>
                <span>Pengumuman</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">PENGATURAN</div>
    <ul class="sidebar-menu">
        <li>
            <a href="#">
                <i class="fas fa-user-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </li>
        <li>
            <a href="../../logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </a>
        </li>
    </ul>
</div>
