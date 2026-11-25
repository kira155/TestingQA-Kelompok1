<?php
$pageTitle = 'Barang Keluar';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$barangList = $conn->query("
    SELECT id_barang, nama_barang, satuan, stok_sekarang
    FROM barang
    WHERE stok_sekarang > 0
    ORDER BY nama_barang
")->fetch_all(MYSQLI_ASSOC);

$riwayat = $conn->query("
    SELECT t.*, b.nama_barang, b.satuan, u.username
    FROM transaksi t
    JOIN barang b ON b.id_barang = t.id_barang
    JOIN users u ON u.id_user = t.id_user
    WHERE t.jenis = 'keluar'
    ORDER BY t.tanggal DESC, t.id_transaksi DESC
    LIMIT 100
")->fetch_all(MYSQLI_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$totalTransaksi = count($riwayat);
$totalQtyKeluar = 0;

foreach ($riwayat as $r) {
    $totalQtyKeluar += (int)$r['qty'];
}
?>

<style>
:root {
    --bg: #f4f7fb;
    --card: #ffffff;
    --text: #111827;
    --muted: #6b7280;
    --line: #e5eaf1;
    --danger: #dc2626;
    --danger-dark: #b91c1c;
    --danger-soft: #fee2e2;
    --warning-soft: #fef3c7;
}

body {
    background: var(--bg);
}

#main-wrapper,
#content {
    overflow-x: hidden;
}

#content {
    padding: 22px;
}

#topbar {
    background: rgba(255,255,255,.94);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--line);
    padding: 14px 22px;
    position: sticky;
    top: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
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
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 18px;
}

.summary-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 18px;
    padding: 16px;
    box-shadow: 0 8px 22px rgba(15,23,42,.05);
}

.summary-card small {
    display: block;
    color: var(--muted);
    font-weight: 800;
    margin-bottom: 6px;
}

.summary-card strong {
    font-size: 1.35rem;
    color: var(--text);
}

.modern-card {
    border: 1px solid var(--line);
    border-radius: 18px;
    background: var(--card);
    box-shadow: 0 10px 24px rgba(15,23,42,.06);
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

.alert {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 8px 18px rgba(15,23,42,.06);
}

.form-label {
    font-size: 0.84rem;
    font-weight: 800;
    color: #374151;
}

.form-control,
.form-select {
    border-radius: 14px;
    border: 1px solid #dfe5ee;
    font-size: 0.9rem;
    padding: 10px 12px;
    min-height: 44px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--danger);
    box-shadow: 0 0 0 .18rem rgba(220,38,38,.12);
}

.btn {
    border-radius: 12px;
    font-weight: 800;
    min-height: 42px;
}

.btn-danger {
    background: var(--danger);
    border-color: var(--danger);
}

.btn-danger:hover {
    background: var(--danger-dark);
    border-color: var(--danger-dark);
}

.btn-sm {
    border-radius: 999px;
    min-height: 34px;
}

