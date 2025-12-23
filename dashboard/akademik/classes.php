<?php
require_once '../../config/database.php';
include 'includes/header.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='classes.php';</script>";
}

$classes = $conn->query("SELECT * FROM classes ORDER BY class_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Data Kelas</h3>
        <a href="class_form.php" class="btn btn-navy">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    <div class="card card-custom p-4">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?= $class['id'] ?></td>
                    <td><?= htmlspecialchars($class['class_name']) ?></td>
                    <td>
                        <a href="class_form.php?id=<?= $class['id'] ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                        <a href="classes.php?delete=<?= $class['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

