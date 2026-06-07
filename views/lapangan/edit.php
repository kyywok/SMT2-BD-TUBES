
<?php include 'views/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-warning">Edit Lapangan</div>
    <div class="card-body">
        <form action="index.php?controller=lapangan&action=update&id=<?= $lapangan['id'] ?>" method="POST">
            <div class="mb-3">
                <label>Nama Lapangan</label>
                <input type="text" name="nama_lapangan" class="form-control" value="<?= htmlspecialchars($lapangan['nama_lapangan']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Jenis Lapangan</label>
                <select name="jenis_lapangan" class="form-select">
                    <option value="1" <?= $lapangan['jenis_lapangan']==1?'selected':'' ?>>Badminton</option>
                    <option value="2" <?= $lapangan['jenis_lapangan']==2?'selected':'' ?>>Futsal</option>
                    <option value="3" <?= $lapangan['jenis_lapangan']==3?'selected':'' ?>>Tennis</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Harga per Jam (Rp)</label>
                <input type="number" name="harga_per_jam" class="form-control" value="<?= $lapangan['harga_per_jam'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Fasilitas</label>
                <textarea name="fasilitas" class="form-control"><?= htmlspecialchars($lapangan['fasilitas']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status_lapangan" class="form-select">
                    <option value="1" <?= $lapangan['status_lapangan']==1?'selected':'' ?>>Tersedia</option>
                    <option value="0" <?= $lapangan['status_lapangan']==0?'selected':'' ?>>Tidak Tersedia</option>
                </select>
            </div>
             <input type="hidden" name="foto_lama" value="<?= $lapangan['foto'] ?>">
    <!-- ... input lain ... -->
    <div class="mb-3">
        <label>Foto Saat Ini</label><br>
        <?php if($lapangan['foto'] && file_exists("uploads/lapangan/" . $lapangan['foto'])): ?>
            <img src="uploads/lapangan/<?= $lapangan['foto'] ?>" width="150" class="img-thumbnail mb-2"><br>
        <?php else: ?>
            <span class="text-muted">Belum ada foto</span><br>
        <?php endif; ?>
        <label>Ganti Foto (opsional)</label>
        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
    </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php include 'views/templates/footer.php'; ?>