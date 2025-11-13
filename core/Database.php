<?php
class Database
{
    private $host = "localhost";
    private $port = "3306";
    private $db_name = "rest_api";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}",
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
        $sql = "
        CREATE TABLE IF NOT EXISTS mahasiswa (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            jurusan VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        $this->conn->exec($sql);
    }
}
