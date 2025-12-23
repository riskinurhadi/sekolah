<?php
require_once '../../config/database.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
$class = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$id]);
    $class = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE classes SET class_name = ? WHERE id = ?");
        $stmt->execute([$class_name, $id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO classes (class_name) VALUES (?)");
        $stmt->execute([$class_name]);
    }
    echo "<script>window.location='classes.php';</script>";
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom p-4">
                <h4 class="mb-4"><?= $id ? 'Edit' : 'Tambah' ?> Kelas</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="class_name" class="form-control" value="<?= $class['class_name'] ?? '' ?>" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="classes.php" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-navy">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

