<?php
session_start();

// Jika pengguna tidak login, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pengguna sudah login, tampilkan halaman selamat datang
$full_name = htmlspecialchars($_SESSION['full_name']);
$role = htmlspecialchars($_SESSION['role']);

// Tentukan path dashboard berdasarkan peran
$role_dir = str_replace(' ', '_', strtolower($role));
$dashboard_path = $role_dir . '/index.html';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center; }
        .container { background: #fff; padding: 40px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 10px; }
        p { margin-bottom: 20px; color: #555; }
        .btn { display: inline-block; text-decoration: none; background: #0275d8; color: white; padding: 10px 20px; border-radius: 5px; margin: 5px; }
        .btn-danger { background: #d9534f; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang, <?php echo $full_name; ?>!</h1>
        <p>Anda login sebagai: <strong><?php echo $role; ?></strong></p>
        <a href="<?php echo $dashboard_path; ?>" class="btn">Masuk ke Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
