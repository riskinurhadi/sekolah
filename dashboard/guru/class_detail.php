<?php
require_once '../../config/database.php';
include 'includes/header.php';

$schedule_id = $_GET['schedule_id'] ?? null;
if (!$schedule_id) {
    header("Location: schedules.php");
    exit;
}

// Get Schedule Details
$stmt = $conn->prepare("
    SELECT s.*, c.class_name, sub.subject_name 
    FROM schedules s 
    JOIN classes c ON s.class_id = c.id 
    JOIN subjects sub ON s.subject_id = sub.id 
    WHERE s.id = ? AND s.teacher_id = ?
");
$stmt->execute([$schedule_id, $_SESSION['user_id']]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$schedule) {
    echo "Jadwal tidak ditemukan atau Anda tidak memiliki akses.";
    exit;
}

// Handle Create Attendance Session
if (isset($_POST['create_session'])) {
    $code = strtoupper(substr(md5(uniqid()), 0, 6));
    $date = date('Y-m-d');
    
    // Check if session exists for today
    $check = $conn->prepare("SELECT id FROM attendance_sessions WHERE schedule_id = ? AND session_date = ?");
    $check->execute([$schedule_id, $date]);
    if ($check->rowCount() == 0) {
        $stmt = $conn->prepare("INSERT INTO attendance_sessions (schedule_id, session_date, unique_code) VALUES (?, ?, ?)");
        $stmt->execute([$schedule_id, $date, $code]);
        $success_msg = "Sesi presensi dibuka! Kode: <strong>$code</strong>";
    } else {
        $error_msg = "Sesi presensi hari ini sudah ada.";
    }
}

// Handle Material Upload
if (isset($_POST['upload_material'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    
    // File Upload
    $target_dir = "../../uploads/materials/";
    $file_name = time() . '_' . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO materials (schedule_id, title, description, file_path) VALUES (?, ?, ?, ?)");
        $stmt->execute([$schedule_id, $title, $desc, $file_name]);
        $success_msg = "Materi berhasil diupload!";
    } else {
        $error_msg = "Gagal mengupload file.";
    }
}

// Fetch Data
$sessions = $conn->prepare("SELECT * FROM attendance_sessions WHERE schedule_id = ? ORDER BY session_date DESC");
$sessions->execute([$schedule_id]);
$sessions = $sessions->fetchAll(PDO::FETCH_ASSOC);

$materials = $conn->prepare("SELECT * FROM materials WHERE schedule_id = ? ORDER BY created_at DESC");
$materials->execute([$schedule_id]);
$materials = $materials->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0"><?= htmlspecialchars($schedule['subject_name']) ?></h3>
            <p class="text-muted mb-0"><?= htmlspecialchars($schedule['class_name']) ?> | <?= $schedule['day_of_week'] ?>, <?= date('H:i', strtotime($schedule['start_time'])) ?></p>
        </div>
        <a href="schedules.php" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="card card-custom">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="presensi-tab" data-bs-toggle="tab" data-bs-target="#presensi" type="button">Presensi</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button">Materi</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas" type="button">Tugas & Ujian</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                
                <!-- Tab Presensi -->
                <div class="tab-pane fade show active" id="presensi">
                    <div class="mb-4">
                        <form method="POST">
                            <button type="submit" name="create_session" class="btn btn-navy">
                                <i class="fas fa-qrcode me-2"></i> Buka Presensi Hari Ini
                            </button>
                        </form>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Presensi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $session): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($session['session_date'])) ?></td>
                                <td><span class="badge bg-success fs-6"><?= $session['unique_code'] ?></span></td>
                                <td><?= $session['is_open'] ? 'Dibuka' : 'Ditutup' ?></td>
                                <td>
                                    <a href="attendance_detail.php?session_id=<?= $session['id'] ?>" class="btn btn-sm btn-info text-white">Lihat Kehadiran</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tab Materi -->
                <div class="tab-pane fade" id="materi">
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6>Upload Materi Baru</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="title" class="form-control mb-2" placeholder="Judul Materi" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="file" class="form-control mb-2" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="upload_material" class="btn btn-navy w-100">Upload</button>
                                </div>
                                <div class="col-12">
                                    <textarea name="description" class="form-control" placeholder="Deskripsi (Opsional)" rows="2"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="list-group">
                        <?php foreach ($materials as $material): ?>
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= htmlspecialchars($material['title']) ?></h5>
                                <small><?= date('d M Y', strtotime($material['created_at'])) ?></small>
                            </div>
                            <p class="mb-1"><?= htmlspecialchars($material['description']) ?></p>
                            <a href="../../uploads/materials/<?= $material['file_path'] ?>" class="btn btn-sm btn-outline-primary" download>Download File</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tab Tugas (Placeholder) -->
                <div class="tab-pane fade" id="tugas">
                    <p class="text-muted">Fitur Tugas dan Ujian akan segera hadir.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

