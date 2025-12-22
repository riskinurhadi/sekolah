<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

// Ambil data roles dari database untuk dropdown
try {
    $stmt = $pdo->query("SELECT id, name FROM roles ORDER BY name");
    $roles = $stmt->fetchAll();
} catch (\PDOException $e) {
    // Jika gagal, buat array kosong dan set pesan error
    $roles = [];
    $error = "Gagal memuat data peran: " . $e->getMessage();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    // Validasi sederhana
    if (empty($username) || empty($full_name) || empty($password) || empty($role_id)) {
        $error = 'Semua field wajib diisi.';
    } else {
        // Cek apakah username sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username sudah digunakan. Silakan pilih yang lain.';
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan pengguna baru ke database
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, full_name, password, role_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $full_name, $hashed_password, $role_id]);
                $success = 'Registrasi berhasil! Silakan <a href="login.php">login</a>.';
            } catch (\PDOException $e) {
                $error = "Registrasi gagal: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background: #5cb85c; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .error { color: #d9534f; text-align: center; margin-bottom: 10px; }
        .success { color: #5cb85c; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Registrasi Pengguna Baru</h2>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php else: ?>
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" name="full_name" id="full_name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label for="role_id">Peran</label>
                    <select name="role_id" id="role_id" required>
                        <option value="">-- Pilih Peran --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo htmlspecialchars($role['id']); ?>">
                                <?php echo htmlspecialchars($role['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Daftar</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
