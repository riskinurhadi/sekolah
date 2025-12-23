<?php
require_once '../../config/database.php';
include 'includes/header.php';

$class_id = $_SESSION['class_id'];
$student_id = $_SESSION['user_id'];

if (!$class_id) {
    echo "<div class='container-fluid mt-4'><div class='alert alert-warning'>Anda belum masuk ke dalam kelas manapun. Hubungi akademik.</div></div>";
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

// Total Tugas (Pending - Placeholder logic as we don't track 'completed' assignments fully yet)
// For now, just count total assignments in class
$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM assignments a
    JOIN schedules s ON a.schedule_id = s.id
    WHERE s.class_id = ?
");
$stmt->execute([$class_id]);
$total_assignments = $stmt->fetchColumn();

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
";
$stmt = $conn->prepare($query);
$stmt->execute([$class_id, $today]);
$today_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Announcements
$stmt = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    
    <!-- Welcome Banner -->
    <div class="card card-custom mb-4 border-0">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold text-navy mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h2>
                <p class="text-muted mb-0">Berikut adalah ringkasan aktivitas belajar kamu.</p>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['full_name']) ?>&background=001f3f&color=fff&size=64" class="rounded-circle shadow-sm" alt="Profile">
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card card-custom h-100 border-0">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="stat-icon bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-book fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_subjects ?></h3>
                        <small class="text-muted">Mata Pelajaran</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card card-custom h-100 border-0">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="stat-icon bg-success text-white rounded-circle p-3 me-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_presence ?></h3>
                        <small class="text-muted">Total Kehadiran</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom h-100 border-0">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="stat-icon bg-warning text-white rounded-circle p-3 me-3">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_assignments ?></h3>
                        <small class="text-muted">Total Tugas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column: Schedule -->
        <div class="col-lg-8 mb-4">
            <div class="card card-custom h-100 border-0">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Jadwal Hari Ini (<?= $today ?>)</h5>
                    <a href="my_classes.php" class="text-decoration-none small">Lihat Semua</a>
                </div>
                <div class="card-body px-4 pb-4">
                    <?php if (count($today_schedules) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($today_schedules as $schedule): ?>
                            <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3 text-center" style="width: 50px;">
                                        <i class="fas fa-chalkboard text-navy"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($schedule['subject_name']) ?></h6>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i> <?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?>
                                            <span class="mx-2">|</span>
                                            <i class="far fa-user me-1"></i> <?= htmlspecialchars($schedule['teacher_name']) ?>
                                        </small>
                                    </div>
                                </div>
                                <a href="class_view.php?schedule_id=<?= $schedule['id'] ?>" class="btn btn-sm btn-outline-navy rounded-pill px-3">
                                    Masuk
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/surr-chill.svg" alt="Empty" style="width: 150px; opacity: 0.5;">
                            <p class="text-muted mt-3">Tidak ada jadwal pelajaran hari ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Announcements -->
        <div class="col-lg-4 mb-4">
            <div class="card card-custom h-100 border-0">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Pengumuman</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <?php if (count($announcements) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($announcements as $info): ?>
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex w-100 justify-content-between mb-1">
                                    <h6 class="mb-0 fw-semibold text-truncate" style="max-width: 70%;"><?= htmlspecialchars($info['title']) ?></h6>
                                    <small class="text-muted"><?= date('d M', strtotime($info['created_at'])) ?></small>
                                </div>
                                <p class="mb-0 text-muted small line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($info['content']) ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small">Belum ada pengumuman terbaru.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Local overrides for specific dashboard look */
.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.btn-outline-navy {
    color: var(--primary-color);
    border-color: var(--primary-color);
}
.btn-outline-navy:hover {
    background-color: var(--primary-color);
    color: white;
}
</style>

<?php include 'includes/footer.php'; ?>
