<?php
$pageTitle = 'Permintaan Barang';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$barangList = $conn->query("
    SELECT id_barang, nama_barang, satuan, harga_barang, stok_sekarang, kategori
    FROM barang 
    ORDER BY nama_barang
")->fetch_all(MYSQLI_ASSOC);

$riwayat = $conn->query("
    SELECT 
        p.id_permintaan,
        p.id_barang,
        p.id_user,
        p.qty AS diminta,
        p.estimasi_harga,
        p.estimasi_total,
        p.tanggal,
        p.keterangan,
        b.nama_barang, 
        b.satuan, 
        b.harga_barang,
        b.stok_sekarang,
        b.kategori,
        u.username,
        COALESCE(SUM(CASE WHEN t.jenis = 'masuk' THEN t.qty ELSE 0 END), 0) AS sudah_masuk,
        COALESCE(SUM(CASE WHEN t.jenis = 'masuk' THEN t.total_harga ELSE 0 END), 0) AS nilai_sudah_masuk
    FROM permintaan p
    JOIN barang b ON b.id_barang = p.id_barang
    JOIN users u ON u.id_user = p.id_user
    LEFT JOIN transaksi t ON t.id_barang = p.id_barang 
        AND t.jenis = 'masuk'
        AND (t.notes = CONCAT('REQ-', p.id_permintaan) OR t.notes LIKE CONCAT('REQ-', p.id_permintaan, ' | %'))
    GROUP BY p.id_permintaan
    ORDER BY p.id_permintaan DESC
    LIMIT 60
")->fetch_all(MYSQLI_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

function rupiah($angka) {
    if ($angka <= 0) return '-';
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

$totalRequest = count($riwayat);
$totalQtyDiminta = array_sum(array_column($riwayat, 'diminta'));
$totalQtyMasuk = array_sum(array_column($riwayat, 'sudah_masuk'));
$totalSisa = $totalQtyDiminta - $totalQtyMasuk;
?>

<style>
:root {
    --bg: #f4f7fb;
    --card: #ffffff;
    --text: #111827;
    --muted: #6b7280;
    --line: #e5eaf1;
    --primary: #2563eb;
    --primary-soft: #eff6ff;
    --success: #16a34a;
    --warning: #f59e0b;
    --danger: #dc2626;
    --radius: 18px;
}

body {
    background: var(--bg);
    color: var(--text);
}

#main-wrapper,
#content {
    overflow-x: hidden;
}

#content {
    padding: 22px;
}

#topbar {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--line);
    padding: 14px 22px;
    position: sticky;
    top: 0;
    z-index: 50;
}

.page-title {
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--text);
}

.user-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid var(--line);
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 0.88rem;
    color: #374151;
}

.role-badge {
    border-radius: 999px;
    padding: 4px 8px;
    font-size: 0.72rem;
}

.page-intro {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}

.page-intro h5 {
    color: var(--text);
    font-size: 1.25rem;
}

.page-intro small {
    color: var(--muted);
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.summary-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 14px 16px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, .045);
}

.summary-card small {
    color: var(--muted);
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
}

.summary-card strong {
    font-size: 1.2rem;
    color: var(--text);
}

.modern-card {
    border: 1px solid var(--line);
    border-radius: var(--radius);
    background: var(--card);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

.modern-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid var(--line);
    padding: 16px 18px;
    font-weight: 800;
    color: var(--text);
}

.modern-card .card-body {
    padding: 18px;
}

.form-label {
    font-size: 0.82rem;
    font-weight: 800;
    color: #374151;
    margin-bottom: 6px;
}

.form-control,
.form-select {
    border-radius: 14px;
    border: 1px solid #dbe3ee;
    font-size: 0.92rem;
    padding: 11px 12px;
    min-height: 44px;
}

textarea.form-control {
    min-height: 92px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.12);
}

.estimasi-box {
    background: var(--primary-soft);
    border: 1px solid #bfdbfe;
    border-radius: 14px;
    padding: 12px 14px;
    font-weight: 900;
    color: var(--primary);
    font-size: 1rem;
}

.btn-primary {
    border-radius: 14px;
    font-weight: 800;
    padding: 11px 14px;
    min-height: 44px;
    background: var(--primary);
    border-color: var(--primary);
}

.alert {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
}

.table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    margin-bottom: 0;
    font-size: 0.88rem;
}

.table thead th {
    background: #f8fafc;
    color: var(--muted);
    font-weight: 800;
    border-bottom: 1px solid var(--line);
    white-space: nowrap;
    padding: 13px 14px;
}

