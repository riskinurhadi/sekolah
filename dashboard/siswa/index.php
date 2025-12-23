<?php
require_once '../../config/database.php';
include 'includes/header.php';

$class_id = $_SESSION['class_id'];
$student_id = $_SESSION['user_id'];

if (!$class_id) {
    echo "<div class='alert alert-warning'>Anda belum masuk ke dalam kelas manapun. Hubungi akademik.</div>";
    include 'includes/footer.php';
    exit;
}

// 1. Stats Queries
// Total Mapel
$stmt = $conn->prepare("SELECT COUNT(DISTINCT subject_id) FROM schedules WHERE class_id = ?");
$stmt->execute([$class_id]);
$total_subjects = $stmt->fetchColumn();

// Total Kehadiran
$stmt = $conn->prepare("SELECT COUNT(*) FROM attendance_logs WHERE student_id = ? AND status = 'Hadir'");
$stmt->execute([$student_id]);
$total_presence = $stmt->fetchColumn();

// Total Tugas
$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM assignments a
    JOIN schedules s ON a.schedule_id = s.id
    WHERE s.class_id = ?
");
$stmt->execute([$class_id]);
$total_assignments = $stmt->fetchColumn();

// Total Materi
$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM materials m
    JOIN schedules s ON m.schedule_id = s.id
    WHERE s.class_id = ?
");
$stmt->execute([$class_id]);
$total_materials = $stmt->fetchColumn();

// 2. Schedule Today
$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$today = $days[date('w')];

$query = "
    SELECT s.*, sub.subject_name, u.full_name as teacher_name 
    FROM schedules s 
    JOIN subjects sub ON s.subject_id = sub.id 
    JOIN users u ON s.teacher_id = u.id 
    WHERE s.class_id = ? AND s.day_of_week = ?
    ORDER BY s.start_time ASC
    LIMIT 5
";
$stmt = $conn->prepare($query);
$stmt->execute([$class_id, $today]);
$today_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Recent Activities
$stmt = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Page Header -->
<div class="page-header">
    <h1>Home</h1>
    <p>Selamat datang kembali, <?= htmlspecialchars($_SESSION['full_name']) ?>! 👋</p>
</div>

<!-- Date Filter (Optional) -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <div class="d-flex gap-2 align-items-center">
        <i class="far fa-calendar"></i>
        <span class="text-muted"><?= date('d/m/Y') ?> - <?= date('d/m/Y', strtotime('+7 days')) ?></span>
    </div>
</div>

<!-- Stats Cards -->
<div class="stat-cards-grid">
    <div class="stat-card-modern stat-blue">
        <div class="stat-icon-modern blue">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <h3><?= $total_subjects ?></h3>
            <p>Total Mata Pelajaran</p>
            <small class="text-muted">Semester ini</small>
        </div>
    </div>

    <div class="stat-card-modern stat-purple">
        <div class="stat-icon-modern purple">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3><?= $total_presence ?></h3>
            <p>Total Kehadiran</p>
            <small class="text-muted">Hari ini: <?= $today ?></small>
        </div>
    </div>

    <div class="stat-card-modern stat-green">
        <div class="stat-icon-modern green">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <h3><?= $total_materials ?></h3>
            <p>Materi Tersedia</p>
            <small class="text-muted">Semua kelas</small>
        </div>
    </div>

    <div class="stat-card-modern stat-orange">
        <div class="stat-icon-modern orange">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-content">
            <h3><?= $total_assignments ?></h3>
            <p>Total Tugas</p>
            <small class="text-muted">Pending & selesai</small>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row">
    <!-- Left Column: Jadwal Hari Ini -->
    <div class="col-lg-8 mb-4">
        <div class="card-modern">
            <div class="card-modern-header">
                <h5>Jadwal Hari Ini</h5>
                <a href="my_classes.php" class="view-all">Lihat Semua</a>
            </div>
            <div class="card-modern-body">
                <?php if (count($today_schedules) > 0): ?>
                    <?php foreach ($today_schedules as $schedule): ?>
                    <div class="list-item-modern">
                        <div class="list-item-icon" style="background: #EFF6FF; color: #3B82F6;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="list-item-content">
                            <h6><?= htmlspecialchars($schedule['subject_name']) ?></h6>
                            <p>
                                <i class="far fa-clock"></i> <?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?>
                                <span class="mx-2">•</span>
                                <i class="far fa-user"></i> <?= htmlspecialchars($schedule['teacher_name']) ?>
                            </p>
                        </div>
                        <div class="list-item-action">
                            <a href="class_view.php?schedule_id=<?= $schedule['id'] ?>" class="btn-modern btn-outline-modern">
                                Masuk
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <img src="https://illustrations.popsy.co/gray/compass.svg" alt="Empty">
                        <p>Tidak ada jadwal pelajaran hari ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Pengumuman & Quick Actions -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="card-modern mb-4">
            <div class="card-modern-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-modern-body">
                <div class="d-grid gap-2">
                    <a href="my_classes.php" class="btn-modern btn-primary-modern">
                        <i class="fas fa-book-open"></i> Lihat Semua Kelas
                    </a>
                    <a href="results.php" class="btn-modern btn-outline-modern">
                        <i class="fas fa-chart-line"></i> Hasil Belajar
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="card-modern">
            <div class="card-modern-header">
                <h5>Pengumuman</h5>
            </div>
            <div class="card-modern-body">
                <?php if (count($announcements) > 0): ?>
                    <?php foreach ($announcements as $info): ?>
                    <div class="list-item-modern">
                        <div class="list-item-icon" style="background: #FEF3C7; color: #F59E0B;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="list-item-content">
                            <h6><?= htmlspecialchars($info['title']) ?></h6>
                            <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars(substr($info['content'], 0, 80)) ?>...
                            </p>
                            <small class="text-muted"><?= date('d M Y', strtotime($info['created_at'])) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada pengumuman terbaru.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
