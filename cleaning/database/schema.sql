-- Skema demonstrasi kosong untuk instalasi lokal. Jangan import ke database produksi.
-- Buat database cleaning_service pada server lokal dan pilih database tersebut terlebih dahulu.
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cleaning_service`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` enum('chemical','peralatan') NOT NULL,
  `harga_barang` int(11) DEFAULT 0,
  `satuan` varchar(50) DEFAULT NULL,
  `stok_awal` int(11) DEFAULT 0,
  `stok_sekarang` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tidak ada data operasional pada skema demo
--

-- --------------------------------------------------------

--
-- Table structure for table `permintaan`
--

CREATE TABLE `permintaan` (
  `id_permintaan` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `estimasi_harga` int(11) DEFAULT 0,
  `estimasi_total` int(11) DEFAULT 0,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tidak ada data operasional pada skema demo
--

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL DEFAULT 0,
  `total_harga` int(11) NOT NULL DEFAULT 0,
  `tanggal` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tidak ada data operasional pada skema demo
--

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `after_transaksi_delete` AFTER DELETE ON `transaksi` FOR EACH ROW BEGIN
    IF OLD.jenis = 'masuk' THEN
        UPDATE barang SET stok_sekarang = stok_sekarang - OLD.qty 
        WHERE id_barang = OLD.id_barang;
    ELSEIF OLD.jenis = 'keluar' THEN
        UPDATE barang SET stok_sekarang = stok_sekarang + OLD.qty 
        WHERE id_barang = OLD.id_barang;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_transaksi_insert` AFTER INSERT ON `transaksi` FOR EACH ROW BEGIN
    IF NEW.jenis = 'masuk' THEN
        UPDATE barang SET stok_sekarang = stok_sekarang + NEW.qty 
        WHERE id_barang = NEW.id_barang;
    ELSEIF NEW.jenis = 'keluar' THEN
        UPDATE barang SET stok_sekarang = stok_sekarang - NEW.qty 
        WHERE id_barang = NEW.id_barang;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_transaksi_update` AFTER UPDATE ON `transaksi`
FOR EACH ROW BEGIN
    UPDATE barang
       SET stok_sekarang = stok_sekarang -
           (CASE WHEN OLD.jenis = 'masuk' THEN OLD.qty ELSE -OLD.qty END)
     WHERE id_barang = OLD.id_barang;
    UPDATE barang
       SET stok_sekarang = stok_sekarang +
           (CASE WHEN NEW.jenis = 'masuk' THEN NEW.qty ELSE -NEW.qty END)
     WHERE id_barang = NEW.id_barang;
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff') DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tidak ada data operasional pada skema demo
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `permintaan`
--
ALTER TABLE `permintaan`
  ADD PRIMARY KEY (`id_permintaan`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_id_barang` (`id_barang`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `fk_user` (`id_user`),
  ADD KEY `idx_jenis_tanggal` (`jenis`,`tanggal`),
  ADD KEY `idx_id_barang_jenis` (`id_barang`,`jenis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permintaan`
--
ALTER TABLE `permintaan`
  MODIFY `id_permintaan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

