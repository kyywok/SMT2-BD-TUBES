<?php include 'views/templates/header.php'; ?>
<?php if (!isset($_SESSION['admin_logged_in'])): ?>
    <div class="alert alert-danger">Anda harus login sebagai admin.</div>
<?php else: ?>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Booking Menunggu Konfirmasi</h5>
                <h3><?= $jumlahMenunggu ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Total Pendapatan Lunas</h5>
                <h3>Rp <?= number_format($totalPendapatan,0,',','.') ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Booking</h2>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th><th>Pelanggan</th><th>Lapangan</th><th>Tanggal</th><th>Jam</th><th>Total</th><th>Status Booking</th><th>Bukti</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($bookings as $b): ?>
        <tr>
            <td><?= $b['id'] ?></td>
            <td><?= htmlspecialchars($b['nama_pelanggan']) ?><br><small><?= $b['email_pelanggan'] ?></small></td>
            <td><?= htmlspecialchars($b['nama_lapangan']) ?></td>
            <td><?= date('d-m-Y', strtotime($b['tanggal_sewa'])) ?></td>
            <td><?= substr($b['jam_mulai'],0,5) ?> - <?= substr($b['jam_selesai'],0,5) ?></td>
            <td>Rp <?= number_format($b['total_biaya'],0,',','.') ?></td>
            <td>
                <?php
                    if ($b['status_booking'] == 1) echo '<span class="badge bg-warning">Menunggu</span>';
                    elseif ($b['status_booking'] == 2) echo '<span class="badge bg-success">Lunas</span>';
                    else echo '<span class="badge bg-danger">Batal</span>';
                ?>
            </td>
            <td>
                <?php if($b['bukti_transfer']): ?>
                    <a href="uploads/<?= $b['bukti_transfer'] ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td>
                <form action="index.php?controller=booking&action=updateStatus" method="POST" class="d-inline">
                    <input type="hidden" name="id_booking" value="<?= $b['id'] ?>">
                    <select name="status_booking" class="form-select form-select-sm d-inline w-auto">
                        <option value="1" <?= $b['status_booking']==1?'selected':'' ?>>Menunggu</option>
                        <option value="2" <?= $b['status_booking']==2?'selected':'' ?>>Lunas</option>
                        <option value="3" <?= $b['status_booking']==3?'selected':'' ?>>Batal</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php include 'views/templates/footer.php'; ?>