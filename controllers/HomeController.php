<<<<<<< HEAD
<?php
// controllers/HomeController.php
require_once 'models/LapanganModel.php';

class HomeController {
    private $lapanganModel;

    public function __construct() {
        $this->lapanganModel = new LapanganModel();
    }

    public function index() {
        // Ambil semua lapangan yang tersedia (status=1)
        $lapanganList = $this->lapanganModel->getAllLapangan();
        include 'views/home/index.php';
    }
}
=======
<?php
// controllers/HomeController.php
require_once 'models/LapanganModel.php';

class HomeController {
    private $lapanganModel;

    public function __construct() {
        $this->lapanganModel = new LapanganModel();
    }

    public function index() {
        // Ambil semua lapangan yang tersedia (status=1)
        $lapanganList = $this->lapanganModel->getAllLapangan();
        include 'views/home/index.php';
    }
}
>>>>>>> master
?>