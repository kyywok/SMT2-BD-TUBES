
<?php include 'views/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-primary text-white">Tambah Lapangan Baru</div>
    <div class="card-body">
        <form action="index.php?controller=lapangan&action=store" method="POST">
            <div class="mb-3">
                <label>Nama Lapangan</label>
                <input type="text" name="nama_lapangan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Jenis Lapangan</label>
                <select name="jenis_lapangan" class="form-select">
                    <option value="1">Badminton</option>
                    <option value="2">Futsal</option>
                    <option value="3">Tennis</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Harga per Jam (Rp)</label>
                <input type="number" name="harga_per_jam" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Fasilitas</label>
                <textarea name="fasilitas" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status_lapangan" class="form-select">
                    <option value="1">Tersedia</option>
                    <option value="0">Tidak Tersedia</option>
                </select>
            </div>
            <!-- ... input lain ... -->
    <div class="mb-3">
        <label>Foto Lapangan</label>
        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
        <small class="text-muted">Ukuran maksimal 2MB, format JPG/PNG</small>
    </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php include 'views/templates/footer.php'; ?>