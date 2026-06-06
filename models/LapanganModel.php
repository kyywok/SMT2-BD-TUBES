<?php
// models/LapanganModel.php
require_once 'config/database.php';

class LapanganModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // SELECT semua lapangan (ORDER BY harga_per_jam DESC -> contoh ORDER BY)
    public function getAllLapangan() {
        $query = "SELECT * FROM lapangan ORDER BY harga_per_jam DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT satu lapangan berdasarkan id
    public function getLapanganById($id) {
        $query = "SELECT * FROM lapangan WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // INSERT lapangan baru (DML - CREATE)
    public function createLapangan($data) {
        $query = "INSERT INTO lapangan (nama_lapangan, jenis_lapangan, harga_per_jam, fasilitas, status_lapangan)
                  VALUES (:nama_lapangan, :jenis_lapangan, :harga_per_jam, :fasilitas, :status_lapangan)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_lapangan', $data['nama_lapangan']);
        $stmt->bindParam(':jenis_lapangan', $data['jenis_lapangan']);
        $stmt->bindParam(':harga_per_jam', $data['harga_per_jam']);
        $stmt->bindParam(':fasilitas', $data['fasilitas']);
        $stmt->bindParam(':status_lapangan', $data['status_lapangan']);
        $stmt->bindParam(':gambar', $gambar_name);
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // UPDATE lapangan (DML - UPDATE)
    public function updateLapangan($id, $data, $gambar_name = null) {
    if ($gambar_name) {
        $query = "UPDATE lapangan SET 
                    nama_lapangan = :nama_lapangan,
                    jenis_lapangan = :jenis_lapangan,
                    harga_per_jam = :harga_per_jam,
                    fasilitas = :fasilitas,
                    status_lapangan = :status_lapangan,
                    gambar = :gambar
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':gambar', $gambar_name);
    } else {
        $query = "UPDATE lapangan SET 
                    nama_lapangan = :nama_lapangan,
                    jenis_lapangan = :jenis_lapangan,
                    harga_per_jam = :harga_per_jam,
                    fasilitas = :fasilitas,
                    status_lapangan = :status_lapangan
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_lapangan', $data['nama_lapangan']);
        $stmt->bindParam(':jenis_lapangan', $data['jenis_lapangan']);
        $stmt->bindParam(':harga_per_jam', $data['harga_per_jam']);
        $stmt->bindParam(':fasilitas', $data['fasilitas']);
        $stmt->bindParam(':status_lapangan', $data['status_lapangan']);
        return $stmt->execute();
    }

    // DELETE lapangan (DML - DELETE)
    public function deleteLapangan($id) {
        $query = "DELETE FROM lapangan WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>