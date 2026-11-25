<?php
$pageTitle = 'Barang Masuk';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$daftarBarang = $conn->query("
    SELECT id_barang, nama_barang, satuan, harga_barang, stok_sekarang, kategori, gambar 
    FROM barang 
    ORDER BY nama_barang
")->fetch_all(MYSQLI_ASSOC);

$permintaanBelumTerpenuhi = $conn->query("
    SELECT 
        p.id_permintaan,
        p.id_barang,
        p.qty AS diminta,
        p.tanggal AS tgl_permintaan,
        p.keterangan AS keterangan_permintaan,
        b.nama_barang,
        b.satuan,
        b.harga_barang,
        b.stok_sekarang,
        b.kategori,
        b.gambar,
        u.username AS pemohon,
        COALESCE(SUM(CASE WHEN t.jenis = 'masuk' THEN t.qty ELSE 0 END), 0) AS sudah_masuk
    FROM permintaan p
    JOIN barang b ON b.id_barang = p.id_barang
    JOIN users u ON u.id_user = p.id_user
    LEFT JOIN transaksi t ON t.id_barang = p.id_barang 
        AND t.jenis = 'masuk'
        AND (t.notes = CONCAT('REQ-', p.id_permintaan) OR t.notes LIKE CONCAT('REQ-', p.id_permintaan, ' | %'))
    GROUP BY p.id_permintaan
    HAVING (diminta - sudah_masuk) > 0
    ORDER BY p.tanggal ASC
")->fetch_all(MYSQLI_ASSOC);

$riwayat = $conn->query("
    SELECT 
        t.*, 
        b.nama_barang, 
        b.satuan,
        b.kategori,
        b.harga_barang,
        b.stok_sekarang,
        b.gambar,
        u.username,
        p.id_permintaan,
        p.qty AS diminta
    FROM transaksi t
    JOIN barang b ON b.id_barang = t.id_barang
    JOIN users u ON u.id_user = t.id_user
    LEFT JOIN permintaan p ON (t.notes = CONCAT('REQ-', p.id_permintaan) OR t.notes LIKE CONCAT('REQ-', p.id_permintaan, ' | %'))
    WHERE t.jenis = 'masuk'
    ORDER BY t.id_transaksi DESC
    LIMIT 50
")->fetch_all(MYSQLI_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

function rupiah($angka) {
    if ($angka <= 0) return '-';
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function getGambarUrl($gambar) {
    if ($gambar && file_exists(__DIR__ . '/../uploads/' . $gambar)) {
        return '/cleaning/uploads/' . $gambar;
    }
    return '/cleaning/assets/img/no-image.png';
}

$totalPending = count($permintaanBelumTerpenuhi);
$totalRiwayat = count($riwayat);
$totalQtyPending = 0;

foreach ($permintaanBelumTerpenuhi as $x) {
    $totalQtyPending += max(0, ($x['diminta'] - $x['sudah_masuk']));
}
?>

<style>
:root {
    --bg: #f4f7fb;
    --card: #ffffff;
    --text: #111827;
    --muted: #6b7280;
    --line: #e5eaf1;
    --success: #198754;
    --success-soft: #ecfdf3;
    --warning-soft: #fef3c7;
    --danger-soft: #fee2e2;
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
    background: rgba(255, 255, 255, .94);
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
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
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

.alert {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
}

.table-responsive-mobile {
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

.badge-soft-success {
    background: #dcfce7;
    color: #166534;
}

.badge-soft-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-soft-info {
    background: #e0f2fe;
    color: #075985;
}

.barang-clickable {
    cursor: pointer;
    color: var(--text);
    font-weight: 800;
    transition: all 0.2s ease;
}

.barang-clickable:hover {
    color: var(--success);
}

.detail-icon {
    font-size: 0.78rem;
    margin-left: 4px;
    opacity: 0.55;
}

.btn {
    border-radius: 12px;
    font-weight: 700;
    min-height: 42px;
}

.btn-sm {
    border-radius: 999px;
    min-height: 34px;
}

.empty-state {
    padding: 34px 15px;
    color: #9ca3af;
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
    border-color: var(--success);
    box-shadow: 0 0 0 0.18rem rgba(25, 135, 84, 0.12);
}

.total-box {
    background: var(--success-soft);
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 12px 14px;
    font-weight: 900;
    color: #166534;
}

.modal-content {
    border: 0;
    border-radius: 22px;
    overflow: hidden;
}

.modal-header {
    border-bottom: 1px solid var(--line);
    padding: 18px 20px;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: 1px solid var(--line);
    padding: 16px 20px;
}

.modal-img {
    max-width: 100%;
    max-height: 210px;
    object-fit: cover;
    border-radius: 16px;
    border: 1px solid var(--line);
}

.detail-table td {
    padding: 9px 4px;
    color: #374151;
}

.detail-table td:first-child {
    color: var(--muted);
}

.info-box {
    border: 1px solid #bbf7d0;
    border-radius: 16px;
    background: var(--success-soft);
    color: #166534;
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
        font-size: 0.8rem;
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
        font-size: 0.92rem;
    }

    .modern-card .card-body {
        padding: 14px;
    }

    .user-badge {
        padding: 6px 9px;
        font-size: 0.78rem;
        gap: 6px;
    }

    .role-badge {
        font-size: 0.65rem;
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
        background: #ffffff;
        margin: 12px;
        border-radius: 16px;
        border: 1px solid var(--line);
        padding: 14px;
        box-shadow: 0 6px 16px rgba(15, 23, 42, .045);
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

    .btn-sm,
    .mobile-full-btn {
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

    .modal-dialog {
        margin: 10px;
    }

    .form-control,
    .form-select {
        font-size: 0.88rem;
    }
}

@media (max-width: 576px) {
    .row {
        --bs-gutter-x: 0.8rem;
        --bs-gutter-y: 0.8rem;
    }

    .user-badge span:first-of-type {
        max-width: 82px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modal-body {
        padding: 16px;
    }

    .modal-footer {
        flex-direction: column;
    }

    .modal-footer .btn {
        width: 100%;
    }
}
</style>

<div id="main-wrapper">

    <div id="topbar" class="d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="page-title">Barang Masuk</div>
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
            <h5 class="fw-bold mb-0">Barang Masuk</h5>
            <small>Proses permintaan barang yang belum terpenuhi dan pantau riwayat barang masuk.</small>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <small>Pending Request</small>
                <strong><?= number_format($totalPending) ?></strong>
            </div>

            <div class="summary-card">
                <small>Total Qty Pending</small>
                <strong><?= number_format($totalQtyPending) ?></strong>
            </div>

            <div class="summary-card">
                <small>Riwayat Barang Masuk</small>
                <strong><?= number_format($totalRiwayat) ?></strong>
            </div>
        </div>

        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'x-circle' ?> me-2"></i>
            <?= htmlspecialchars($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row g-3 g-lg-4">

            <div class="col-12 col-lg-5">
                <div class="card modern-card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span>
                            <i class="bi bi-clipboard-check text-success me-2"></i>
                            Permintaan Belum Terpenuhi
                        </span>
                        <span class="badge bg-success"><?= count($permintaanBelumTerpenuhi) ?></span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive-mobile">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th class="text-center">Diminta</th>
                                        <th class="text-center">Masuk</th>
                                        <th class="text-center">Sisa</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($permintaanBelumTerpenuhi)): ?>
                                    <tr class="empty-state">
                                        <td colspan="7" class="text-center">
                                            <i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i>
                                            Semua permintaan sudah terpenuhi.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($permintaanBelumTerpenuhi as $p): 
                                            $sisa = $p['diminta'] - $p['sudah_masuk'];
                                            $statusText = $p['sudah_masuk'] > 0 ? 'Partial' : 'Pending';
                                            $statusClass = $p['sudah_masuk'] > 0 ? 'badge-soft-info' : 'badge-soft-warning';
                                        ?>
                                        <tr>
                                            <td data-label="Tanggal" class="text-nowrap">
                                                <small><?= date('d/m/Y', strtotime($p['tgl_permintaan'])) ?></small>
                                            </td>

                                            <td data-label="Barang" class="primary-cell">
                                                <span 
                                                    class="barang-clickable"
                                                    data-id="<?= $p['id_barang'] ?>"
                                                    data-nama="<?= htmlspecialchars($p['nama_barang'], ENT_QUOTES) ?>"
                                                    data-satuan="<?= htmlspecialchars($p['satuan'] ?? '-', ENT_QUOTES) ?>"
                                                    data-harga="<?= rupiah($p['harga_barang']) ?>"
                                                    data-stok="<?= $p['stok_sekarang'] ?? 0 ?>"
                                                    data-kategori="<?= htmlspecialchars($p['kategori'] ?? '-', ENT_QUOTES) ?>"
                                                    data-gambar="<?= htmlspecialchars($p['gambar'] ?? '', ENT_QUOTES) ?>"
                                                    onclick="showDetailBarang(this)">
                                                    <?= htmlspecialchars($p['nama_barang']) ?>
                                                    <i class="bi bi-info-circle detail-icon"></i>
                                                </span>
                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($p['kategori'] ?? '-') ?> · <?= rupiah($p['harga_barang']) ?>
                                                </small>
                                            </td>

                                            <td data-label="Diminta" class="text-center fw-bold">
                                                <?= number_format($p['diminta']) ?>
                                            </td>

                                            <td data-label="Masuk" class="text-center">
                                                <?php if ($p['sudah_masuk'] > 0): ?>
                                                    <span class="badge badge-soft-success">
                                                        <?= number_format($p['sudah_masuk']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <td data-label="Sisa" class="text-center">
                                                <span class="badge badge-soft-warning">
                                                    <?= number_format($sisa) ?>
                                                </span>
                                            </td>

                                            <td data-label="Status" class="text-center">
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </td>

                                            <td data-label="Aksi" class="text-center">
                                                <button 
                                                    type="button"
                                                    class="btn btn-success btn-sm mobile-full-btn btnProsesMasuk"
                                                    data-id="<?= $p['id_permintaan'] ?>"
                                                    data-barang="<?= htmlspecialchars($p['nama_barang'], ENT_QUOTES) ?>"
                                                    data-diminta="<?= $p['diminta'] ?>"
                                                    data-sudah-masuk="<?= $p['sudah_masuk'] ?>"
                                                    data-sisa="<?= $sisa ?>"
                                                    data-satuan="<?= htmlspecialchars($p['satuan'] ?? '', ENT_QUOTES) ?>"
                                                    data-harga="<?= $p['harga_barang'] ?>"
                                                    data-pemohon="<?= htmlspecialchars($p['pemohon'], ENT_QUOTES) ?>">
                                                    <i class="bi bi-box-arrow-in-down"></i>
                                                    <span>Proses</span>
                                                </button>
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

            <div class="col-12 col-lg-7">
                <div class="card modern-card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span>
                            <i class="bi bi-clock-history text-success me-2"></i>
                            Riwayat Barang Masuk
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
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Total</th>
                                        <th>Dari</th>
                                        <th>Input Oleh</th>
                                        <?php if (isAdmin()): ?>
                                            <th class="text-center">Aksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($riwayat)): ?>
                                    <tr class="empty-state">
                                        <td colspan="<?= isAdmin() ? 7 : 6 ?>" class="text-center">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            Belum ada riwayat barang masuk.
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($riwayat as $t): ?>
                                        <tr>
                                            <td data-label="Tanggal" class="text-nowrap">
                                                <?= date('d/m/Y', strtotime($t['tanggal'])) ?>
                                            </td>

                                            <td data-label="Barang" class="primary-cell">
                                                <span 
                                                    class="barang-clickable"
                                                    data-id="<?= $t['id_barang'] ?>"
                                                    data-nama="<?= htmlspecialchars($t['nama_barang'], ENT_QUOTES) ?>"
                                                    data-satuan="<?= htmlspecialchars($t['satuan'] ?? '-', ENT_QUOTES) ?>"
                                                    data-harga="<?= rupiah($t['harga_barang']) ?>"
                                                    data-stok="<?= $t['stok_sekarang'] ?? 0 ?>"
                                                    data-kategori="<?= htmlspecialchars($t['kategori'] ?? '-', ENT_QUOTES) ?>"
                                                    data-gambar="<?= htmlspecialchars($t['gambar'] ?? '', ENT_QUOTES) ?>"
                                                    onclick="showDetailBarang(this)">
                                                    <?= htmlspecialchars($t['nama_barang']) ?>
                                                    <i class="bi bi-info-circle detail-icon"></i>
                                                </span>
                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($t['kategori'] ?? '-') ?>
                                                </small>
                                            </td>

                                            <td data-label="Qty" class="text-center fw-bold text-success">
                                                <?= number_format($t['qty']) ?>
                                            </td>

                                            <td data-label="Total" class="text-center fw-bold text-nowrap">
                                                <?= rupiah($t['total_harga'] ?? 0) ?>
                                            </td>

                                            <td data-label="Dari">
                                                <?php if ($t['id_permintaan']): ?>
                                                    <span class="badge badge-soft-info">
                                                        REQ-<?= $t['id_permintaan'] ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <td data-label="Input Oleh" class="text-nowrap">
                                                <?= htmlspecialchars($t['username']) ?>
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
                                                    <input type="hidden" name="kembali" value="/cleaning/pages/transaksi_masuk.php">
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

<div class="modal fade" id="modalProsesMasuk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h5 class="modal-title text-success fw-bold">
                    <i class="bi bi-box-arrow-in-down me-2"></i>
                    Proses Barang Masuk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="/cleaning/actions/permintaan_action.php">
                <?= cleaning_csrf_field() ?>
                <input type="hidden" name="action" value="catat_masuk">
                <input type="hidden" name="id_permintaan" id="modalIdPermintaan">

                <div class="modal-body">
                    <div class="alert info-box mb-3">
                        <strong id="modalNamaBarang"></strong><br>
                        <small>Pemohon: <span id="modalPemohon"></span></small><br>
                        <small>Diminta: <span id="modalDiminta"></span> <span id="modalSatuan"></span></small><br>
                        <small>Sudah masuk: <span id="modalSudahMasuk"></span></small><br>
                        <small>Harga/unit: <span id="modalHarga"></span></small><br>
                        <small class="fw-bold text-warning">Sisa: <span id="modalSisa"></span></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Jumlah Masuk <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="qty_masuk" 
                            id="modalQtyMasuk" 
                            class="form-control" 
                            min="1" 
                            step="1" 
                            required>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle"></i>
                            Maksimal sisa belum masuk: <strong id="maxSisa">0</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Nilai</label>
                        <div class="total-box" id="modalTotalNilai">-</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Masuk</label>
                        <input 
                            type="date" 
                            name="tanggal_masuk" 
                            class="form-control" 
                            value="<?= date('Y-m-d') ?>" 
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea 
                            name="notes" 
                            class="form-control" 
                            rows="3" 
                            placeholder="Contoh: Barang sudah diterima"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        Simpan Barang Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailBarang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h5 class="modal-title text-primary fw-bold">
                    <i class="bi bi-box-seam me-2"></i>
                    Detail Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="text-center mb-3">
                    <img 
                        id="detailGambar" 
                        src="/cleaning/assets/img/no-image.png" 
                        alt="Foto Barang" 
                        class="modal-img">
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
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetailBarang(element) {
    const nama = element.dataset.nama || '-';
    const satuan = element.dataset.satuan || '-';
    const harga = element.dataset.harga || '-';
    const stok = element.dataset.stok || '0';
    const kategori = element.dataset.kategori || '-';
    const gambar = element.dataset.gambar || '';

    document.getElementById('detailNamaBarang').textContent = nama;
    document.getElementById('detailKategori').textContent = kategori;
    document.getElementById('detailSatuan').textContent = satuan;
    document.getElementById('detailHarga').textContent = harga;
    document.getElementById('detailStok').textContent = stok;

    const imgElement = document.getElementById('detailGambar');

    if (gambar !== '') {
        imgElement.src = '/cleaning/uploads/' + gambar;
        imgElement.onerror = function() {
            this.src = '/cleaning/assets/img/no-image.png';
        };
    } else {
        imgElement.src = '/cleaning/assets/img/no-image.png';
    }

    new bootstrap.Modal(document.getElementById('modalDetailBarang')).show();
}

document.querySelectorAll('.btnProsesMasuk').forEach(btn => {
    btn.addEventListener('click', function() {
        const sisa = parseInt(this.dataset.sisa) || 0;
        const harga = parseInt(this.dataset.harga) || 0;

        document.getElementById('modalIdPermintaan').value = this.dataset.id;
        document.getElementById('modalNamaBarang').textContent = this.dataset.barang;
        document.getElementById('modalPemohon').textContent = this.dataset.pemohon;
        document.getElementById('modalDiminta').textContent = this.dataset.diminta;
        document.getElementById('modalSudahMasuk').textContent = this.dataset.sudahMasuk;
        document.getElementById('modalSisa').textContent = sisa;
        document.getElementById('modalSatuan').textContent = this.dataset.satuan;
        document.getElementById('modalHarga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
        document.getElementById('maxSisa').textContent = sisa;

        const qtyInput = document.getElementById('modalQtyMasuk');
        const totalNilaiElement = document.getElementById('modalTotalNilai');

        qtyInput.value = '';
        qtyInput.placeholder = 'Maksimal ' + sisa;
        qtyInput.max = sisa;
        qtyInput.min = 1;
        totalNilaiElement.innerHTML = '-';

        qtyInput.oninput = function() {
            let val = parseInt(this.value) || 0;

            if (val > sisa) {
                val = sisa;
                this.value = val;
            }

            totalNilaiElement.innerHTML = val >= 1
                ? 'Rp ' + new Intl.NumberFormat('id-ID').format(val * harga)
                : '-';
        };

        new bootstrap.Modal(document.getElementById('modalProsesMasuk')).show();
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>