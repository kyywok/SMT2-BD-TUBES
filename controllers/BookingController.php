
<?php
// session_start();
require_once 'models/BookingModel.php';
require_once 'models/LapanganModel.php';
require_once 'models/PelangganModel.php';  


class BookingController {
    private $bookingModel;
    private $lapanganModel;
    private $pelangganModel; 

    public function __construct() {
        $this->bookingModel = new BookingModel();
        $this->lapanganModel = new LapanganModel();
        $this->pelangganModel = new PelangganModel();
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
       // Pastikan ada data booking sementara
    if (!isset($_SESSION['temp_booking'])) {
        header("Location: index.php?controller=home&action=index");
        exit();
    }
    
    // Jika belum ada expiry time, buat baru (5 menit dari sekarang)
    if (!isset($_SESSION['payment_expiry'])) {
        $_SESSION['payment_expiry'] = time() + (5 * 60); // 5 menit dalam detik
    }
    
    // Cek apakah sudah expired
    if (time() > $_SESSION['payment_expiry']) {
        // Hapus session booking dan expiry, lalu redirect dengan pesan
        unset($_SESSION['temp_booking']);
        unset($_SESSION['payment_expiry']);
        echo "<script>alert('Waktu pembayaran habis! Silakan booking ulang.'); window.location.href='index.php?controller=home&action=index';</script>";
        exit();
    }
    
    $booking = $_SESSION['temp_booking'];
    // Hitung sisa waktu untuk ditampilkan di view
    $remaining = $_SESSION['payment_expiry'] - time();
    include 'views/booking/payment.php';
    }

    // STEP 2: Proses upload bukti dan simpan ke database
      public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['temp_booking'])) {
            $temp = $_SESSION['temp_booking'];

            // 1. Dapatkan atau buat pelanggan berdasarkan email
            $dataPelanggan = [
                'nama_pelanggan' => $temp['nama_pelanggan'],
                'email_pelanggan' => $temp['email_pelanggan'],
                'no_telp_pelanggan' => $temp['no_telp_pelanggan']
            ];
            $id_pelanggan = $this->pelangganModel->getOrCreatePelanggan($dataPelanggan);

            if (!$id_pelanggan) {
                echo "<script>alert('Gagal menyimpan data pelanggan.'); window.history.back();</script>";
                return;
            }

            // 2. Siapkan data booking dengan id_pelanggan
            $dataBooking = [
                'id_pelanggan' => $id_pelanggan,
                'id_lapangan' => $temp['id_lapangan'],
                'tanggal_sewa' => $temp['tanggal_sewa'],
                'jam_mulai' => $temp['jam_mulai'],
                'jam_selesai' => $temp['jam_selesai'],
                'total_biaya' => $temp['total_biaya']
            ];

            $metode_bayar = isset($_POST['metode_bayar']) ? (int)$_POST['metode_bayar'] : 1;
            $booking_id = $this->bookingModel->createBookingWithPayment($dataBooking, $_FILES['bukti_transfer'], $metode_bayar);
            
            if ($booking_id) {
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
 // controllers/BookingController.php
public function updateStatus() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: index.php?controller=auth&action=loginForm");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_booking = isset($_POST['id_booking']) ? (int)$_POST['id_booking'] : 0;
        $status_booking = isset($_POST['status_booking']) ? (int)$_POST['status_booking'] : 1;

        if (!in_array($status_booking, [1,2,3])) {
            echo "<script>alert('Status tidak valid!'); window.history.back();</script>";
            return;
        }

        if ($this->bookingModel->updateStatusBooking($id_booking, $status_booking)) {
            // ✅ INI YANG KURANG
            header("Location: index.php?controller=booking&action=index");
            exit();
        } else {
            echo "<script>alert('Gagal mengubah status.'); window.history.back();</script>";
        }
    }
}


}
?>