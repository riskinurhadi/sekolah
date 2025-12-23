<?php
require_once 'config/database.php';

$message = '';

// Check if users table is empty
$stmt = $conn->query("SELECT COUNT(*) FROM users");
$userCount = $stmt->fetchColumn();

if ($userCount > 0) {
    $disabled = true;
    $message = '<div class="alert alert-warning">Sistem sudah memiliki pengguna. Silahkan <a href="index.php">Login disini</a>. Untuk keamanan, file ini sebaiknya dihapus jika tidak digunakan.</div>';
} else {
    $disabled = false;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $full_name = trim($_POST['full_name']);
        
        if ($username && $password && $full_name) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'developer';
            
            try {
                $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $hashed_password, $full_name, $role]);
                
                $disabled = true;
                $message = '<div class="alert alert-success">User Developer berhasil dibuat! Silahkan <a href="index.php">Login disini</a>.</div>';
            } catch (PDOException $e) {
                $message = '<div class="alert alert-danger">Gagal membuat user: ' . $e->getMessage() . '</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Semua field harus diisi!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card card-custom p-4" style="width: 100%; max-width: 500px;">
        <div class="text-center mb-4">
            <h4 class="text-navy fw-bold">Setup Administrator</h4>
            <p class="text-muted">Buat akun Developer pertama kali</p>
        </div>

        <?= $message ?>

        <?php if (!$disabled): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-navy w-100">Buat Akun Developer</button>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

