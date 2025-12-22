<?php
/**
 * File Konfigurasi Database
 *
 * File ini berisi pengaturan untuk koneksi ke database MySQL.
 * Ganti nilai placeholder dengan kredensial database Anda yang sebenarnya.
 */

// Pengaturan Database
define('DB_HOST', 'localhost');      // Host database Anda (biasanya 'localhost')
define('DB_NAME', 'sekolah'); // Nama database Anda
define('DB_USER', 'sekolah');  // Username database Anda
define('DB_PASS', 'Aloevera21.');    // Password database Anda
define('DB_CHARSET', 'utf8mb4');         // Set karakter, utf8mb4 direkomendasikan

// Opsi untuk koneksi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// DSN (Data Source Name)
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

try {
    // Buat instance PDO untuk koneksi database
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    // Sebaiknya matikan pesan error detail di lingkungan produksi
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

/**
 * Untuk menggunakan koneksi ini di file lain, cukup sertakan file ini:
 *
 * require_once 'config.php';
 *
 * Dan kemudian Anda dapat menggunakan variabel $pdo untuk berinteraksi dengan database.
 * Contoh:
 * $stmt = $pdo->query('SELECT * FROM users');
 * $users = $stmt->fetchAll();
 * print_r($users);
 */
?>
