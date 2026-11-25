-- MariaDB 10.4 / MySQL: backup DB dahulu, lakukan di staging.
-- Fix: UPDATE qty, jenis, atau id_barang harus membalik dampak OLD lalu menerapkan NEW.
-- Tidak mengubah data historis secara massal.
DELIMITER $$
DROP TRIGGER IF EXISTS `after_transaksi_update`$$
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
