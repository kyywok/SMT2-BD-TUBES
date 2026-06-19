
<?php
// models/BookingModel.php
require_once 'config/database.php';

class BookingModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method untuk booking tanpa login (pelanggan umum)
    // models/BookingModel.php
public function createBookingWithPayment($data, $file_bukti, $metode_bayar) {
    try {
        $this->conn->beginTransaction();

        // 1. Insert ke tabel booking (HANYA kolom yang ada di tabel booking)
        $query = "INSERT INTO booking (id_pelanggan, id_lapangan, tanggal_sewa, jam_mulai, jam_selesai, total_biaya, status_booking)
                  VALUES (:id_pelanggan, :id_lapangan, :tanggal_sewa, :jam_mulai, :jam_selesai, :total_biaya, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pelanggan', $data['id_pelanggan']);
        $stmt->bindParam(':id_lapangan', $data['id_lapangan']);
        $stmt->bindParam(':tanggal_sewa', $data['tanggal_sewa']);
        $stmt->bindParam(':jam_mulai', $data['jam_mulai']);
        $stmt->bindParam(':jam_selesai', $data['jam_selesai']);
        $stmt->bindParam(':total_biaya', $data['total_biaya']);
        $stmt->execute();
        $booking_id = $this->conn->lastInsertId();

        // 2. Upload file bukti transfer (sama seperti sebelumnya)
        $target_dir = "uploads/";
        $file_name = time() . '_' . basename($file_bukti['name']);
        $target_file = $target_dir . $file_name;
        if (!move_uploaded_file($file_bukti['tmp_name'], $target_file)) {
            throw new Exception("Gagal upload file bukti transfer");
        }

        // 3. Insert ke tabel pembayaran (sesuai struktur PDM Anda)
        $query2 = "INSERT INTO pembayaran (id_booking, jumlah_bayar, metode_bayar, bukti_transfer, tanggal_bayar)
                   VALUES (:id_booking, :jumlah_bayar, :metode_bayar, :bukti_transfer, NULL)";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(':id_booking', $booking_id);
        $stmt2->bindParam(':jumlah_bayar', $data['total_biaya']);
        $stmt2->bindParam(':metode_bayar', $metode_bayar);
        $stmt2->bindParam(':bukti_transfer', $file_name);
        $stmt2->execute();

        $this->conn->commit();
        return $booking_id;
    } catch (Exception $e) {
        $this->conn->rollBack();
        // Tulis error ke file untuk debugging
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}

    // Cek ketersediaan lapangan (apakah sudah dibooking pada tanggal dan jam tersebut)
    public function cekKetersediaan($id_lapangan, $tanggal_sewa, $jam_mulai, $jam_selesai) {
        $query = "SELECT * FROM booking 
                  WHERE id_lapangan = :id_lapangan 
                  AND tanggal_sewa = :tanggal_sewa
                  AND status_booking != 3  -- 3 = batal
                  AND (
                      (jam_mulai < :jam_selesai AND jam_selesai > :jam_mulai)
                  )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_lapangan', $id_lapangan);
        $stmt->bindParam(':tanggal_sewa', $tanggal_sewa);
        $stmt->bindParam(':jam_mulai', $jam_mulai);
        $stmt->bindParam(':jam_selesai', $jam_selesai);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Untuk admin: ambil semua booking dengan JOIN lapangan dan pembayaran
   public function getAllBookingsForAdmin() {
    $query = "SELECT b.*, 
                     l.nama_lapangan, l.harga_per_jam, 
                     p.bukti_transfer, p.metode_bayar, p.tanggal_bayar,
                     pel.nama_pelanggan, pel.email_pelanggan, pel.no_telp_pelanggan
              FROM booking b
              INNER JOIN lapangan l ON b.id_lapangan = l.id
              INNER JOIN pelanggan pel ON b.id_pelanggan = pel.id
              LEFT JOIN pembayaran p ON b.id = p.id_booking
              ORDER BY b.tanggal_sewa DESC, b.jam_mulai ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Admin: ubah status booking (1=menunggu, 2=lunas, 3=batal)
    public function updateStatusBooking($id_booking, $status_booking) {
          $query = "UPDATE booking SET status_booking = :status_booking WHERE id = :id_booking";
    $stmt = $this->conn->prepare($query);

    $stmt->bindValue(':status_booking', (int)$status_booking, PDO::PARAM_INT);
    $stmt->bindValue(':id_booking', (int)$id_booking, PDO::PARAM_INT);

    $stmt->execute();

    // 🔥 CEK apakah benar update
    return $stmt->rowCount();
    }

    // Admin: update status pembayaran menjadi lunas
    // public function updateStatusPembayaran($id_booking, $ = 1) {
    //     $query = "UPDATE pembayaran SET status_bayar = :status, tanggal_bayar = CURDATE() WHERE id_booking = :id_booking";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':status', $status_bayar);
    //     $stmt->bindParam(':id_booking', $id_booking);
    //     return $stmt->execute();
    // }

    // Fungsi agregat untuk dashboard admin
    public function getJumlahBookingMenunggu() {
        $query = "SELECT COUNT(*) as total FROM booking WHERE status_booking = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalPendapatanLunas() {
        $query = "SELECT SUM(CAST(total_biaya AS DECIMAL(15,2))) as total FROM booking WHERE status_booking = 2";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }

public function getBookingsByEmail($email) {
    $query = "SELECT b.*, l.nama_lapangan, 
                     p.metode_bayar, p.bukti_transfer, p.tanggal_bayar,
                     pel.nama_pelanggan, pel.email_pelanggan, pel.no_telp_pelanggan,
                     b.status_booking
              FROM booking b
              INNER JOIN lapangan l ON b.id_lapangan = l.id
              INNER JOIN pelanggan pel ON b.id_pelanggan = pel.id
              LEFT JOIN pembayaran p ON b.id = p.id_booking
              WHERE pel.email_pelanggan = :email
              ORDER BY b.tanggal_sewa DESC, b.jam_mulai DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}