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
        $options = [];

        try {
            // 1. Tentukan DSN dan OPSI BERDASARKAN TIPE DATABASE
            if ($this->type === 'pgsql') {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
                
                // Tambahkan OPSI SSL untuk PostgreSQL (Supabase)
                if ($this->sslmode === 'require') {
                    // Set SSL mode ke PDO::SSL_REQUIRED
                    $options[PDO::ATTR_SSL_MODE] = PDO::SSL_REQUIRED;
                    
                    // Jika DB_SSLCA disetel, tambahkan lokasi sertifikat (untuk Vercel)
                    if (!empty($this->sslca)) {
                        $options[PDO::PGSQL_ATTR_SSL_CA] = $this->sslca;
                    }
                }
            } else {
                // Untuk MySQL
                $dsn = "{$this->type}:host={$this->host};port={$this->port};dbname={$this->db_name}";
            }

            // 2. Buat Koneksi PDO
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            
            // Logika Anda untuk MySQL (Membuat database jika belum ada)
            if ($this->type === 'mysql' && strpos($e->getMessage(), 'Unknown database') !== false) {
                // ... (Logika pembuatan database tetap di sini)
                // ... (Tidak ditampilkan di sini untuk ringkasan)
                
            } else {
                 // Error koneksi fatal (SSL/Auth/Pooler)
                 // Ganti die() dengan error response JSON yang lebih baik
                header('Content-Type: application/json');
                http_response_code(500);
                die(json_encode(["error" => "Koneksi gagal: " . $e->getMessage()]));
            }
        }

        // Set Attribute lagi di luar blok try-catch
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Pastikan Anda memanggil createTableIfNotExists di sini atau di tempat yang benar
        // $this->createTableIfNotExists(); 
        
        return $this->conn;
    }

    // Metode createTableIfNotExists tetap di sini
    private function createTableIfNotExists() {
        // ... (Kode createTableIfNotExists Anda yang asli)
        // ...
    }
}