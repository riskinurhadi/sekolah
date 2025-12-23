<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'siswa' && $_SESSION['role'] !== 'developer')) {
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="top-navbar-left">
                <span class="top-logo">SmartLearn</span>
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                </div>
            </div>
            <div class="navbar-actions">
                <button class="btn-modern btn-primary-modern d-none d-md-inline-flex">
                    <i class="fas fa-plus"></i> Mulai Belajar
                </button>
                <button class="icon-btn">
                    <i class="far fa-bell"></i>
                </button>
                <div class="user-profile">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['full_name'], 0, 2)) ?>
                    </div>
                    <div class="user-info">
                        <h6><?= htmlspecialchars($_SESSION['full_name']); ?></h6>
                        <p>Siswa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-wrapper">
