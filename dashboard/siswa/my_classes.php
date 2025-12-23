<?php
require_once '../../config/database.php';
include 'includes/header.php';

$class_id = $_SESSION['class_id'];

$query = "
    SELECT DISTINCT s.id as schedule_id, sub.subject_name, u.full_name as teacher_name, s.day_of_week, s.start_time
    FROM schedules s 
    JOIN subjects sub ON s.subject_id = sub.id 
    JOIN users u ON s.teacher_id = u.id 
    WHERE s.class_id = ? 
    ORDER BY sub.subject_name ASC
";
$stmt = $conn->prepare($query);
$stmt->execute([$class_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Kelas Saya</h3>
    
    <div class="row">
        <?php foreach ($schedules as $schedule): ?>
        <div class="col-md-4 mb-4">
            <div class="card card-custom h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($schedule['subject_name']) ?></h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-chalkboard-teacher me-2"></i> <?= htmlspecialchars($schedule['teacher_name']) ?><br>
                        <i class="fas fa-clock me-2"></i> <?= $schedule['day_of_week'] ?>, <?= date('H:i', strtotime($schedule['start_time'])) ?>
                    </p>
                    <a href="class_view.php?schedule_id=<?= $schedule['schedule_id'] ?>" class="btn btn-navy w-100">Lihat Kelas</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

