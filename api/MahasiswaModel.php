<?php
require_once __DIR__ . '/../core/Database.php';

class MahasiswaModel
{
    private $conn;
    private $table = "mahasiswa";

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        // $check = $this->check($id);
        // if ($check !== true) {
        //     return $check; // langsung return hasil dari check()
        // }

        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nama, jurusan) VALUES (?, ?)");
        $stmt->execute([$data['nama'], $data['jurusan']]);
        return ["message" => "Data mahasiswa berhasil ditambahkan"];
    }

    public function update($id, $data)
    {
        $check = $this->check($id);
        if ($check !== true) {
            return $check; // langsung return hasil dari check()
        }

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET nama = ?, jurusan = ? WHERE id = ?");
        $stmt->execute([$data['nama'], $data['jurusan'], $id]);
        return ["message" => "Data mahasiswa berhasil diperbarui"];
    }

    public function delete($id)
    {
        $check = $this->check($id);
        if ($check !== true) {
            return $check; // langsung return hasil dari check()
        }

        // Jika ada, baru hapus
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);

        return [
            "status" => true,
            "message" => "Data mahasiswa dengan ID {$id} berhasil dihapus"
        ];
    }

    private function check($id)
    {
        // Cek apakah data ada
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Data tidak ditemukan
            return [
                "status" => false,
                "message" => "Data mahasiswa dengan ID {$id} tidak ditemukan"
            ];
        }
    }
}
