<?php
require_once '../../config/database.php';
include 'includes/header.php';

$teacher_id = $_SESSION['user_id'];

$query = "
    SELECT s.*, c.class_name, sub.subject_name 
    FROM schedules s 
    JOIN classes c ON s.class_id = c.id 
    JOIN subjects sub ON s.subject_id = sub.id 
    WHERE s.teacher_id = ? 
    ORDER BY FIELD(s.day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), s.start_time ASC
";
$stmt = $conn->prepare($query);
$stmt->execute([$teacher_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Semua Jadwal Mengajar</h3>
    
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= $schedule['day_of_week'] ?></span></td>
                        <td><?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?></td>
                        <td><?= htmlspecialchars($schedule['class_name']) ?></td>
                        <td><?= htmlspecialchars($schedule['subject_name']) ?></td>
                        <td>
                            <a href="class_detail.php?schedule_id=<?= $schedule['id'] ?>" class="btn btn-sm btn-navy">
                                Kelola <i class="fas fa-cog ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

