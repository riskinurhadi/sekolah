<?php
require_once '../../config/database.php';
include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="alert alert-info">
        <h5><i class="fas fa-code me-2"></i> Mode Developer</h5>
        <p class="mb-0">Anda memiliki akses penuh ke sistem. Silahkan gunakan menu navigasi untuk mengelola data.</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card card-custom p-4 text-center">
                <h5>Akses Cepat</h5>
                <div class="d-grid gap-2 mt-3">
                    <a href="../../dashboard/akademik/index.php" class="btn btn-outline-primary">Ke Dashboard Akademik</a>
                    <a href="../../dashboard/guru/index.php" class="btn btn-outline-success">Ke Dashboard Guru</a>
                    <a href="../../dashboard/siswa/index.php" class="btn btn-outline-warning">Ke Dashboard Siswa</a>
                    <a href="../../dashboard/kepala_sekolah/index.php" class="btn btn-outline-info">Ke Dashboard Kepala Sekolah</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-custom p-4">
                <h5>System Status</h5>
                <p>Database: Connected</p>
                <p>Server Time: <?= date('Y-m-d H:i:s') ?></p>
                <p>PHP Version: <?= phpversion() ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

