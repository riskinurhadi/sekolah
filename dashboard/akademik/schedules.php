<?php
require_once '../../config/database.php';
include 'includes/header.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM schedules WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='schedules.php';</script>";
}

$query = "
    SELECT s.*, c.class_name, sub.subject_name, u.full_name as teacher_name 
    FROM schedules s 
    JOIN classes c ON s.class_id = c.id 
    JOIN subjects sub ON s.subject_id = sub.id 
    JOIN users u ON s.teacher_id = u.id 
    ORDER BY s.day_of_week, s.start_time ASC
";
$schedules = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Jadwal Pelajaran</h3>
        <a href="schedule_form.php" class="btn btn-navy">
            <i class="fas fa-plus"></i> Buat Jadwal
        </a>
    </div>

    <div class="card card-custom p-4">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?= $schedule['day_of_week'] ?></td>
                    <td><?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?></td>
                    <td><?= htmlspecialchars($schedule['class_name']) ?></td>
                    <td><?= htmlspecialchars($schedule['subject_name']) ?></td>
                    <td><?= htmlspecialchars($schedule['teacher_name']) ?></td>
                    <td>
                        <a href="schedule_form.php?id=<?= $schedule['id'] ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                        <a href="schedules.php?delete=<?= $schedule['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

