<?php
require_once '../../config/database.php';
include 'includes/header.php';

// Stats
$stats = [
    'users' => $conn->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'classes' => $conn->query("SELECT COUNT(*) FROM classes")->fetchColumn(),
    'subjects' => $conn->query("SELECT COUNT(*) FROM subjects")->fetchColumn(),
    'schedules' => $conn->query("SELECT COUNT(*) FROM schedules")->fetchColumn(),
];
?>

<div class="container-fluid">
    <h3 class="mb-4">Dashboard Overview</h3>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['users'] ?></h3>
                        <small class="text-muted">Total Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['classes'] ?></h3>
                        <small class="text-muted">Total Kelas</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['subjects'] ?></h3>
                        <small class="text-muted">Mata Pelajaran</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['schedules'] ?></h3>
                        <small class="text-muted">Jadwal Aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-custom p-4">
                <h5>Selamat Datang di Panel Akademik</h5>
                <p>Anda dapat mengelola data pengguna, kelas, mata pelajaran, dan jadwal pelajaran melalui menu di samping.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

