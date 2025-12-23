<?php
require_once '../../config/database.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
$user = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch Classes for Dropdown
$classes = $conn->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $class_id = $_POST['class_id'] ?: null;
    $password = $_POST['password'];
    
    if ($id) {
        // Update
        $query = "UPDATE users SET username = ?, full_name = ?, role = ?, class_id = ? WHERE id = ?";
        $params = [$username, $full_name, $role, $class_id, $id];
        
        if (!empty($password)) {
            $query = "UPDATE users SET username = ?, full_name = ?, role = ?, class_id = ?, password = ? WHERE id = ?";
            $params = [$username, $full_name, $role, $class_id, password_hash($password, PASSWORD_DEFAULT), $id];
        }
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, role, class_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $full_name, $role, $class_id]);
    }
    
    echo "<script>window.location='users.php';</script>";
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-custom p-4">
                <h4 class="mb-4"><?= $id ? 'Edit' : 'Tambah' ?> Pengguna</h4>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="<?= $user['full_name'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $user['username'] ?? '' ?>" required>
                    </div>
                    <?php if (!$id): ?>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" id="roleSelect" required>
                            <option value="siswa" <?= ($user['role'] ?? '') == 'siswa' ? 'selected' : '' ?>>Siswa</option>
                            <option value="guru" <?= ($user['role'] ?? '') == 'guru' ? 'selected' : '' ?>>Guru</option>
                            <option value="akademik" <?= ($user['role'] ?? '') == 'akademik' ? 'selected' : '' ?>>Akademik</option>
                            <option value="kepala_sekolah" <?= ($user['role'] ?? '') == 'kepala_sekolah' ? 'selected' : '' ?>>Kepala Sekolah</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="classSection">
                        <label class="form-label">Kelas (Khusus Siswa)</label>
                        <select name="class_id" class="form-select">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= ($user['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>>
                                    <?= $class['class_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="users.php" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-navy">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const classSection = document.getElementById('classSection');
    if (this.value === 'siswa') {
        classSection.style.display = 'block';
    } else {
        classSection.style.display = 'none';
    }
});
// Trigger on load
document.getElementById('roleSelect').dispatchEvent(new Event('change'));
</script>

<?php include 'includes/footer.php'; ?>