.table tbody td {
    padding: 14px;
    vertical-align: middle;
    color: #374151;
    border-bottom: 1px solid #f1f5f9;
}

.table-hover tbody tr:hover {
    background-color: #f8fafc;
}

.badge {
    border-radius: 999px;
    font-weight: 800;
    padding: 6px 9px;
}

.badge-masuk {
    background: #dcfce7;
    color: #166534;
}

.badge-open {
    background: #fee2e2;
    color: #991b1b;
}

.badge-partial {
    background: #fef3c7;
    color: #92400e;
}

.badge-done {
    background: #dcfce7;
    color: #166534;
}

.progress-custom {
    height: 6px;
    width: 70px;
    display: inline-block;
    margin-left: 6px;
    background: #edf2f7;
    border-radius: 999px;
    vertical-align: middle;
}

.progress-custom .progress-bar {
    border-radius: 999px;
    background: var(--success);
}

.barang-clickable {
    cursor: pointer;
    color: var(--text);
    font-weight: 800;
    transition: all 0.2s ease;
}

.barang-clickable:hover {
    color: var(--primary);
}

.detail-icon {
    font-size: 0.82rem;
    margin-left: 4px;
    opacity: 0.55;
}

.note-text {
    max-width: 180px;
}

.empty-state {
    padding: 38px 15px;
    color: #9ca3af;
}

.modal-content {
    border: 0;
    border-radius: 20px;
    overflow: hidden;
}

.modal-header {
    border-bottom: 0;
}

.modal-footer {
    border-top: 1px solid var(--line);
}

.detail-table td {
    padding: 9px 4px;
    color: #374151;
}

.detail-table td:first-child {
    color: var(--muted);
}

/* Mobile Card Table */
@media (max-width: 768px) {
    #topbar {
        padding: 12px 14px;
    }

    #content {
        padding: 12px;
    }

    .page-title {
        font-size: 1rem;
    }

    .page-intro {
        display: block;
        margin-bottom: 14px;
    }

    .page-intro h5 {
        font-size: 1.05rem;
    }

    .page-intro small {
        font-size: 0.8rem;
    }

    .summary-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .summary-card {
        padding: 12px 14px;
    }

    .modern-card {
        border-radius: 16px;
    }

    .modern-card .card-header {
        padding: 14px;
        font-size: 0.92rem;
    }

    .modern-card .card-body {
        padding: 14px;
    }

    .form-control,
    .form-select {
        font-size: 0.9rem;
    }

    .user-badge {
        padding: 6px 9px;
        font-size: 0.78rem;
    }

    .table-wrap {
        overflow: visible;
    }

    .table,
    .table thead,
    .table tbody,
    .table th,
    .table td,
    .table tr {
        display: block;
        width: 100%;
    }

    .table thead {
        display: none;
    }

    .table tbody tr {
        background: #ffffff;
        border: 1px solid var(--line);
        border-radius: 16px;
        margin: 12px;
        padding: 12px;
        box-shadow: 0 6px 16px rgba(15, 23, 42, .045);
    }

    .table tbody td {
        border: 0;
        padding: 8px 0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        text-align: right !important;
    }

    .table tbody td::before {
        content: attr(data-label);
        font-weight: 800;
        color: var(--muted);
        text-align: left;
        flex: 0 0 38%;
    }

    .table tbody td[data-primary="true"] {
        display: block;
        text-align: left !important;
        border-bottom: 1px solid #eef2f7;
        padding-bottom: 12px;
        margin-bottom: 4px;
    }

    .table tbody td[data-primary="true"]::before {
        display: none;
    }

    .note-text {
        max-width: 170px;
        white-space: normal;
        text-align: right;
    }

    .progress-custom {
        width: 55px;
    }

    .empty-state {
        display: block !important;
        border: 0 !important;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .empty-state td {
        display: block;
        text-align: center !important;
    }

    .empty-state td::before {
        display: none;
    }

    .modal-dialog {
        margin: 10px;
    }
}