.table-responsive-mobile {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    margin-bottom: 0;
    font-size: .88rem;
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

.badge-soft-danger {
    background: var(--danger-soft);
    color: #991b1b;
}

.empty-state {
    padding: 34px 15px;
    color: #9ca3af;
}

.stock-box {
    background: var(--danger-soft);
    color: #991b1b;
    border: 1px solid #fecaca;
    border-radius: 14px;
    padding: 11px 12px;
    font-weight: 900;
}

.note-text {
    max-width: 180px;
}

@media (max-width: 992px) {
    #content {
        padding: 18px;
    }
}

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
        margin-bottom: 14px;
    }

    .page-intro h5 {
        font-size: 1.05rem;
    }

    .page-intro small {
        font-size: .8rem;
    }

    .summary-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .summary-card {
        padding: 14px;
    }

    .modern-card {
        border-radius: 16px;
    }

    .modern-card .card-header {
        padding: 14px;
        font-size: .92rem;
    }

    .modern-card .card-body {
        padding: 14px;
    }

    .user-badge {
        padding: 6px 9px;
        font-size: .78rem;
        gap: 6px;
    }

    .role-badge {
        font-size: .65rem;
        padding: 3px 6px;
    }

    .table-responsive-mobile {
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
        background: #fff;
        margin: 12px;
        border-radius: 16px;
        border: 1px solid var(--line);
        padding: 14px;
        box-shadow: 0 6px 16px rgba(15,23,42,.045);
    }

    .table tbody td {
        border: none;
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

    .table tbody td.primary-cell {
        display: block;
        text-align: left !important;
        border-bottom: 1px solid #eef2f7;
        padding-bottom: 12px;
        margin-bottom: 6px;
    }

    .table tbody td.primary-cell::before {
        display: none;
    }

    .mobile-full-btn,
    .btn-sm {
        width: 100%;
        min-height: 42px;
        border-radius: 12px;
    }

    .empty-state {
        display: block !important;
        box-shadow: none !important;
        border: 0 !important;
        margin: 0 !important;
    }

    .empty-state td {
        display: block;
        text-align: center !important;
    }

    .empty-state td::before {
        display: none;
    }

    .note-text {
        max-width: 170px;
        white-space: normal;
        text-align: right;
    }

    .form-control,
    .form-select {
        font-size: .88rem;
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

    <div id="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="page-title">Barang Keluar</div>
        </div>

        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <span><?= htmlspecialchars($_SESSION['username']) ?></span>
            <span class="role-badge <?= isAdmin() ? 'bg-primary text-white' : 'bg-success text-white' ?>">
                <?= $_SESSION['role'] == 'admin' ? 'Admin' : (substr($_SESSION['role'], 0, 1) == 'u' ? 'User' : htmlspecialchars($_SESSION['role'])) ?>
            </span>
        </div>
    </div>

    <div id="content">

        <div class="page-intro">
            <h5 class="fw-bold mb-0">Barang Keluar</h5>
            <small>Input pemakaian barang dan pantau histori barang keluar.</small>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <small>Barang Tersedia</small>
                <strong><?= number_format(count($barangList)) ?></strong>
            </div>

            <div class="summary-card">
                <small>Total Transaksi Keluar</small>
                <strong><?= number_format($totalTransaksi) ?></strong>
            </div>

            <div class="summary-card">
                <small>Total Qty Keluar</small>
                <strong><?= number_format($totalQtyKeluar) ?></strong>
            </div>
        </div>

        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
            <i class="bi bi-<?= $flash['type'] == 'success' ? 'check-circle' : ($flash['type'] == 'danger' ? 'exclamation-triangle' : 'info-circle') ?> me-2"></i>
            <?= htmlspecialchars($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row g-3 g-lg-4">

            <div class="col-12 col-lg-4">
                <div class="card modern-card">
                    <div class="card-header text-danger">
                        <i class="bi bi-box-arrow-up me-2"></i>
                        Input Barang Keluar
                    </div>

                    <div class="card-body">
                        <form method="POST" action="/cleaning/actions/transaksi_action.php" id="formKeluar">
                <?= cleaning_csrf_field() ?>
                            <input type="hidden" name="action" value="input_transaksi">
                            <input type="hidden" name="jenis" value="keluar">

                            <div class="mb-3">
                                <label class="form-label">
                                    Barang <span class="text-danger">*</span>
                                </label>
                                <select name="id_barang" class="form-select" id="selectBarang" required>
                                    <option value="">Pilih barang</option>
                                    <?php foreach ($barangList as $b): ?>
                                    <option 
                                        value="<?= $b['id_barang'] ?>" 
                                        data-stok="<?= $b['stok_sekarang'] ?>"
                                        data-satuan="<?= htmlspecialchars($b['satuan'] ?? '') ?>">
                                        <?= htmlspecialchars($b['nama_barang']) ?> 
                                        | Stok: <?= number_format($b['stok_sekarang']) ?> <?= htmlspecialchars($b['satuan'] ?? '') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Stok Tersedia
                                </label>
                                <div class="stock-box" id="stokTersedia">-</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Jumlah Keluar <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="qty" 
                                    id="qtyKeluar" 
                                    class="form-control" 
                                    min="1" 
                                    placeholder="Masukkan jumlah keluar"
                                    required>

                                <div id="stokWarning" class="form-text text-danger d-none">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Qty keluar melebihi stok tersedia.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Tanggal <span class="text-danger">*</span>
                                </label>
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
                                    name="notes" 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="Contoh: pemakaian untuk cleaning lobby"></textarea>
                            </div>

                            <button type="submit" class="btn btn-danger w-100" id="btnSubmit">
                                <i class="bi bi-save me-1"></i>
                                Simpan Barang Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card modern-card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span>
                            <i class="bi bi-clock-history me-2 text-danger"></i>
                            Riwayat Barang Keluar
                        </span>
                        <span class="badge bg-secondary"><?= count($riwayat) ?></span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive-mobile">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Oleh</th>
                                        <th>Keterangan</th>
                                        <?php if (isAdmin()): ?>
                                        <th class="text-center">Aksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (empty($riwayat)): ?>
                                    <tr class="empty-state">
                                        <td colspan="<?= isAdmin() ? '6' : '5' ?>" class="text-center">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            Belum ada data barang keluar.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($riwayat as $t): ?>
                                        <tr>
                                            <td data-label="Tanggal" class="text-nowrap">
                                                <small><?= date('d/m/Y', strtotime($t['tanggal'])) ?></small>
                                            </td>

                                            <td data-label="Barang" class="primary-cell">
                                                <strong><?= htmlspecialchars($t['nama_barang']) ?></strong>
                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($t['satuan'] ?? '-') ?>
                                                </small>
                                            </td>

                                            <td data-label="Jumlah" class="text-center fw-bold">
                                                <span class="badge badge-soft-danger">
                                                    <?= number_format($t['qty']) ?> <?= htmlspecialchars($t['satuan'] ?? '') ?>
                                                </span>
                                            </td>

                                            <td data-label="Oleh" class="text-nowrap">
                                                <small><?= htmlspecialchars($t['username']) ?></small>
                                            </td>

                                            <td data-label="Keterangan" class="text-muted small">
                                                <span class="d-inline-block text-truncate note-text">
                                                    <?= htmlspecialchars($t['notes'] ?? '-') ?>
                                                </span>
                                            </td>

                                            <?php if (isAdmin()): ?>
                                            <td data-label="Aksi" class="text-center">
                                                <form 
                                                    method="POST" 
                                                    action="/cleaning/actions/transaksi_action.php"
                                                    onsubmit="return confirm('Hapus transaksi ini?')">
                <?= cleaning_csrf_field() ?>
                                                    <input type="hidden" name="action" value="hapus_transaksi">
                                                    <input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi'] ?>">
                                                    <input type="hidden" name="kembali" value="/cleaning/pages/transaksi_keluar.php">

                                                    <button class="btn btn-outline-danger btn-sm mobile-full-btn" type="submit">
                                                        <i class="bi bi-trash"></i>
                                                        <span class="d-inline d-md-none">Hapus</span>
                                                    </button>
                                                </form>
                                            </td>
                                            <?php endif; ?>
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

<script>
const selectBarang = document.getElementById('selectBarang');
const qtyKeluar = document.getElementById('qtyKeluar');
const stokWarning = document.getElementById('stokWarning');
const btnSubmit = document.getElementById('btnSubmit');
const stokTersedia = document.getElementById('stokTersedia');

function getSelectedData() {
    const selected = selectBarang.options[selectBarang.selectedIndex];

    return {
        stok: parseInt(selected?.dataset?.stok) || 0,
        satuan: selected?.dataset?.satuan || ''
    };
}

function updateStokInfo() {
    const data = getSelectedData();

    if (!selectBarang.value) {
        stokTersedia.innerHTML = '-';
        qtyKeluar.placeholder = 'Masukkan jumlah keluar';
        qtyKeluar.removeAttribute('max');
        return;
    }

    stokTersedia.innerHTML = new Intl.NumberFormat('id-ID').format(data.stok) + ' ' + data.satuan;
    qtyKeluar.max = data.stok;
    qtyKeluar.placeholder = 'Maksimal ' + data.stok;

    validateStok();
}

function validateStok() {
    const data = getSelectedData();
    const qty = parseInt(qtyKeluar.value) || 0;

    if (qty > data.stok && qty > 0) {
        stokWarning.classList.remove('d-none');
        btnSubmit.disabled = true;
        return false;
    }

    stokWarning.classList.add('d-none');
    btnSubmit.disabled = false;
    return true;
}

selectBarang.addEventListener('change', updateStokInfo);
qtyKeluar.addEventListener('input', validateStok);

document.getElementById('formKeluar').addEventListener('submit', function(e) {
    if (!validateStok()) {
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>