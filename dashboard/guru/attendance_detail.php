<?php
require_once '../../config/database.php';
include 'includes/header.php';

$session_id = $_GET['session_id'] ?? null;
if (!$session_id) {
    header("Location: index.php");
    exit;
}

// Get Session Info
$stmt = $conn->prepare("
    SELECT asess.*, s.class_id, c.class_name, sub.subject_name 
    FROM attendance_sessions asess
    JOIN schedules s ON asess.schedule_id = s.id
    JOIN classes c ON s.class_id = c.id
    JOIN subjects sub ON s.subject_id = sub.id
    WHERE asess.id = ?
");
$stmt->execute([$session_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
    echo "Sesi tidak ditemukan.";
    exit;
}

// Get All Students in Class
$students = $conn->prepare("SELECT id, full_name FROM users WHERE class_id = ? AND role = 'siswa' ORDER BY full_name ASC");
$students->execute([$session['class_id']]);
$students_list = $students->fetchAll(PDO::FETCH_ASSOC);

// Get Attendance Logs
$logs = $conn->prepare("SELECT * FROM attendance_logs WHERE session_id = ?");
$logs->execute([$session_id]);
$attendance_data = $logs->fetchAll(PDO::FETCH_KEY_PAIR); // student_id => status

// Handle Manual Update (if needed) - For now just view
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Rekap Presensi</h3>
        <a href="class_detail.php?schedule_id=<?= $session['schedule_id'] ?>" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card card-custom p-4">
        <div class="mb-4">
            <h5><?= htmlspecialchars($session['class_name']) ?> - <?= htmlspecialchars($session['subject_name']) ?></h5>
            <p>Tanggal: <?= date('d M Y', strtotime($session['session_date'])) ?> | Kode: <strong><?= $session['unique_code'] ?></strong></p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach ($students_list as $student): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($student['full_name']) ?></td>
                    <td>
                        <?php 
                        $status = $attendance_data[$student['id']] ?? 'Belum Presensi';
                        $badge = match($status) {
                            'Hadir' => 'success',
                            'Izin' => 'warning',
                            'Sakit' => 'info',
                            'Alpha' => 'danger',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

