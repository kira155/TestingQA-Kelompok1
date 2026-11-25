-- BACA SAJA. Tidak menghapus/mengoreksi data. Jalankan di salinan database.
SELECT b.id_barang,b.nama_barang,b.stok_awal,b.stok_sekarang AS cached,
  b.stok_awal + COALESCE(SUM(CASE WHEN t.jenis='masuk' THEN t.qty ELSE -t.qty END),0) AS expected,
  b.stok_sekarang - (b.stok_awal + COALESCE(SUM(CASE WHEN t.jenis='masuk' THEN t.qty ELSE -t.qty END),0)) AS selisih
FROM barang b LEFT JOIN transaksi t ON t.id_barang=b.id_barang
GROUP BY b.id_barang,b.nama_barang,b.stok_awal,b.stok_sekarang
HAVING selisih <> 0 ORDER BY ABS(selisih) DESC;
