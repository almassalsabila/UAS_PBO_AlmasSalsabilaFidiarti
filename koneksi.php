<?php
/**
 * koneksi.php
 * 
 * File khusus untuk menangani koneksi ke database MySQL menggunakan PDO.
 * Mengikuti best practices keamanan PHP dengan try-catch untuk error handling.
 */

$host   = 'localhost';
$dbname = 'db_uas_pbo_trpl1a_almassalsabilafidiarti';
$user   = 'root';
$pass   = '';

try {
    // Membuat instance PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    // Set PDO attributes untuk melempar exception saat terjadi error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode menjadi associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Tampilkan pesan error yang rapi jika koneksi gagal
    die("<div style='padding:2rem;text-align:center;color:#DC9B9B;font-family:sans-serif;'>
            <h2>⚠ Koneksi Database Gagal</h2>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
?>
