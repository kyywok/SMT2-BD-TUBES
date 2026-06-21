<?php include 'views/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-success text-white">Konfirmasi & Pembayaran</div>
    <div class="card-body">
        <h5>Ringkasan Pesanan Anda</h5>
        <table class="table table-bordered">
            <tr><th width="30%">Lapangan</th><td><?= htmlspecialchars($booking['nama_lapangan']) ?></td></tr>
            <tr><th>Nama Pemesan</th><td><?= htmlspecialchars($booking['nama_pelanggan']) ?></td></tr>
            <tr><th>Email / No. Telp</th><td><?= htmlspecialchars($booking['email_pelanggan']) ?> / <?= htmlspecialchars($booking['no_telp_pelanggan']) ?></td></tr>
            <tr><th>Tanggal Sewa</th><td><?= date('d-m-Y', strtotime($booking['tanggal_sewa'])) ?></td></tr>
            <tr><th>Jam</th><td><?= substr($booking['jam_mulai'],0,5) ?> - <?= substr($booking['jam_selesai'],0,5) ?> (<?= $booking['durasi_jam'] ?> jam)</td></tr>
            <tr><th>Total Biaya</th><td><strong class="text-danger">Rp <?= number_format($booking['total_biaya'],0,',','.') ?></strong></td></tr>
        </table>

        <hr>
        <h5>Pilih Metode Pembayaran</h5>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="metode_bayar" id="bca" value="1" checked>
                <label class="form-check-label" for="bca">
                    Transfer BCA
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="metode_bayar" id="qris" value="2">
                <label class="form-check-label" for="qris">
                    QRIS (Scan QR Code)
                </label>
            </div>
        </div>

        <!-- Informasi pembayaran dinamis -->
        <div id="infoBCA" class="alert alert-info">
            <strong>Transfer ke Rekening BCA:</strong><br>
            Bank BCA<br>
            No. Rekening: 1234567890<br>
            Atas nama: ARENA SPORT<br>
            <small>Setelah transfer, upload bukti transfer di bawah.</small>
        </div>
        <div id="infoQRIS" class="alert alert-success" style="display: none;">
            <strong>Scan QR Code berikut untuk membayar menggunakan QRIS:</strong><br>
            <img src="assets/img/Qris1.jpeg" alt="QRIS Code" style="width: 200px; height: auto; margin-top: 10px;"><br>
            <small>Setelah scan dan pembayaran sukses, upload bukti transfer (screenshot QRIS) di bawah.</small>
        </div>

        <hr>
        <h5>Upload Bukti Transfer</h5>
        <form action="index.php?controller=booking&action=processPayment" method="POST" enctype="multipart/form-data">
            <!-- Kirim metode bayar yang dipilih -->
            <input type="hidden" name="metode_bayar" id="metode_bayar_hidden" value="1">
            <div class="mb-3">
                <label>Bukti Transfer (foto/PDF)</label>
                <input type="file" name="bukti_transfer" class="form-control" accept="image/*,application/pdf" required>
                <small class="text-muted">Upload bukti transfer sesuai instruksi di atas.</small>
            </div>
            <button type="submit" class="btn btn-success">Selesaikan Booking</button>
            <a href="index.php?controller=booking&action=create" class="btn btn-secondary">Kembali Edit</a>
        </form>
    </div>
</div>

<script>
    // Ambil elemen radio
    const radioBCA = document.getElementById('bca');
    const radioQRIS = document.getElementById('qris');
    const infoBCA = document.getElementById('infoBCA');
    const infoQRIS = document.getElementById('infoQRIS');
    const hiddenMethod = document.getElementById('metode_bayar_hidden');

    function togglePaymentInfo() {
        if (radioBCA.checked) {
            infoBCA.style.display = 'block';
            infoQRIS.style.display = 'none';
            hiddenMethod.value = '1';
        } else if (radioQRIS.checked) {
            infoBCA.style.display = 'none';
            infoQRIS.style.display = 'block';
            hiddenMethod.value = '2';
        }
    }

    radioBCA.addEventListener('change', togglePaymentInfo);
    radioQRIS.addEventListener('change', togglePaymentInfo);
    // Inisialisasi tampilan awal
    togglePaymentInfo();
</script>

<?php include 'views/templates/footer.php'; ?>