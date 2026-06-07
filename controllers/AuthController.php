<<<<<<< HEAD
<?php
// controllers/AuthController.php
// session_start();
require_once 'models/UsersModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function loginForm() {
        include 'views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->userModel->getUserByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_nama'] = $user['nama'];
                $_SESSION['admin_email'] = $user['email'];
                header("Location: index.php?controller=booking&action=index");
            } else {
                echo "<script>alert('Email atau password salah!'); window.location.href='index.php?controller=auth&action=loginForm';</script>";
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=loginForm");
    }
}
=======
<?php
// controllers/AuthController.php
// session_start();
require_once 'models/UsersModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function loginForm() {
        include 'views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->userModel->getUserByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_nama'] = $user['nama'];
                $_SESSION['admin_email'] = $user['email'];
                header("Location: index.php?controller=booking&action=index");
            } else {
                echo "<script>alert('Email atau password salah!'); window.location.href='index.php?controller=auth&action=loginForm';</script>";
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=loginForm");
    }
}
>>>>>>> master
?>