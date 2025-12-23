<?php
require_once '../../config/database.php';
include 'includes/header.php';

$class_id = $_SESSION['class_id'];
if (!$class_id) {
    echo "Anda belum masuk ke dalam kelas manapun. Hubungi akademik.";
    exit;
}

// Get Today's Schedule
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
?>

<div class="container-fluid">
    <h3 class="mb-4">Dashboard Siswa</h3>
    
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card card-custom p-4">
                <h5>Jadwal Hari Ini (<?= $today ?>)</h5>
                <?php if (count($today_schedules) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($today_schedules as $schedule): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?></td>
                                    <td><?= htmlspecialchars($schedule['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($schedule['teacher_name']) ?></td>
                                    <td>
                                        <a href="class_view.php?schedule_id=<?= $schedule['id'] ?>" class="btn btn-sm btn-navy">
                                            Masuk Kelas <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Tidak ada jadwal pelajaran hari ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

