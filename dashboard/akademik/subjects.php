<?php
require_once '../../config/database.php';
include 'includes/header.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='subjects.php';</script>";
}

$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Data Mata Pelajaran</h3>
        <a href="subject_form.php" class="btn btn-navy">
            <i class="fas fa-plus"></i> Tambah Mapel
        </a>
    </div>

    <div class="card card-custom p-4">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?= $subject['id'] ?></td>
                    <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                    <td><?= htmlspecialchars($subject['description']) ?></td>
                    <td>
                        <a href="subject_form.php?id=<?= $subject['id'] ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                        <a href="subjects.php?delete=<?= $subject['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

