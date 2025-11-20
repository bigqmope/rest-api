<?php
class Database
{
    private $type;
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $sslmode;
    // Tambahkan properti untuk sertifikat SSL
    private $sslca; 
    public $conn;

    public function __construct() {
        $this->type = getenv('DB_TYPE') ?: 'mysql'; // vercel pakai pgsql
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->port = getenv('DB_PORT') ?: '3306';
        $this->db_name = getenv('DB_NAME') ?: 'kampus_db';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->sslmode = getenv('DB_SSLMODE') ?: ''; // vercel pakai require
        // Ambil variabel DB_SSLCA yang kita set di Vercel
        $this->sslca = getenv('DB_SSLCA') ?: '';
    }

    public function connect()
{
    $this->conn = null;
    $dsn = "";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]; // Hapus opsi SSL dari sini

    try {
        // Tentukan DSN
        if ($this->type === 'pgsql') {
            // UNTUK POSTGRESQL/SUPABASE: Gunakan sslmode di DSN
            $dsn = "{$this->type}:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode={$this->sslmode}";
            
            // Perhatian: Tidak ada opsi PDO::ATTR_SSL_MODE di sini!
            // Kita mengandalkan string DSN yang Anda set: sslmode=require
            
        } else {
            // Untuk MySQL
            $dsn = "{$this->type}:host={$this->host};port={$this->port};dbname={$this->db_name}";
        }

        // 1. Buat Koneksi PDO
        $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        // ... (Logika pembuatan database/error handling lainnya)
        // ... (Jika koneksi gagal, kode akan terhenti di sini)
        die(json_encode(["error" => "Koneksi gagal: " . $e->getMessage()]));
    }

    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->createTableIfNotExists();
    return $this->conn;
}