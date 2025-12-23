<?php
require_once '../../config/database.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
$schedule = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM schedules WHERE id = ?");
    $stmt->execute([$id]);
    $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch Data for Dropdowns
$classes = $conn->query("SELECT * FROM classes ORDER BY class_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$teachers = $conn->query("SELECT * FROM users WHERE role = 'guru' ORDER BY full_name ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE schedules SET class_id=?, subject_id=?, teacher_id=?, day_of_week=?, start_time=?, end_time=? WHERE id=?");
        $stmt->execute([$class_id, $subject_id, $teacher_id, $day_of_week, $start_time, $end_time, $id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO schedules (class_id, subject_id, teacher_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$class_id, $subject_id, $teacher_id, $day_of_week, $start_time, $end_time]);
    }
    echo "<script>window.location='schedules.php';</script>";
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-custom p-4">
                <h4 class="mb-4"><?= $id ? 'Edit' : 'Buat' ?> Jadwal</h4>
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <select name="class_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach ($classes as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($schedule['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hari</label>
                            <select name="day_of_week" class="form-select" required>
                                <option value="Senin" <?= ($schedule['day_of_week'] ?? '') == 'Senin' ? 'selected' : '' ?>>Senin</option>
                                <option value="Selasa" <?= ($schedule['day_of_week'] ?? '') == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                                <option value="Rabu" <?= ($schedule['day_of_week'] ?? '') == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                                <option value="Kamis" <?= ($schedule['day_of_week'] ?? '') == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                                <option value="Jumat" <?= ($schedule['day_of_week'] ?? '') == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                                <option value="Sabtu" <?= ($schedule['day_of_week'] ?? '') == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= ($schedule['subject_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['subject_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Guru Pengampu</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= ($schedule['teacher_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= $t['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control" value="<?= $schedule['start_time'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control" value="<?= $schedule['end_time'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="schedules.php" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-navy">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

