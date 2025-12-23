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

<!-- Page Header with Analytics Title -->
<div class="page-header-modern">
    <div class="page-header-left">
        <h1>Home</h1>
        <p>Selamat datang kembali, <?= htmlspecialchars($_SESSION['full_name']) ?>! 👋</p>
    </div>
    <div class="page-header-right">
        <div class="date-picker-modern">
            <i class="far fa-calendar-alt"></i>
            <span><?= date('d/m/Y') ?> - <?= date('d/m/Y', strtotime('+7 days')) ?></span>
        </div>
    </div>
</div>

<!-- Analytics Section Title -->
<div class="analytics-section-title">
    <h2>Statistik Pembelajaran Saya</h2>
</div>

<!-- Stats Cards -->
<div class="stat-cards-grid-modern">
    <div class="stat-card-modern stat-blue">
        <div class="stat-icon-modern blue">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?= $total_subjects ?></div>
            <div class="stat-label">Total Mata Pelajaran</div>
            <div class="stat-description">Semester ini</div>
        </div>
    </div>

    <div class="stat-card-modern stat-purple">
        <div class="stat-icon-modern purple">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?= $total_presence ?></div>
            <div class="stat-label">Total Kehadiran</div>
            <div class="stat-description">Hari ini: <?= $today ?></div>
        </div>
    </div>

    <div class="stat-card-modern stat-green">
        <div class="stat-icon-modern green">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?= $total_materials ?></div>
            <div class="stat-label">Materi Tersedia</div>
            <div class="stat-description">Semua kelas</div>
        </div>
    </div>

    <div class="stat-card-modern stat-orange">
        <div class="stat-icon-modern orange">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?= $total_assignments ?></div>
            <div class="stat-label">Total Tugas</div>
            <div class="stat-description">Pending & selesai</div>
        </div>
    </div>
</div>

<!-- Action Cards Section -->
<div class="action-cards-grid mb-4">
    <div class="action-card">
        <div class="action-card-content">
            <h5>Akses Kelas</h5>
            <p>Masuk ke kelas dan mulai pembelajaran Anda</p>
        </div>
        <a href="my_classes.php" class="btn-modern btn-outline-modern">Akses Kelas</a>
    </div>
    <div class="action-card">
        <div class="action-card-content">
            <h5>Lihat Materi</h5>
            <p>Download dan pelajari materi pembelajaran</p>
        </div>
        <a href="my_classes.php" class="btn-modern btn-outline-modern">Lihat Materi</a>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row">
    <!-- Left Column: Jadwal Hari Ini -->
    <div class="col-lg-8 mb-4">
        <div class="card-modern">
            <div class="card-modern-header">
                <h5>Jadwal Hari Ini</h5>
                <a href="my_classes.php" class="view-all">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
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

    <!-- Right Column: Aktivitas & Pengumuman -->
    <div class="col-lg-4 mb-4">
        <!-- Aktivitas Terbaru -->
        <div class="card-modern mb-4">
            <div class="card-modern-header">
                <h5>Aktivitas Terbaru</h5>
                <a href="#" class="view-all">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="card-modern-body">
                <div class="activity-list">
                    <?php 
                    // Get recent activities - combine attendance and submissions
                    $activity_query = "
                        (SELECT 'presensi' as type, al.submitted_at as date, CONCAT('Presensi tersubmit - ', DATE_FORMAT(al.submitted_at, '%d/%m/%Y')) as title, al.submitted_at as sort_date
                        FROM attendance_logs al 
                        WHERE al.student_id = ?)
                        UNION ALL
                        (SELECT 'tugas' as type, s.submitted_at as date, CONCAT('Tugas dikumpulkan - ', a.title) as title, s.submitted_at as sort_date
                        FROM submissions s
                        JOIN assignments a ON s.assignment_id = a.id
                        WHERE s.student_id = ?)
                        ORDER BY sort_date DESC 
                        LIMIT 3
                    ";
                    $stmt = $conn->prepare($activity_query);
                    $stmt->execute([$student_id, $student_id]);
                    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($activities) > 0):
                        foreach ($activities as $activity):
                            $icon_class = $activity['type'] == 'presensi' ? 'fa-check' : 'fa-file-alt';
                            $icon_bg = $activity['type'] == 'presensi' ? '#EFF6FF' : '#ECFDF5';
                            $icon_color = $activity['type'] == 'presensi' ? '#3B82F6' : '#10B981';
                    ?>
                    <div class="activity-item">
                        <div class="activity-icon" style="background: <?= $icon_bg ?>; color: <?= $icon_color ?>;">
                            <i class="fas <?= $icon_class ?>"></i>
                        </div>
                        <div class="activity-content">
                            <h6><?= htmlspecialchars($activity['title']) ?></h6>
                            <p><?= date('d M Y, H:i', strtotime($activity['date'])) ?></p>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <p class="text-muted mb-0">Belum ada aktivitas terbaru.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="card-modern">
            <div class="card-modern-header">
                <h5>Pengumuman</h5>
                <a href="#" class="view-all">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="card-modern-body">
                <?php if (count($announcements) > 0): ?>
                    <?php foreach ($announcements as $info): ?>
                    <div class="announcement-item">
                        <div class="announcement-icon" style="background: #FEF3C7; color: #F59E0B;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="announcement-content">
                            <h6><?= htmlspecialchars($info['title']) ?></h6>
                            <p><?= htmlspecialchars(substr($info['content'], 0, 60)) ?>...</p>
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
