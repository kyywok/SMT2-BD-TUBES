<?php
// controllers/BookingController.php (bagian yang diubah)
// session_start();
require_once 'models/BookingModel.php';
require_once 'models/LapanganModel.php';

class BookingController {
    private $bookingModel;
    private $lapanganModel;

    public function __construct() {
        $this->bookingModel = new BookingModel();
        $this->lapanganModel = new LapanganModel();
    }

    // STEP 1: Tampilkan form booking (jika ada id_lapangan dari URL, pre-select)
    public function create() {
        $id_lapangan = isset($_GET['id_lapangan']) ? $_GET['id_lapangan'] : null;
        $lapanganList = $this->lapanganModel->getAllLapangan();
        $selectedLapangan = null;
        if ($id_lapangan) {
            $selectedLapangan = $this->lapanganModel->getLapanganById($id_lapangan);
        }
        include 'views/booking/create.php';
    }

    // STEP 1: Simpan data sementara ke session, lalu redirect ke halaman pembayaran
    public function storeStep1() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi input
            $id_lapangan = $_POST['id_lapangan'];
            $tanggal_sewa = $_POST['tanggal_sewa'];
            $jam_mulai = $_POST['jam_mulai'];
            $jam_selesai = $_POST['jam_selesai'];
            $nama_pelanggan = $_POST['nama_pelanggan'];
            $email_pelanggan = $_POST['email_pelanggan'];
            $no_telp_pelanggan = $_POST['no_telp_pelanggan'];

            // Ambil harga lapangan
            $lapangan = $this->lapanganModel->getLapanganById($id_lapangan);
            if (!$lapangan) {
                echo "<script>alert('Lapangan tidak ditemukan!'); window.history.back();</script>";
                return;
            }
            $harga_per_jam = $lapangan['harga_per_jam'];

            // Hitung durasi dan total biaya
            $start = new DateTime($jam_mulai);
            $end = new DateTime($jam_selesai);
            $diff = $start->diff($end);
            $durasi_jam = $diff->h + ($diff->i / 60);
            if ($durasi_jam <= 0) {
                echo "<script>alert('Durasi minimal 1 jam!'); window.history.back();</script>";
                return;
            }
            $total_biaya = $durasi_jam * $harga_per_jam;

            // Cek ketersediaan (apakah sudah ada booking di jam tersebut)
            if ($this->bookingModel->cekKetersediaan($id_lapangan, $tanggal_sewa, $jam_mulai, $jam_selesai)) {
                echo "<script>alert('Maaf, lapangan sudah dibooking pada jam tersebut!'); window.history.back();</script>";
                return;
            }

            // Simpan data sementara ke session (cart)
            $_SESSION['temp_booking'] = [
                'id_lapangan' => $id_lapangan,
                'nama_lapangan' => $lapangan['nama_lapangan'],
                'harga_per_jam' => $harga_per_jam,
                'tanggal_sewa' => $tanggal_sewa,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'durasi_jam' => $durasi_jam,
                'total_biaya' => $total_biaya,
                'nama_pelanggan' => $nama_pelanggan,
                'email_pelanggan' => $email_pelanggan,
                'no_telp_pelanggan' => $no_telp_pelanggan
            ];

            // Redirect ke halaman pembayaran
            header("Location: index.php?controller=booking&action=payment");
        }
    }

    // STEP 2: Tampilkan halaman pembayaran (ringkasan + upload bukti)
    public function payment() {
        // Pastikan ada data temp_booking di session
        if (!isset($_SESSION['temp_booking'])) {
            header("Location: index.php?controller=home&action=index");
            exit();
        }
        $booking = $_SESSION['temp_booking'];
        include 'views/booking/payment.php';
    }

    // STEP 2: Proses upload bukti dan simpan ke database
    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['temp_booking'])) {
            $temp = $_SESSION['temp_booking'];

            // Validasi file upload
            if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] != 0) {
                echo "<script>alert('Harap upload bukti transfer!'); window.history.back();</script>";
                return;
            }

            // Siapkan data untuk disimpan
            $data = [
                'id_lapangan' => $temp['id_lapangan'],
                'nama_pelanggan' => $temp['nama_pelanggan'],
                'email_pelanggan' => $temp['email_pelanggan'],
                'no_telp_pelanggan' => $temp['no_telp_pelanggan'],
                'tanggal_sewa' => $temp['tanggal_sewa'],
                'jam_mulai' => $temp['jam_mulai'],
                'jam_selesai' => $temp['jam_selesai'],
                'total_biaya' => $temp['total_biaya']
            ];

            // Simpan booking dan pembayaran (method sebelumnya)
            $booking_id = $this->bookingModel->createBookingWithPayment($data, $_FILES['bukti_transfer']);
            if ($booking_id) {
                // Hapus data sementara dari session
                unset($_SESSION['temp_booking']);
                header("Location: index.php?controller=booking&action=success&id=" . $booking_id);
            } else {
                echo "<script>alert('Gagal menyimpan booking. Coba lagi.'); window.history.back();</script>";
            }
        } else {
            header("Location: index.php?controller=home&action=index");
        }
    }

      // Halaman sukses setelah booking
    public function success() {
        $booking_id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$booking_id) {
            header("Location: index.php");
            exit();
        }
        // Ambil data booking untuk ditampilkan
        // Sederhananya kita tampilkan pesan sukses
        include 'views/booking/success.php';
    }

    // ... method lain untuk admin (index, updateStatus) tetap sama seperti sebelumnya


    // ========== AREA ADMIN (HARUS LOGIN) ==========
    // Menampilkan daftar booking untuk admin
    public function index() {
        // Proteksi: hanya admin yang login
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?controller=auth&action=loginForm");
            exit();
        }

        $bookings = $this->bookingModel->getAllBookingsForAdmin();
        $jumlahMenunggu = $this->bookingModel->getJumlahBookingMenunggu();
        $totalPendapatan = $this->bookingModel->getTotalPendapatanLunas();
        include 'views/booking/index.php';
    }

    // Admin: update status booking (via AJAX atau POST)
    public function updateStatus() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?controller=auth&action=loginForm");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_booking = $_POST['id_booking'];
            $status_booking = $_POST['status_booking'];

            if ($this->bookingModel->updateStatusBooking($id_booking, $status_booking)) {
                // Jika status booking menjadi lunas (2), update juga status pembayaran
                if ($status_booking == 2) {
                    $this->bookingModel->updateStatusPembayaran($id_booking, 1);
                }
                echo "<script>alert('Status booking berhasil diubah!'); window.location.href='index.php?controller=booking&action=index';</script>";
            } else {
                echo "<script>alert('Gagal mengubah status.'); window.history.back();</script>";
            }
        }
    }
}
?>