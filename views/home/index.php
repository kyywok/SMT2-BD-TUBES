<?php include 'views/templates/header.php'; ?>

<!-- Hero Section -->
<div class="text-center bg-primary text-white p-5 rounded mb-4">
    <h1>🏸 Arena Sport Booking</h1>
    <p>Booking lapangan futsal, badminton, dan tennis dengan mudah</p>
</div>

<!-- Daftar Lapangan -->
<h2 class="mb-4">Pilih Lapangan Favoritmu</h2>
<div class="row">
    <?php foreach($lapanganList as $lapangan): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow">


            <?php 
            // Mapping gambar berdasarkan jenis_lapangan langsung dari assets/img
            $foto_map = [
                1 =>  'assets/img/A1.jfif',
                2 =>  'assets/img/lapangan2.jfif',
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
                    <strong>Fasilitas:</strong> <?= htmlspecialchars($lapangan['fasilitas']) ?><br>
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

<?php include 'views/templates/footer.php'; ?>