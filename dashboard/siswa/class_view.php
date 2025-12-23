<?php
require_once '../../config/database.php';
include 'includes/header.php';

$schedule_id = $_GET['schedule_id'] ?? null;
if (!$schedule_id) {
    header("Location: my_classes.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// Get Schedule Details
$stmt = $conn->prepare("
    SELECT s.*, sub.subject_name, u.full_name as teacher_name 
    FROM schedules s 
    JOIN subjects sub ON s.subject_id = sub.id 
    JOIN users u ON s.teacher_id = u.id 
    WHERE s.id = ?
");
$stmt->execute([$schedule_id]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$schedule) {
    echo "Jadwal tidak ditemukan.";
    exit;
}

// Handle Attendance Submission
if (isset($_POST['submit_attendance'])) {
    $input_code = trim($_POST['attendance_code']);
    
    // Find active session with this code for this schedule
    $find_session = $conn->prepare("SELECT id FROM attendance_sessions WHERE schedule_id = ? AND unique_code = ? AND is_open = 1 AND session_date = CURDATE()");
    $find_session->execute([$schedule_id, $input_code]);
    $session = $find_session->fetch(PDO::FETCH_ASSOC);
    
    if ($session) {
        // Check if already submitted
        $check_log = $conn->prepare("SELECT id FROM attendance_logs WHERE session_id = ? AND student_id = ?");
        $check_log->execute([$session['id'], $student_id]);
        
        if ($check_log->rowCount() == 0) {
            $insert = $conn->prepare("INSERT INTO attendance_logs (session_id, student_id, status) VALUES (?, ?, 'Hadir')");
            $insert->execute([$session['id'], $student_id]);
            $success_msg = "Presensi berhasil!";
        } else {
            $error_msg = "Anda sudah melakukan presensi.";
        }
    } else {
        $error_msg = "Kode presensi salah atau sesi tidak aktif.";
    }
}

// Fetch Materials
$materials = $conn->prepare("SELECT * FROM materials WHERE schedule_id = ? ORDER BY created_at DESC");
$materials->execute([$schedule_id]);
$materials = $materials->fetchAll(PDO::FETCH_ASSOC);

// Check if there is an active attendance session for today that the student hasn't joined
$active_session = $conn->prepare("
    SELECT id FROM attendance_sessions 
    WHERE schedule_id = ? AND is_open = 1 AND session_date = CURDATE() 
    AND id NOT IN (SELECT session_id FROM attendance_logs WHERE student_id = ?)
");
$active_session->execute([$schedule_id, $student_id]);
$needs_attendance = $active_session->rowCount() > 0;
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0"><?= htmlspecialchars($schedule['subject_name']) ?></h3>
            <p class="text-muted mb-0">Guru: <?= htmlspecialchars($schedule['teacher_name']) ?></p>
        </div>
        <a href="my_classes.php" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-custom mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Materi Pelajaran</h5>
                </div>
                <div class="card-body">
                    <?php if (count($materials) > 0): ?>
                        <div class="list-group">
                            <?php foreach ($materials as $material): ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($material['title']) ?></h6>
                                    <small><?= date('d M Y', strtotime($material['created_at'])) ?></small>
                                </div>
                                <p class="mb-1 small text-muted"><?= htmlspecialchars($material['description']) ?></p>
                                <a href="../../uploads/materials/<?= $material['file_path'] ?>" class="btn btn-sm btn-outline-primary mt-2" download>Download</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada materi yang diupload.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Presensi Harian</h5>
                </div>
                <div class="card-body">
                    <?php if ($needs_attendance): ?>
                        <p>Silahkan masukkan kode presensi yang diberikan oleh guru.</p>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" name="attendance_code" class="form-control" placeholder="Masukkan Kode" required>
                            </div>
                            <button type="submit" name="submit_attendance" class="btn btn-navy w-100">Submit Presensi</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-check-circle me-2"></i> Anda sudah presensi atau tidak ada sesi aktif.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card card-custom">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tugas & Ujian</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Fitur tugas dan ujian akan segera hadir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

