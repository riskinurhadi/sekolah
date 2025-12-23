<?php
require_once '../../config/database.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
$subject = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_name = $_POST['subject_name'];
    $description = $_POST['description'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE subjects SET subject_name = ?, description = ? WHERE id = ?");
        $stmt->execute([$subject_name, $description, $id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO subjects (subject_name, description) VALUES (?, ?)");
        $stmt->execute([$subject_name, $description]);
    }
    echo "<script>window.location='subjects.php';</script>";
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom p-4">
                <h4 class="mb-4"><?= $id ? 'Edit' : 'Tambah' ?> Mata Pelajaran</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" name="subject_name" class="form-control" value="<?= $subject['subject_name'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"><?= $subject['description'] ?? '' ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="subjects.php" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-navy">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

