<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan Olahraga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🏸 Booking Lapangan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?controller=booking&action=create">Booking Lapangan</a></li>
                <?php if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=booking&action=index">Kelola Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=lapangan&action=index">Kelola Lapangan</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="#">Admin: <?= $_SESSION['admin_nama'] ?></a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger btn-sm text-white" href="index.php?controller=auth&action=logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=loginForm">Login Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
=======
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan Olahraga</title>
    <!-- Bootstrap CSS lokal -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Opsional: Bootstrap Icons lokal jika diunduh -->
    <!-- <link href="assets/css/bootstrap-icons.css" rel="stylesheet"> -->
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🏸 Booking Lapangan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?controller=home&action=index">Dashboard</a></li>
                <?php if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=booking&action=index">Kelola Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=lapangan&action=index">Kelola Lapangan</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="#">Admin: <?= $_SESSION['admin_nama'] ?></a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger btn-sm text-white" href="index.php?controller=auth&action=logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=loginForm">Login Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
>>>>>>> master
<div class="container mt-4">