@media (max-width: 576px) {
    .row {
        --bs-gutter-x: .8rem;
        --bs-gutter-y: .8rem;
    }

    .user-badge span:first-of-type {
        max-width: 82px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>

<div id="main-wrapper">

    <div id="topbar" class="d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="page-title">Permintaan Barang</div>
        </div>

        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <span>
                <?= htmlspecialchars($_SESSION['username']) ?>
            </span>
            <span class="role-badge <?= isAdmin() ? 'bg-primary text-white' : 'bg-success text-white' ?>">
                <?= $_SESSION['role'] == 'admin' ? 'Admin' : 'User' ?>
            </span>
        </div>
    </div>

    <div id="content">

        <div class="page-intro">
            <div>
                <h5 class="fw-bold mb-0">Permintaan Barang</h5>
                <small>Buat request barang dan monitor progress barang masuk.</small>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <small>Total Request</small>
                <strong><?= number_format($totalRequest) ?></strong>
            </div>
            <div class="summary-card">
                <small>Total Qty Diminta</small>
                <strong><?= number_format($totalQtyDiminta) ?></strong>
            </div>
            <div class="summary-card">
                <small>Sisa Belum Masuk</small>
                <strong><?= number_format($totalSisa) ?></strong>
            </div>
        </div>

        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show mb-3">
            <i class="bi bi-<?= $flash['type'] == 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= htmlspecialchars($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row g-3 g-lg-4">

            <div class="col-12 col-lg-4">
                <div class="card modern-card">
                    <div class="card-header">
                        <i class="bi bi-plus-circle text-primary me-2"></i>
                        Buat Permintaan Baru
                    </div>

                    <div class="card-body">
                        <form method="POST" action="/cleaning/actions/permintaan_action.php">
                <?= cleaning_csrf_field() ?>
                            <input type="hidden" name="action" value="buat_permintaan">

                            <div class="mb-3">
                                <label class="form-label">
                                    Pilih Barang <span class="text-danger">*</span>
                                </label>
                                <select name="id_barang" id="selectBarang" class="form-select" required>
                                    <option value="">Pilih barang</option>
                                    <?php foreach ($barangList as $b): ?>
                                    <option 
                                        value="<?= $b['id_barang'] ?>"
                                        data-harga="<?= $b['harga_barang'] ?>"
                                        data-satuan="<?= htmlspecialchars($b['satuan'] ?? '-') ?>"
                                        data-stok="<?= $b['stok_sekarang'] ?>"
                                        data-kategori="<?= htmlspecialchars($b['kategori'] ?? '-') ?>">
                                        <?= htmlspecialchars($b['nama_barang']) ?> 
                                        | <?= htmlspecialchars($b['satuan'] ?? '-') ?> 
                                        | <?= rupiah($b['harga_barang']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Jumlah Diminta <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="qty" 
                                    id="qtyDiminta" 
                                    class="form-control" 
                                    min="1" 
                                    placeholder="Contoh: 10"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estimasi Biaya</label>
                                <div class="estimasi-box" id="estimasiBiaya">-</div>
                                <small class="text-muted">Mengikuti harga barang saat ini.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Permintaan</label>
                                <input 
                                    type="date" 
                                    name="tanggal" 
                                    class="form-control" 
                                    value="<?= date('Y-m-d') ?>" 
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Keterangan</label>
                                <textarea 
                                    name="keterangan" 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="Tambahkan catatan jika diperlukan"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-1"></i>
                                Kirim Permintaan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card modern-card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span>
                            <i class="bi bi-list-check me-2 text-primary"></i>
                            Riwayat Permintaan
                        </span>
                        <span class="badge bg-secondary"><?= count($riwayat) ?></span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-wrap">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center">Diminta</th>
                                        <th class="text-center">Estimasi</th>
                                        <th class="text-center">Sudah Masuk</th>
                                        <th class="text-center">Sisa</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Pemohon</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (empty($riwayat)): ?>
                                    <tr class="empty-state">
                                        <td colspan="9" class="text-center">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            Belum ada permintaan.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($riwayat as $p): 
                                            $sisa = $p['diminta'] - $p['sudah_masuk'];
                                            $progress = $p['diminta'] > 0 ? ($p['sudah_masuk'] / $p['diminta']) * 100 : 0;
                                            $progress = min(100, max(0, $progress));

                                            if ($sisa <= 0) {
                                                $statusText = 'Complete';
                                                $statusBadge = 'badge-done';
                                                $statusClass = 'text-success';
                                            } elseif ($p['sudah_masuk'] > 0) {
                                                $statusText = 'Partial';
                                                $statusBadge = 'badge-partial';
                                                $statusClass = 'text-warning';
                                            } else {
                                                $statusText = 'Open';
                                                $statusBadge = 'badge-open';
                                                $statusClass = 'text-danger';
                                            }
                                        ?>
                                        <tr>
                                            <td data-label="Tanggal" class="text-nowrap">
                                                <small><?= date('d/m/Y', strtotime($p['tanggal'])) ?></small>
                                            </td>

                                            <td data-label="Barang" data-primary="true">
                                                <span 
                                                    class="barang-clickable"
                                                    data-id="<?= $p['id_barang'] ?>"
                                                    data-nama="<?= htmlspecialchars($p['nama_barang'], ENT_QUOTES) ?>"
                                                    data-satuan="<?= htmlspecialchars($p['satuan'] ?? '-', ENT_QUOTES) ?>"
                                                    data-harga="<?= rupiah($p['harga_barang']) ?>"
                                                    data-stok="<?= $p['stok_sekarang'] ?>"
                                                    data-kategori="<?= htmlspecialchars($p['kategori'] ?? '-', ENT_QUOTES) ?>"
                                                    onclick="showDetailBarang(this)">
                                                    <?= htmlspecialchars($p['nama_barang']) ?>
                                                    <i class="bi bi-info-circle detail-icon"></i>
                                                </span>
                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($p['kategori'] ?? '-') ?> · <?= htmlspecialchars($p['satuan'] ?? '-') ?>
                                                </small>
                                            </td>

                                            <td data-label="Diminta" class="text-center fw-bold">
                                                <?= number_format($p['diminta']) ?>
                                            </td>

                                            <td data-label="Estimasi" class="text-center text-nowrap">
                                                <small class="text-muted">
                                                    <?= rupiah($p['estimasi_total']) ?>
                                                </small>
                                            </td>

                                            <td data-label="Sudah Masuk" class="text-center text-nowrap">
                                                <span class="badge badge-masuk">
                                                    <?= number_format($p['sudah_masuk']) ?>
                                                </span>

                                                <div class="progress progress-custom">
                                                    <div 
                                                        class="progress-bar" 
                                                        style="width: <?= $progress ?>%">
                                                    </div>
                                                </div>
                                            </td>

                                            <td data-label="Sisa" class="text-center <?= $statusClass ?> fw-bold">
                                                <?= number_format(max(0, $sisa)) ?>
                                            </td>

                                            <td data-label="Status">
                                                <span class="badge <?= $statusBadge ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </td>

                                            <td data-label="Keterangan" class="text-muted small">
                                                <span class="d-inline-block text-truncate note-text">
                                                    <?= htmlspecialchars($p['keterangan'] ?: '-') ?>
                                                </span>
                                            </td>

                                            <td data-label="Pemohon" class="text-nowrap">
                                                <small><?= htmlspecialchars($p['username']) ?></small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailBarang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-box-seam me-2"></i>
                    Detail Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-cube fs-1 text-primary"></i>
                </div>

                <table class="table table-borderless detail-table">
                    <tr>
                        <td style="width: 38%"><strong>Nama Barang</strong></td>
                        <td>: <span id="detailNamaBarang">-</span></td>
                    </tr>
                    <tr>
                        <td><strong>Kategori</strong></td>
                        <td>: <span id="detailKategori">-</span></td>
                    </tr>
                    <tr>
                        <td><strong>Satuan</strong></td>
                        <td>: <span id="detailSatuan">-</span></td>
                    </tr>
                    <tr>
                        <td><strong>Harga Barang</strong></td>
                        <td>: <span id="detailHarga">-</span></td>
                    </tr>
                    <tr>
                        <td><strong>Stok Saat Ini</strong></td>
                        <td>: <span id="detailStok">-</span></td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function hitungEstimasi() {
    const select = document.getElementById('selectBarang');
    const qtyInput = document.getElementById('qtyDiminta');
    const estimasiElement = document.getElementById('estimasiBiaya');

    if (!select || !qtyInput || !estimasiElement) return;

    const qty = parseInt(qtyInput.value) || 0;
    const selectedOption = select.options[select.selectedIndex];
    const harga = parseInt(selectedOption?.dataset?.harga) || 0;
    const estimasi = qty * harga;

    estimasiElement.innerHTML = estimasi > 0
        ? 'Rp ' + new Intl.NumberFormat('id-ID').format(estimasi)
        : '-';
}

document.getElementById('selectBarang')?.addEventListener('change', hitungEstimasi);
document.getElementById('qtyDiminta')?.addEventListener('input', hitungEstimasi);

function showDetailBarang(element) {
    document.getElementById('detailNamaBarang').textContent = element.dataset.nama || '-';
    document.getElementById('detailKategori').textContent = element.dataset.kategori || '-';
    document.getElementById('detailSatuan').textContent = element.dataset.satuan || '-';
    document.getElementById('detailHarga').textContent = element.dataset.harga || '-';
    document.getElementById('detailStok').textContent = element.dataset.stok || '0';

    new bootstrap.Modal(document.getElementById('modalDetailBarang')).show();
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>