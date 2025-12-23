<?php
require_once '../../config/database.php';
include 'includes/header.php';

$teacher_id = $_SESSION['user_id'];

// Get Today's Schedule
$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$today = $days[date('w')];

$query = "
    SELECT s.*, c.class_name, sub.subject_name 
    FROM schedules s 
    JOIN classes c ON s.class_id = c.id 
    JOIN subjects sub ON s.subject_id = sub.id 
    WHERE s.teacher_id = ? AND s.day_of_week = ?
    ORDER BY s.start_time ASC
";
$stmt = $conn->prepare($query);
$stmt->execute([$teacher_id, $today]);
$today_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Dashboard Guru</h3>
    
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card card-custom p-4">
                <h5>Jadwal Mengajar Hari Ini (<?= $today ?>)</h5>
                <?php if (count($today_schedules) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($today_schedules as $schedule): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?></td>
                                    <td><?= htmlspecialchars($schedule['class_name']) ?></td>
                                    <td><?= htmlspecialchars($schedule['subject_name']) ?></td>
                                    <td>
                                        <a href="class_detail.php?schedule_id=<?= $schedule['id'] ?>" class="btn btn-sm btn-navy">
                                            Masuk Kelas <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Tidak ada jadwal mengajar hari ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

