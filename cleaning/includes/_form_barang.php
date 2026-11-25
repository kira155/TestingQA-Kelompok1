<?php
// _form_barang.php - Form untuk tambah/edit barang dengan gambar
?>

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-tag"></i> Nama Barang
    </label>
    <input type="text" name="nama_barang" class="form-control" 
           value="<?= htmlspecialchars($b['nama_barang'] ?? '') ?>" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-tags"></i> Kategori
    </label>
    <select name="kategori" class="form-select" required>
        <option value="chemical" <?= (isset($b['kategori']) && $b['kategori'] == 'chemical') ? 'selected' : '' ?>>Chemical</option>
        <option value="peralatan" <?= (isset($b['kategori']) && $b['kategori'] == 'peralatan') ? 'selected' : '' ?>>Peralatan</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-cash"></i> Harga Barang
    </label>
    <div class="input-group">
        <span class="input-group-text">Rp</span>
        <input type="number" name="harga_barang" class="form-control" 
               value="<?= $b['harga_barang'] ?? 0 ?>" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-ruler"></i> Satuan
    </label>
    <select name="satuan" class="form-select" required>
        <option value="pcs" <?= (isset($b['satuan']) && $b['satuan'] == 'pcs') ? 'selected' : '' ?>>Pcs</option>
        <option value="liter" <?= (isset($b['satuan']) && $b['satuan'] == 'liter') ? 'selected' : '' ?>>Liter</option>
        <option value="kg" <?= (isset($b['satuan']) && $b['satuan'] == 'kg') ? 'selected' : '' ?>>Kg</option>
        <option value="botol" <?= (isset($b['satuan']) && $b['satuan'] == 'botol') ? 'selected' : '' ?>>Botol</option>
        <option value="pack" <?= (isset($b['satuan']) && $b['satuan'] == 'pack') ? 'selected' : '' ?>>Pack</option>
        <option value="unit" <?= (isset($b['satuan']) && $b['satuan'] == 'unit') ? 'selected' : '' ?>>Unit</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-archive"></i> Stok Awal
    </label>
    <input type="number" name="stok_awal" class="form-control" 
           value="<?= $b['stok_awal'] ?? 0 ?>" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-image"></i> Gambar Produk
    </label>
    <?php if (isset($b['gambar']) && $b['gambar']): ?>
        <div class="mb-2">
            <img src="/uploads/<?= $b['gambar'] ?>" 
                 class="img-thumbnail" 
                 style="max-height: 100px; border-radius: 10px;">
            <input type="hidden" name="gambar_lama" value="<?= $b['gambar'] ?>">
        </div>
    <?php endif; ?>
    <input type="file" name="gambar" class="form-control" accept="image/*"
           <?= !isset($b['gambar']) ? 'required' : '' ?>>
    <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
</div>