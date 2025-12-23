<?php
require_once '../../config/database.php';
include 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='users.php';</script>";
}

// Fetch Users
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? '';

$query = "SELECT u.*, c.class_name FROM users u LEFT JOIN classes c ON u.class_id = c.id WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (u.username LIKE ? OR u.full_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($role_filter) {
    $query .= " AND u.role = ?";
    $params[] = $role_filter;
}

$query .= " ORDER BY u.id DESC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Data Pengguna</h3>
        <a href="user_form.php" class="btn btn-navy">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card card-custom p-4">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau username..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="siswa" <?= $role_filter == 'siswa' ? 'selected' : '' ?>>Siswa</option>
                    <option value="guru" <?= $role_filter == 'guru' ? 'selected' : '' ?>>Guru</option>
                    <option value="akademik" <?= $role_filter == 'akademik' ? 'selected' : '' ?>>Akademik</option>
                    <option value="kepala_sekolah" <?= $role_filter == 'kepala_sekolah' ? 'selected' : '' ?>>Kepala Sekolah</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Kelas (Siswa)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><span class="badge bg-primary"><?= ucfirst($user['role']) ?></span></td>
                        <td><?= $user['class_name'] ?? '-' ?></td>
                        <td>
                            <a href="user_form.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning text-white">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="users.php?delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

