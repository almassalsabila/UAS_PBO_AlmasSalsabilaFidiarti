<?php
/**
 * koneksi.php
 * 
 * Class Database dengan pola desain Singleton (Singleton Design Pattern).
 * Memastikan bahwa hanya ada SATU instance koneksi PDO yang dibuat
 * selama siklus hidup aplikasi berjalan, guna menghemat resource memori.
 */
class Database {
    // Kredensial database (Enkapsulasi - Private)
    private $host   = 'localhost';
    private $dbname = 'db_uas_pbo_trpl1a_almassalsabilafidiarti';
    private $user   = 'root';
    private $pass   = '';

    // Properti statis untuk menyimpan instance tunggal (Singleton)
    private static $instance = null;

    // Properti untuk menyimpan koneksi PDO
    private $pdo;

    /**
     * Constructor dibuat private agar class tidak dapat diinisialisasi
     * secara langsung menggunakan keyword 'new' dari luar class.
     */
    private function __construct() {
        try {
            // Membuat koneksi PDO
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);

            // Set mode error ke Exception agar mudah di-catch
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode ke array asosiatif
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Tampilkan error jika koneksi gagal
            die("<div style='padding:2rem;text-align:center;color:#DC9B9B;font-family:sans-serif;'>
                    <h2>⚠ Koneksi Database Gagal</h2>
                    <p>" . htmlspecialchars($e->getMessage()) . "</p>
                 </div>");
        }
    }

    /**
     * Mencegah cloning object (Singleton pattern strictness)
     */
    private function __clone() {}

    /**
     * Mencegah unserialization object (Singleton pattern strictness)
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * Metode utama untuk mengambil instance Singleton dari Database.
     * Jika instance belum ada, buat baru. Jika sudah ada, kembalikan yang lama.
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Metode untuk mendapatkan objek koneksi PDO
     * 
     * @return PDO
     */
    public function getConnection() {
        return $this->pdo;
    }
}
?>
