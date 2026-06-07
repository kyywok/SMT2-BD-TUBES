
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
    public function createBookingWithPayment($data, $file_bukti) {
        try {
            $this->conn->beginTransaction();

            // 1. Insert ke tabel booking
            $query = "INSERT INTO booking (id_lapangan, nama_pelanggan, email_pelanggan, no_telp_pelanggan, 
                      tanggal_sewa, jam_mulai, jam_selesai, total_biaya, status_booking)
                      VALUES (:id_lapangan, :nama_pelanggan, :email_pelanggan, :no_telp_pelanggan,
                      :tanggal_sewa, :jam_mulai, :jam_selesai, :total_biaya, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_lapangan', $data['id_lapangan']);
            $stmt->bindParam(':nama_pelanggan', $data['nama_pelanggan']);
            $stmt->bindParam(':email_pelanggan', $data['email_pelanggan']);
            $stmt->bindParam(':no_telp_pelanggan', $data['no_telp_pelanggan']);
            $stmt->bindParam(':tanggal_sewa', $data['tanggal_sewa']);
            $stmt->bindParam(':jam_mulai', $data['jam_mulai']);
            $stmt->bindParam(':jam_selesai', $data['jam_selesai']);
            $stmt->bindParam(':total_biaya', $data['total_biaya']);
            $stmt->execute();
            $booking_id = $this->conn->lastInsertId();

            // 2. Upload file bukti transfer
            $target_dir = "uploads/";
            $file_name = time() . '_' . basename($file_bukti['name']);
            $target_file = $target_dir . $file_name;
            move_uploaded_file($file_bukti['tmp_name'], $target_file);

            // 3. Insert ke tabel pembayaran
            $kode_transaksi = rand(100000, 999999);
            $batas_waktu = date('H:i:s', strtotime('+24 hours'));
            $query2 = "INSERT INTO pembayaran (id_booking, jumlah_bayar, metode_bayar, bukti_transfer, 
                       status_bayar, kode_transaksi, batas_waktu, tanggal_bayar)
                       VALUES (:id_booking, :jumlah_bayar, 1, :bukti_transfer, 0, :kode_transaksi, :batas_waktu, NULL)";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':id_booking', $booking_id);
            $stmt2->bindParam(':jumlah_bayar', $data['total_biaya']);
            $stmt2->bindParam(':bukti_transfer', $file_name);
            $stmt2->bindParam(':kode_transaksi', $kode_transaksi);
            $stmt2->bindParam(':batas_waktu', $batas_waktu);
            $stmt2->execute();

            $this->conn->commit();
            return $booking_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
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
        $query = "SELECT b.*, l.nama_lapangan, l.harga_per_jam, p.bukti_transfer, p.status_bayar, p.kode_transaksi
                  FROM booking b
                  INNER JOIN lapangan l ON b.id_lapangan = l.id
                  LEFT JOIN pembayaran p ON b.id = p.id_booking
                  ORDER BY b.tanggal_sewa DESC, b.jam_mulai ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: ubah status booking (1=menunggu, 2=lunas, 3=batal)
    public function updateStatusBooking($id_booking, $status_booking) {
        $query = "UPDATE booking SET status_booking = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status_booking);
        $stmt->bindParam(':id', $id_booking);
        return $stmt->execute();
    }

    // Admin: update status pembayaran menjadi lunas
    public function updateStatusPembayaran($id_booking, $status_bayar = 1) {
        $query = "UPDATE pembayaran SET status_bayar = :status, tanggal_bayar = CURDATE() WHERE id_booking = :id_booking";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status_bayar);
        $stmt->bindParam(':id_booking', $id_booking);
        return $stmt->execute();
    }

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
}
?>