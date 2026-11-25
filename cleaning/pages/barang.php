<?php
$pageTitle = 'Kelola Barang';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/auth.php';
requireAdmin();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$barangList = $conn->query("
    SELECT b.*,
        b.stok_awal + COALESCE(t.total_masuk,0) - COALESCE(t.total_keluar,0) AS stok_sekarang,
        COALESCE(t.total_masuk,0) AS total_masuk,
        COALESCE(t.total_keluar,0) AS total_keluar,
        COALESCE(p.total_diminta,0) AS total_diminta
    FROM barang b
    LEFT JOIN (
        SELECT id_barang,
            SUM(CASE WHEN jenis = 'masuk' THEN qty ELSE 0 END) AS total_masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN qty ELSE 0 END) AS total_keluar
        FROM transaksi GROUP BY id_barang
    ) t ON t.id_barang = b.id_barang
    LEFT JOIN (
        SELECT id_barang, SUM(qty) AS total_diminta
        FROM permintaan GROUP BY id_barang
    ) p ON p.id_barang = b.id_barang
    ORDER BY b.kategori, b.nama_barang
")->fetch_all(MYSQLI_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

function rupiah($angka) {
    if ($angka <= 0) return '-';
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function getProductImage($gambar, $kategori, $nama_barang) {
    if (!empty($gambar) && $gambar !== 'null') {
        $upload_path = __DIR__ . '/../uploads/' . $gambar;

        if (file_exists($upload_path)) {
            return '/cleaning/uploads/' . $gambar;
        }
    }

    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 24 24' fill='%23667eea'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 13c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z'/%3E%3C/svg%3E";
}
?>

<style>
.stock-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.stock-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.stock-safe {
    background: #d1fae5;
    color: #059669;
}

.stock-mid {
    background: #dbeafe;
    color: #3b82f6;
}

.stock-low {
    background: #fef3c7;
    color: #d97706;
}

.stock-critical,
.stock-out {
    background: #fee2e2;
    color: #dc2626;
}

.product-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 10px;
    background: #f3f4f6;
    transition: transform 0.3s;
}

.product-thumb:hover {
    transform: scale(1.5);
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 1000;
    position: relative;
}

.table-hover tbody tr:hover {
    background: linear-gradient(90deg, #f9fafb 0%, #ffffff 100%);
    cursor: pointer;
}

.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 35px rgba(0,0,0,0.1);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px 20px 0 0;
    padding: 1rem 1.5rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 700;
}

