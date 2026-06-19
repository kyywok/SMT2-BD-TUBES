
<?php
// controllers/LapanganController.php
require_once 'models/LapanganModel.php';

class LapanganController {
    private $model;

    public function __construct() {
        $this->model = new LapanganModel();
    }

    // Menampilkan daftar lapangan
    public function index() {
        $lapanganList = $this->model->getAllLapangan();
        include 'views/lapangan/index.php';
    }

    // Menampilkan form tambah data
    public function create() {
        include 'views/lapangan/create.php';
    }

    // Menyimpan data baru (INSERT)
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nama_lapangan' => $_POST['nama_lapangan'],
                'jenis_lapangan' => $_POST['jenis_lapangan'],
                'harga_per_jam' => $_POST['harga_per_jam'],
                'status_lapangan' => $_POST['status_lapangan']
            ];
            if($this->model->createLapangan($data)) {
                header("Location: index.php?controller=lapangan&action=index");
            } else {
                echo "Gagal menambahkan lapangan.";
            }
        }
    }

    // Menampilkan form edit berdasarkan id
    public function edit($id) {
        $lapangan = $this->model->getLapanganById($id);
        include 'views/lapangan/edit.php';
    }

    // Memproses update data (UPDATE)
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nama_lapangan' => $_POST['nama_lapangan'],
                'jenis_lapangan' => $_POST['jenis_lapangan'],
                'harga_per_jam' => $_POST['harga_per_jam'],
                'status_lapangan' => $_POST['status_lapangan']
            ];
            if($this->model->updateLapangan($id, $data)) {
                header("Location: index.php?controller=lapangan&action=index");
            } else {
                echo "Gagal mengupdate lapangan.";
            }
        }
    }

    // Menghapus data (DELETE)
    public function delete($id) {
        if($this->model->deleteLapangan($id)) {
            header("Location: index.php?controller=lapangan&action=index");
        } else {
            echo "Gagal menghapus lapangan.";
        }
    }
}
?>