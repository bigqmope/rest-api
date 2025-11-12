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
    public $conn;

    public function __construct() {
        $this->type = getenv('DB_TYPE') ?: 'mysql'; // vercel pakai pgsql
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->port = getenv('DB_PORT') ?: '3306';
        $this->db_name = getenv('DB_NAME') ?: 'kampus_db';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->sslmode = getenv('DB_SSLMODE') ?: ''; // vercel pakai require
        // getenv() hanya akan mengambil nilai dari environment variable sistem, bukan dari file .env apa pun.
        // getenv() hanya dipakai untuk production server
    }

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "{$this->type}:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode={$this->sslmode}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Jika database belum ada, buat dulu
            if (strpos($e->getMessage(), 'Unknown database') !== false) {
                $tempConn = new PDO("mysql:host={$this->host}", $this->username, $this->password);
                $tempConn->exec("CREATE DATABASE IF NOT EXISTS {$this->db_name}");
                $tempConn = null;

                // Reconnect ke database yang baru dibuat
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username,
                    $this->password
                );
            } else {
                die(json_encode(["error" => "Koneksi gagal: " . $e->getMessage()]));
            }
        }

        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTableIfNotExists();
        return $this->conn;
    }

    private function createTableIfNotExists()
    {
        if ($this->type === 'pgsql') {
            $sql = "
            CREATE TABLE IF NOT EXISTS mahasiswa (
                id SERIAL PRIMARY KEY,                  -- AUTO_INCREMENT versi PostgreSQL
                nama VARCHAR(100) NOT NULL,
                jurusan VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
            ";
        } else {
            $sql = "
            CREATE TABLE IF NOT EXISTS mahasiswa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama VARCHAR(100) NOT NULL,
                jurusan VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
        }

        $this->conn->exec($sql);
    }
}