.form-control,
.form-select {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary,
.btn-outline-info,
.btn-outline-danger {
    border-radius: 10px;
    transition: all 0.3s;
}

.btn-outline-primary:hover,
.btn-outline-info:hover,
.btn-outline-danger:hover {
    transform: translateY(-2px);
}

.alert {
    border-radius: 15px;
    border: none;
    background: white;
    border-left: 4px solid;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.alert-success {
    border-left-color: #10b981;
}

.alert-danger {
    border-left-color: #ef4444;
}

.badge-chemical {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-peralatan {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.harga-badge {
    background: #dcfce7;
    color: #15803d;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.detail-image {
    max-width: 200px;
    max-height: 200px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }

    .table {
        min-width: 1100px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 3px;
    }

    .btn-sm {
        padding: 2px 6px;
        font-size: 0.7rem;
    }

    .product-thumb {
        width: 40px;
        height: 40px;
    }
}
</style>

<div id="main-wrapper">
    <div id="topbar">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div class="page-title">
            <i class="bi bi-box-seam me-2"></i>Kelola Barang
        </div>

        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <?= htmlspecialchars($_SESSION['username']) ?>
            <span class="role-badge bg-primary text-white">admin</span>
        </div>
    </div>

    <div id="content">

        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show shadow-sm">
            <i class="bi bi-<?= $flash['type'] == 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= htmlspecialchars($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-2">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Barang</div>
                            <div class="h4 mb-0 fw-bold"><?= count($barangList) ?></div>
                        </div>
                        <i class="bi bi-boxes fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-2">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Stok</div>
                            <div class="h4 mb-0 fw-bold text-success">
                                <?= number_format(array_sum(array_column($barangList, 'stok_sekarang'))) ?>
                            </div>
                        </div>
                        <i class="bi bi-cubes fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-2">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Diminta</div>
                            <div class="h4 mb-0 fw-bold text-warning">
                                <?= number_format(array_sum(array_column($barangList, 'total_diminta'))) ?>
                            </div>
                        </div>
                        <i class="bi bi-clipboard-check fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-2">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Stok Menipis</div>
                            <div class="h4 mb-0 fw-bold text-danger">
                                <?= count(array_filter($barangList, fn($b) => $b['stok_sekarang'] < 10 && $b['stok_sekarang'] > 0)) ?>
                            </div>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small">
                    <i class="bi bi-info-circle"></i> Total <?= count($barangList) ?> item
                </span>
            </div>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i>Tambah Barang
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-tags me-2 text-primary"></i>Daftar Barang
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Stok Awal</th>
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Diminta</th>
                                <th>Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($barangList as $i => $b): 
                            $stok = $b['stok_sekarang'];
                            $statusClass = $stok <= 0 ? 'stock-out' : ($stok < 5 ? 'stock-critical' : ($stok < 10 ? 'stock-low' : ($stok < 20 ? 'stock-mid' : 'stock-safe')));
                            $statusIcon = $stok <= 0 ? 'bi-emoji-frown' : ($stok < 5 ? 'bi-skull' : ($stok < 10 ? 'bi-exclamation-triangle' : 'bi-check-circle'));
                            $gambar = getProductImage($b['gambar'] ?? '', $b['kategori'], $b['nama_barang']);
                        ?>
                            <tr class="slide-in">
                                <td class="text-center text-muted small fw-bold">
                                    <?= $i + 1 ?>
                                </td>

                                <td class="text-center">
                                    <img 
                                        src="<?= $gambar ?>" 
                                        class="product-thumb" 
                                        alt="<?= htmlspecialchars($b['nama_barang']) ?>"
                                        onclick="showImageModal('<?= $gambar ?>', '<?= htmlspecialchars($b['nama_barang']) ?>')">
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium"><?= htmlspecialchars($b['nama_barang']) ?></span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge-<?= $b['kategori'] === 'chemical' ? 'chemical' : 'peralatan' ?>">
                                        <?= $b['kategori'] === 'chemical' ? 'Chemical' : 'Peralatan' ?>
                                    </span>
                                </td>

                                <td class="text-center fw-semibold">
                                    <?= number_format($b['stok_awal']) ?>
                                </td>

                                <td class="text-center">
                                    <div class="stock-indicator justify-content-center">
                                        <i class="bi <?= $statusIcon ?> me-1"></i>
                                        <span class="fw-bold <?= $stok <= 0 ? 'text-danger' : ($stok < 5 ? 'text-danger' : ($stok < 10 ? 'text-warning' : 'text-success')) ?>">
                                            <?= number_format($stok) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="stock-badge <?= $statusClass ?>">
                                            <?= $stok <= 0 ? 'Habis' : ($stok < 5 ? 'Kritis' : ($stok < 10 ? 'Menipis' : ($stok < 20 ? 'Cukup' : 'Aman'))) ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="text-center text-muted small">
                                    <?= htmlspecialchars($b['satuan'] ?? '-') ?>
                                </td>

                                <td class="text-center">
                                    <span class="harga-badge">
                                        <?= rupiah($b['harga_barang']) ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clipboard"></i>
                                        <?= number_format($b['total_diminta']) ?>
                                    </span>
                                </td>

                                <td class="text-muted small">
                                    <i class="bi bi-calendar3"></i>
                                    <?= date('d M Y', strtotime($b['created_at'])) ?>
                                </td>

                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button 
                                            class="btn btn-sm btn-outline-info me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetail"
                                            data-id="<?= $b['id_barang'] ?>"
                                            data-nama="<?= htmlspecialchars($b['nama_barang']) ?>"
                                            data-kategori="<?= $b['kategori'] ?>"
                                            data-harga="<?= $b['harga_barang'] ?>"
                                            data-satuan="<?= htmlspecialchars($b['satuan'] ?? '') ?>"
                                            data-gambar="<?= $gambar ?>"
                                            data-gambar_file="<?= $b['gambar'] ?? '' ?>"
                                            data-stok_awal="<?= $b['stok_awal'] ?>"
                                            data-stok_sekarang="<?= $b['stok_sekarang'] ?>"
                                            data-total_masuk="<?= $b['total_masuk'] ?>"
                                            data-total_keluar="<?= $b['total_keluar'] ?>"
                                            data-total_diminta="<?= $b['total_diminta'] ?>"
                                            data-created_at="<?= date('d M Y H:i', strtotime($b['created_at'])) ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button 
                                            class="btn btn-sm btn-outline-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit"
                                            data-id="<?= $b['id_barang'] ?>"
                                            data-nama="<?= htmlspecialchars($b['nama_barang']) ?>"
                                            data-kategori="<?= $b['kategori'] ?>"
                                            data-harga="<?= $b['harga_barang'] ?>"
                                            data-satuan="<?= htmlspecialchars($b['satuan'] ?? '') ?>"
                                            data-stok_awal="<?= $b['stok_awal'] ?>"
                                            data-gambar_lama="<?= $b['gambar'] ?? '' ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form 
                                            method="POST" 
                                            action="/cleaning/actions/barang_action.php"
                                            style="display:inline"
                                            onsubmit="return confirm('Yakin hapus barang ini? Data transaksi dan permintaan terkait akan terpengaruh!')">
                <?= cleaning_csrf_field() ?>
                                            <input type="hidden" name="action" value="hapus_barang">
                                            <input type="hidden" name="id_barang" value="<?= $b['id_barang'] ?>">
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/cleaning/actions/barang_action.php" enctype="multipart/form-data">
                <?= cleaning_csrf_field() ?>
                <input type="hidden" name="action" value="tambah_barang">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Barang Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="chemical">Chemical</option>
                            <option value="peralatan">Peralatan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Barang</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_barang" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan</label>
                        <select name="satuan" class="form-select" required>
                            <option value="pcs">Pcs</option>
                            <option value="liter">Liter</option>
                            <option value="kg">Kg</option>
                            <option value="botol">Botol</option>
                            <option value="pack">Pack</option>
                            <option value="unit">Unit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok Awal</label>
                        <input type="number" name="stok_awal" class="form-control" value="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/cleaning/actions/barang_action.php" enctype="multipart/form-data">
                <?= cleaning_csrf_field() ?>
                <input type="hidden" name="action" value="edit_barang">
                <input type="hidden" name="id_barang" id="editId">
                <input type="hidden" name="gambar_lama" id="editGambarLama">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Barang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" id="editNama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" id="editKategori" class="form-select" required>
                            <option value="chemical">Chemical</option>
                            <option value="peralatan">Peralatan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Barang</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_barang" id="editHarga" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan</label>
                        <select name="satuan" id="editSatuan" class="form-select" required>
                            <option value="pcs">Pcs</option>
                            <option value="liter">Liter</option>
                            <option value="kg">Kg</option>
                            <option value="botol">Botol</option>
                            <option value="pack">Pack</option>
                            <option value="unit">Unit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok Awal</label>
                        <input type="number" name="stok_awal" id="editStokAwal" class="form-control" required>
                        <small class="text-warning">⚠️ Perubahan stok awal mempengaruhi stok saat ini.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Produk</label>
                        <div id="editGambarPreview" class="mb-2"></div>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Detail Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="detailGambar" class="detail-image">
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Nama Barang</label>
                                    <div class="fw-semibold fs-5" id="detailNama"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Kategori</label>
                                    <div id="detailKategori"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Harga</label>
                                    <div class="fw-semibold text-success" id="detailHarga"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Satuan</label>
                                    <div id="detailSatuan"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Stok Awal</label>
                                    <div class="fw-semibold" id="detailStokAwal"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Stok Saat Ini</label>
                                    <div class="fw-bold fs-4" id="detailStokSekarang"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Total Masuk</label>
                                    <div class="text-success fw-semibold" id="detailMasuk"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Total Keluar</label>
                                    <div class="text-warning fw-semibold" id="detailKeluar"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Total Diminta</label>
                                    <div class="text-info fw-semibold" id="detailDiminta"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">Dibuat Pada</label>
                                    <div class="small" id="detailCreated"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="previewImage" style="width: 100%; border-radius: 10px;">
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(src, title) {
    document.getElementById('previewImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

document.getElementById('modalEdit').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;

    document.getElementById('editId').value = btn.dataset.id;
    document.getElementById('editNama').value = btn.dataset.nama;
    document.getElementById('editKategori').value = btn.dataset.kategori;
    document.getElementById('editHarga').value = btn.dataset.harga;
    document.getElementById('editSatuan').value = btn.dataset.satuan;
    document.getElementById('editStokAwal').value = btn.dataset.stokAwal;
    document.getElementById('editGambarLama').value = btn.dataset.gambarLama || '';

    const previewDiv = document.getElementById('editGambarPreview');

    if (btn.dataset.gambarLama && btn.dataset.gambarLama !== 'null') {
        previewDiv.innerHTML = `
            <div class="mb-2">
                <img src="/cleaning/uploads/${btn.dataset.gambarLama}" style="max-height: 100px; border-radius: 10px;">
                <small class="d-block text-muted">Gambar saat ini</small>
            </div>
        `;
    } else {
        previewDiv.innerHTML = '<small class="text-muted">Belum ada gambar</small>';
    }
});

document.getElementById('modalDetail').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    const stok = parseInt(btn.dataset.stokSekarang);

    let statusClass = '';
    let statusText = '';

    if (stok <= 0) {
        statusClass = 'text-danger';
        statusText = 'Habis';
    } else if (stok < 5) {
        statusClass = 'text-danger';
        statusText = 'Kritis';
    } else if (stok < 10) {
        statusClass = 'text-warning';
        statusText = 'Menipis';
    } else if (stok < 20) {
        statusClass = 'text-success';
        statusText = 'Cukup';
    } else {
        statusClass = 'text-success';
        statusText = 'Aman';
    }

    let gambarUrl = btn.dataset.gambar;

    if (btn.dataset.gambarFile && btn.dataset.gambarFile !== 'null') {
        gambarUrl = '/cleaning/uploads/' + btn.dataset.gambarFile;
    }

    document.getElementById('detailGambar').src = gambarUrl;
    document.getElementById('detailNama').innerHTML = btn.dataset.nama;

    document.getElementById('detailKategori').innerHTML = `
        <span class="badge-${btn.dataset.kategori === 'chemical' ? 'chemical' : 'peralatan'}">
            ${btn.dataset.kategori === 'chemical' ? 'Chemical' : 'Peralatan'}
        </span>
    `;

    document.getElementById('detailHarga').innerHTML =
        `Rp ${new Intl.NumberFormat('id-ID').format(btn.dataset.harga)}`;

    document.getElementById('detailSatuan').innerHTML = btn.dataset.satuan || '-';

    document.getElementById('detailStokAwal').innerHTML =
        `${new Intl.NumberFormat('id-ID').format(btn.dataset.stokAwal)} ${btn.dataset.satuan || 'unit'}`;

    document.getElementById('detailStokSekarang').innerHTML = `
        <span class="${statusClass} fw-bold">
            ${new Intl.NumberFormat('id-ID').format(btn.dataset.stokSekarang)} ${btn.dataset.satuan || 'unit'}
            <small class="badge ${statusClass === 'text-danger' ? 'bg-danger' : (statusClass === 'text-warning' ? 'bg-warning' : 'bg-success')} text-white ms-2">
                ${statusText}
            </small>
        </span>
    `;

    document.getElementById('detailMasuk').innerHTML =
        `${new Intl.NumberFormat('id-ID').format(btn.dataset.totalMasuk)} ${btn.dataset.satuan || 'unit'}`;

    document.getElementById('detailKeluar').innerHTML =
        `${new Intl.NumberFormat('id-ID').format(btn.dataset.totalKeluar)} ${btn.dataset.satuan || 'unit'}`;

    document.getElementById('detailDiminta').innerHTML =
        `${new Intl.NumberFormat('id-ID').format(btn.dataset.totalDiminta)} ${btn.dataset.satuan || 'unit'}`;

    document.getElementById('detailCreated').innerHTML = btn.dataset.createdAt;
});

document.querySelectorAll('tbody tr').forEach((row, index) => {
    row.style.animation = `slideIn 0.3s ease ${index * 0.05}s both`;
});

if (!document.querySelector('#slideInStyle')) {
    const style = document.createElement('style');
    style.id = 'slideInStyle';
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;
    document.head.appendChild(style);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>