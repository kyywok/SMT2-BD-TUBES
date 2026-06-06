<?php include 'views/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-success text-white">Konfirmasi & Pembayaran</div>
    <div class="card-body">
        <h5>Ringkasan Pesanan Anda</h5>
        <table class="table table-bordered">
            <tr>
                <th width="30%">Lapangan</th>
                <td><?= htmlspecialchars($booking['nama_lapangan']) ?></td>
            </tr>
            <tr>
                <th>Nama Pemesan</th>
                <td><?= htmlspecialchars($booking['nama_pelanggan']) ?></td>
            </tr>
            <tr>
                <th>Email / No. Telp</th>
                <td><?= htmlspecialchars($booking['email_pelanggan']) ?> / <?= htmlspecialchars($booking['no_telp_pelanggan']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Sewa</th>
                <td><?= date('d-m-Y', strtotime($booking['tanggal_sewa'])) ?></td>
            </tr>
            <tr>
                <th>Jam</th>
                <td><?= substr($booking['jam_mulai'],0,5) ?> - <?= substr($booking['jam_selesai'],0,5) ?> (<?= $booking['durasi_jam'] ?> jam)</td>
            </tr>
            <tr>
                <th>Total Biaya</th>
                <td><strong class="text-danger">Rp <?= number_format($booking['total_biaya'],0,',','.') ?></strong></td>
            </tr>
        </table>

        <hr>
        <h5>Upload Bukti Transfer</h5>
        <form action="index.php?controller=booking&action=processPayment" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Bukti Transfer (foto/PDF)</label>
                <input type="file" name="bukti_transfer" class="form-control" accept="image/*,application/pdf" required>
                <small class="text-muted">Transfer ke rekening BCA 1234567890 a.n. Arena Sport. Upload bukti dengan jelas.</small>
            </div>
            <button type="submit" class="btn btn-success">Selesaikan Booking</button>
            <a href="index.php?controller=booking&action=create" class="btn btn-secondary">Kembali Edit</a>
        </form>
    </div>
</div>
<?php include 'views/templates/footer.php'; ?>