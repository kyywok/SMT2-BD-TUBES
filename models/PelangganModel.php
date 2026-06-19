<?php
// models/PelangganModel.php
require_once 'config/database.php';

class PelangganModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Cari pelanggan berdasarkan email
    public function getPelangganByEmail($email) {
        $query = "SELECT * FROM pelanggan WHERE email_pelanggan = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Simpan pelanggan baru
    public function createPelanggan($data) {
        $query = "INSERT INTO pelanggan (nama_pelanggan, email_pelanggan, no_telp_pelanggan) 
                  VALUES (:nama, :email, :telp)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $data['nama_pelanggan']);
        $stmt->bindParam(':email', $data['email_pelanggan']);
        $stmt->bindParam(':telp', $data['no_telp_pelanggan']);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Ambil atau buat pelanggan berdasarkan data (gunakan email sebagai unique key)
    public function getOrCreatePelanggan($data) {
        $existing = $this->getPelangganByEmail($data['email_pelanggan']);
        if ($existing) {
            return $existing['id'];
        } else {
            return $this->createPelanggan($data);
        }
    }
}
?>