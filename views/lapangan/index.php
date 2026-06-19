
<?php include 'views/templates/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Lapangan</h2>
    <a href="index.php?controller=lapangan&action=create" class="btn btn-success">+ Tambah Lapangan</a>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th><th>Nama Lapangan</th><th>Jenis</th><th>Harga/Jam</th><th>Status</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach($lapanganList as $lap):  ?>
        <tr>
            <td><?= $po = $no++ ?></td>
            <td><?= htmlspecialchars($lap['nama_lapangan']) ?></td>
            <td><?= $lap['jenis_lapangan'] == 1 ? 'Badminton' : ($lap['jenis_lapangan'] == 2 ? 'Futsal' : 'Tennis') ?></td>
            <td>Rp <?= number_format($lap['harga_per_jam'], 0, ',', '.') ?></td>
            <td><?= $lap['status_lapangan'] == 1 ? '<span class="badge bg-success">Tersedia</span>' : '<span class="badge bg-danger">Tidak Tersedia</span>' ?></td>
            <td>
                <a href="index.php?controller=lapangan&action=edit&id=<?= $lap['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="index.php?controller=lapangan&action=delete&id=<?= $lap['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include 'views/templates/footer.php'; ?>