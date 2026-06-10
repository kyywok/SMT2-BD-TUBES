<?php
// controllers/HomeController.php
require_once 'models/LapanganModel.php';
require_once 'models/BookingModel.php';  // tambahkan ini

class HomeController {
    private $lapanganModel;
    private $bookingModel;

    public function __construct() {
        $this->lapanganModel = new LapanganModel();
        $this->bookingModel = new BookingModel();  // tambahkan
    }

    public function index() {
        // Ambil semua lapangan untuk ditampilkan di dashboard
        $lapanganList = $this->lapanganModel->getAllLapangan();
        
        // Inisialisasi variabel untuk hasil pencarian
        $searchResult = null;
        $searchEmail = '';
        
        // Cek apakah ada parameter 'email' di URL (method GET)
        if (isset($_GET['email']) && !empty($_GET['email'])) {
            $searchEmail = trim($_GET['email']);
            $searchResult = $this->bookingModel->getBookingsByEmail($searchEmail);
        }
        
        // Tampilkan view dengan mengirim data
        include 'views/home/index.php';
    }
}
?>