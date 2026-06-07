<<<<<<< HEAD
<?php include 'views/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-primary text-white">Form Booking Lapangan</div>
    <div class="card-body">
        <form action="index.php?controller=booking&action=storeStep1" method="POST">
            <!-- Data Pelanggan -->
            <h5>Data Pemesan</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_pelanggan" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Email</label>
                    <input type="email" name="email_pelanggan" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp_pelanggan" class="form-control" required>
                </div>
            </div>

            <!-- Pilih Lapangan -->
            <h5>Pilih Lapangan & Jadwal</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Lapangan</label>
                    <select name="id_lapangan" id="id_lapangan" class="form-select" required>
                        <option value="">-- Pilih Lapangan --</option>
                        <?php foreach($lapanganList as $lap): ?>
                            <option value="<?= $lap['id'] ?>" 
                                <?= (isset($selectedLapangan) && $selectedLapangan['id'] == $lap['id']) ? 'selected' : '' ?>
                                data-harga="<?= $lap['harga_per_jam'] ?>">
                                <?= htmlspecialchars($lap['nama_lapangan']) ?> - Rp <?= number_format($lap['harga_per_jam'],0,',','.') ?>/jam
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tanggal Sewa</label>
                    <input type="date" name="tanggal_sewa" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Total Biaya (Rp)</label>
                    <input type="text" id="total_biaya_display" class="form-control" readonly disabled>
                </div>
            </div>

            <div class="alert alert-info">
                <strong>Langkah 1 dari 2:</strong> Selanjutnya Anda akan diminta mengupload bukti pembayaran.
            </div>

            <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
    const hargaSelect = document.getElementById('id_lapangan');
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');
    const totalDisplay = document.getElementById('total_biaya_display');

    function hitungTotal() {
        const harga = hargaSelect.options[hargaSelect.selectedIndex]?.getAttribute('data-harga');
        if (!harga || !jamMulai.value || !jamSelesai.value) {
            totalDisplay.value = '';
            return;
        }
        const start = jamMulai.value.split(':');
        const end = jamSelesai.value.split(':');
        let durasi = (parseInt(end[0]) + end[1]/60) - (parseInt(start[0]) + start[1]/60);
        if (durasi <= 0) {
            totalDisplay.value = 'Durasi tidak valid';
            return;
        }
        const total = durasi * parseFloat(harga);
        totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
    }

    hargaSelect.addEventListener('change', hitungTotal);
    jamMulai.addEventListener('change', hitungTotal);
    jamSelesai.addEventListener('change', hitungTotal);
</script>

=======
<?php include 'views/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-primary text-white">Form Booking Lapangan</div>
    <div class="card-body">
        <form action="index.php?controller=booking&action=storeStep1" method="POST">
            <!-- Data Pelanggan -->
            <h5>Data Pemesan</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_pelanggan" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Email</label>
                    <input type="email" name="email_pelanggan" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp_pelanggan" class="form-control" required>
                </div>
            </div>

            <!-- Pilih Lapangan -->
            <h5>Pilih Lapangan & Jadwal</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Lapangan</label>
                    <select name="id_lapangan" id="id_lapangan" class="form-select" required>
                        <option value="">-- Pilih Lapangan --</option>
                        <?php foreach($lapanganList as $lap): ?>
                            <option value="<?= $lap['id'] ?>" 
                                <?= (isset($selectedLapangan) && $selectedLapangan['id'] == $lap['id']) ? 'selected' : '' ?>
                                data-harga="<?= $lap['harga_per_jam'] ?>">
                                <?= htmlspecialchars($lap['nama_lapangan']) ?> - Rp <?= number_format($lap['harga_per_jam'],0,',','.') ?>/jam
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tanggal Sewa</label>
                    <input type="date" name="tanggal_sewa" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Total Biaya (Rp)</label>
                    <input type="text" id="total_biaya_display" class="form-control" readonly disabled>
                </div>
            </div>

            <div class="alert alert-info">
                <strong>Langkah 1 dari 2:</strong> Selanjutnya Anda akan diminta mengupload bukti pembayaran.
            </div>

            <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
    const hargaSelect = document.getElementById('id_lapangan');
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');
    const totalDisplay = document.getElementById('total_biaya_display');

    function hitungTotal() {
        const harga = hargaSelect.options[hargaSelect.selectedIndex]?.getAttribute('data-harga');
        if (!harga || !jamMulai.value || !jamSelesai.value) {
            totalDisplay.value = '';
            return;
        }
        const start = jamMulai.value.split(':');
        const end = jamSelesai.value.split(':');
        let durasi = (parseInt(end[0]) + end[1]/60) - (parseInt(start[0]) + start[1]/60);
        if (durasi <= 0) {
            totalDisplay.value = 'Durasi tidak valid';
            return;
        }
        const total = durasi * parseFloat(harga);
        totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
    }

    hargaSelect.addEventListener('change', hitungTotal);
    jamMulai.addEventListener('change', hitungTotal);
    jamSelesai.addEventListener('change', hitungTotal);
</script>

>>>>>>> master
<?php include 'views/templates/footer.php'; ?>