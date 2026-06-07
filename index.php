
<?php
// index.php
session_start();
require_once 'controllers/HomeController.php';
require_once 'controllers/BookingController.php';
require_once 'controllers/LapanganController.php';
require_once 'controllers/AuthController.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'create';  // default ke form booking untuk publik

// HomeController (publik, tanpa login)
if ($controller == 'home') {
    $homeController = new HomeController();
    if ($action == 'index') {
        $homeController->index();
    } else {
        $homeController->index();
    }
}

// BookingController (publik untuk create, storeStep1, payment, processPayment, success; admin untuk index, updateStatus)
elseif ($controller == 'booking') {
    $bookingController = new BookingController();
    if ($action == 'create') {
        $bookingController->create();
    } elseif ($action == 'storeStep1') {
        $bookingController->storeStep1();
    } elseif ($action == 'payment') {
        $bookingController->payment();
    } elseif ($action == 'processPayment') {
        $bookingController->processPayment();
    } elseif ($action == 'success') {
        $bookingController->success();
    } elseif ($action == 'index') {
        // Admin only
        $bookingController->index();
    } elseif ($action == 'updateStatus') {
        $bookingController->updateStatus();
    } else {
        $bookingController->create();
    }
}

elseif ($controller == 'lapangan') {
    // Hanya bisa diakses jika admin login
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: index.php?controller=auth&action=loginForm");
        exit();
    }
    $lapanganController = new LapanganController();
    switch($action) {
        case 'index': $lapanganController->index(); break;
        case 'create': $lapanganController->create(); break;
        case 'store': $lapanganController->store(); break;
        case 'edit': if(isset($_GET['id'])) $lapanganController->edit($_GET['id']); break;
        case 'update': if(isset($_GET['id'])) $lapanganController->update($_GET['id']); break;
        case 'delete': if(isset($_GET['id'])) $lapanganController->delete($_GET['id']); break;
        default: $lapanganController->index();
    }
}
elseif ($controller == 'auth') {
    $authController = new AuthController();
    if ($action == 'loginForm') {
        $authController->loginForm();
    } elseif ($action == 'login') {
        $authController->login();
    } elseif ($action == 'logout') {
        $authController->logout();
    }
}
else {
    // Default ke halaman booking publik
    header("Location: index.php?controller=booking&action=create");
}
?>