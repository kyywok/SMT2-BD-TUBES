-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 21 Jun 2026 pada 11.23
-- Versi server: 12.2.2-MariaDB
-- Versi PHP: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_lapangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id` int(15) NOT NULL,
  `id_pelanggan` int(15) NOT NULL,
  `id_lapangan` int(15) NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `total_biaya` decimal(15,2) DEFAULT NULL,
  `status_booking` tinyint(4) DEFAULT 1 COMMENT '1=Menunggu, 2=Dikonfirmasi, 3=Dibatalkan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id`, `id_pelanggan`, `id_lapangan`, `tanggal_sewa`, `jam_mulai`, `jam_selesai`, `total_biaya`, `status_booking`) VALUES
(17, 14, 2, '2026-06-18', '12:00:00', '13:00:00', 90000.00, 2),
(18, 15, 3, '2026-06-20', '20:00:00', '22:00:00', 150000.00, 2),
(19, 16, 1, '2026-06-19', '20:00:00', '21:00:00', 100000.00, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapangan`
--

CREATE TABLE `lapangan` (
  `id` int(15) NOT NULL,
  `nama_lapangan` varchar(100) NOT NULL,
  `jenis_lapangan` tinyint(4) NOT NULL COMMENT '1=Badminton, 2=Futsal',
  `harga_per_jam` decimal(15,2) NOT NULL,
  `status_lapangan` tinyint(4) DEFAULT 1 COMMENT '1=Tersedia, 0=Tidak Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data untuk tabel `lapangan`
--

INSERT INTO `lapangan` (`id`, `nama_lapangan`, `jenis_lapangan`, `harga_per_jam`, `status_lapangan`) VALUES
(1, 'A1', 2, 100000.00, 1),
(2, 'A2', 2, 90000.00, 1),
(3, 'B1', 1, 75000.00, 1),
(14, 'B2', 1, 65000.00, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(15) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `email_pelanggan` varchar(255) NOT NULL,
  `no_telp_pelanggan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama_pelanggan`, `email_pelanggan`, `no_telp_pelanggan`) VALUES
(14, 'jeki bin messi', 'jekianjai@gmail.com', '09876'),
(15, 'popopo', 'popo@gmail.com', '098724344'),
(16, 'Aqillah Zakky', 'aqillahzakkyramadana@gmail.com', '098768484');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(15) NOT NULL,
  `id_booking` int(15) NOT NULL,
  `jumlah_bayar` decimal(15,2) DEFAULT NULL,
  `metode_bayar` tinyint(4) DEFAULT NULL COMMENT '1=Transfer, 2=Tunai',
  `bukti_transfer` varchar(100) DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_booking`, `jumlah_bayar`, `metode_bayar`, `bukti_transfer`, `tanggal_bayar`) VALUES
(4, 17, 90000.00, 2, '1781665986_Capture.PNG', NULL),
(5, 18, 150000.00, 1, '1781852421_PDM SS.PNG', NULL),
(6, 19, 100000.00, 2, '1781858149_WhatsApp Image 2025-09-28 at 15.26.48_ce7048e8.jpg', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(15) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `created_at`) VALUES
(1, 'Admin Utama', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', '2026-06-15 10:07:02');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_lapangan` (`id_lapangan`),
  ADD KEY `fk_booking_guest` (`id_pelanggan`);

--
-- Indeks untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_booking` (`id_booking`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_guest` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `fk_booking_lapangan` FOREIGN KEY (`id_lapangan`) REFERENCES `lapangan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_bayar_booking` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
