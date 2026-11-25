-- OPTIONAL: recommended only after backup, foreign-key inspection, and staging verification.
-- Preserve transaction ledger even if an application bug permits deletion of the parent.
-- Existing `ON DELETE CASCADE` otherwise destroys audit trail.
ALTER TABLE `transaksi` DROP FOREIGN KEY `transaksi_ibfk_1`;
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`)
  REFERENCES `barang` (`id_barang`) ON DELETE RESTRICT;
