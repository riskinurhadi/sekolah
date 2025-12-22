<?php
session_start();
require_once 'config.php';

$error = '';

// Jika pengguna sudah login, arahkan ke dashboard yang sesuai
if (isset($_SESSION['user_id'])) {
    $role_dir = str_replace(' ', '_', strtolower($_SESSION['role']));
    header("Location: " . $role_dir . "/index.html"); // Asumsi nama folder sama dengan nama role
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Username dan Password wajib diisi.';
    } else {
        try {
            // Ambil data user beserta rolenya
            $stmt = $pdo->prepare(
                "SELECT u.id, u.username, u.password, u.full_name, r.name as role_name 
                 FROM users u 
                 JOIN roles r ON u.role_id = r.id 
                 WHERE u.username = ?"
            );
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Verifikasi password
            if ($user && password_verify($password, $user['password'])) {
                // Jika berhasil, simpan data sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role_name'];

                // Arahkan ke dashboard yang sesuai
                $role_dir = str_replace(' ', '_', strtolower($user['role_name']));
                
                // Buat path yang benar. Contoh: 'Guru Mapel' -> 'guru_mapel'
                $dashboard_path = $role_dir . '/index.html'; 
                
                if (file_exists($dashboard_path)) {
                    header("Location: " . $dashboard_path);
                    exit;
                } else {
                    $error = "Direktori dashboard untuk peran Anda tidak ditemukan.";
                }

            } else {
                $error = 'Username atau Password salah.';
            }
        } catch (\PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background: #0275d8; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .error { color: #d9534f; text-align: center; margin-bottom: 10px; }
        .register-link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Login</h2>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <div class="register-link">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a>.</p>
        </div>
    </div>

</body>
</html>
