<?php include 'views/templates/header.php'; ?>

<!-- Hero Section -->
<div class="text-center bg-primary text-white p-5 rounded mb-4">
    <h1> Arena Sport Booking</h1>
    <p>Booking lapangan futsal dan badminton dengan mudah</p>
</div>

<!-- Daftar Lapangan -->
<h2 class="mb-4">Pilih Lapangan Favoritmu</h2>
<div class="row">
    <?php foreach($lapanganList as $lapangan): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow">

            <?php 
            // Mapping gambar berdasarkan nama_lapangan
            $foto_map = [
                'A1' => 'assets/img/A1.jfif',
                'A2' => 'assets/img/A2.jfif',
                'B1' => 'assets/img/B1.jfif',
                'B2' => 'assets/img/B2.jfif',
            ];
            $foto_src = $foto_map[$lapangan['nama_lapangan']] ?? 'assets/img/default.jpg';
            ?>

            <img src="<?= $foto_src ?>" class="card-img-top" 
                 alt="<?= htmlspecialchars($lapangan['nama_lapangan']) ?>" 
                 style="height: 200px; object-fit: cover;">

            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($lapangan['nama_lapangan']) ?></h5>
                <p class="card-text">
                    <strong>Jenis:</strong> 
                    <?php 
                        if($lapangan['jenis_lapangan']==1) echo 'Badminton';
                        elseif($lapangan['jenis_lapangan']==2) echo 'Futsal';
                        else echo 'Tennis';
                    ?><br>
                    <strong>Harga:</strong> Rp <?= number_format($lapangan['harga_per_jam'],0,',','.') ?> / jam<br>
                    <strong>Status:</strong> 
                    <?php if($lapangan['status_lapangan']==1): ?>
                        <span class="badge bg-success">Tersedia</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Tidak Tersedia</span>
                    <?php endif; ?>
                </p>
                <?php if($lapangan['status_lapangan']==1): ?>
                    <a href="index.php?controller=booking&action=create&id_lapangan=<?= $lapangan['id'] ?>" 
                       class="btn btn-primary w-100">Booking Sekarang</a>
                <?php else: ?>
                    <button class="btn btn-secondary w-100" disabled>Tidak Tersedia</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<!-- Form Pencarian Status Booking -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <strong>Cek Status Booking Anda</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="controller" value="home">
            <input type="hidden" name="action" value="index">
            <div class="col-md-8">
                <input type="email" name="email" class="form-control" 
                       placeholder="Masukkan email Anda saat booking" 
                       value="<?= htmlspecialchars($searchEmail) ?>" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">    Cek Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Hasil Pencarian (jika ada) -->
<?php if ($searchResult !== null): ?>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Hasil Pencarian untuk Email: <?= htmlspecialchars($searchEmail) ?>
        </div>
    <div class="card-body">
            <?php if (count($searchResult) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Lapangan</th>
                                <th>Tanggal Sewa</th>
                                <th>Jam</th>
                                <th>Total Biaya</th>
                                <th>Status Booking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($searchResult as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['nama_lapangan']) ?></td>
                                <td><?= date('d-m-Y', strtotime($booking['tanggal_sewa'])) ?></td>
                                <td><?= substr($booking['jam_mulai'],0,5) ?> - <?= substr($booking['jam_selesai'],0,5) ?></td>
                                <td>Rp <?= number_format($booking['total_biaya'],0,',','.') ?></td>
                                <td>
                                    <?php 
                                        if ($booking['status_booking'] == 1) echo '<span class="badge bg-warning">Menunggu Konfirmasi</span>';
                                        elseif ($booking['status_booking'] == 2) echo '<span class="badge bg-success">Dikonfirmasi</span>';
                                        else echo '<span class="badge bg-danger">Dibatalkan</span>';
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Tidak ditemukan booking dengan email <?= htmlspecialchars($searchEmail) ?>. Pastikan email yang Anda masukkan sesuai.</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php include 'views/templates/footer.php'; ?>