<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$sqlStok = "
    SELECT
        b.id_barang,
        b.nama_barang,
        b.kategori,
        b.satuan,
        b.stok_awal,
        COALESCE(SUM(CASE WHEN t.jenis = 'masuk' THEN t.qty ELSE 0 END), 0) AS total_masuk,
        COALESCE(SUM(CASE WHEN t.jenis = 'keluar' THEN t.qty ELSE 0 END), 0) AS total_keluar,
        (
            b.stok_awal
            + COALESCE(SUM(CASE WHEN t.jenis = 'masuk' THEN t.qty ELSE 0 END), 0)
            - COALESCE(SUM(CASE WHEN t.jenis = 'keluar' THEN t.qty ELSE 0 END), 0)
        ) AS stok_sekarang
    FROM barang b
    LEFT JOIN transaksi t ON t.id_barang = b.id_barang
    GROUP BY b.id_barang
";
$resStok = $conn->query($sqlStok);
$barangList = $resStok->fetch_all(MYSQLI_ASSOC);

$totalBarang = count($barangList);
$stokHabis = 0;
$stokMenipis = 0;
$stokCukup = 0;

foreach ($barangList as $b) {
    if ($b['stok_sekarang'] <= 0) {
        $stokHabis++;
    } elseif ($b['stok_sekarang'] <= 4) {
        $stokMenipis++;
    } else {
        $stokCukup++;
    }
}

$today = date('Y-m-d');
$stmtHari = $conn->prepare("SELECT COUNT(*) AS total FROM transaksi WHERE tanggal = ?");
$stmtHari->bind_param("s", $today);
$stmtHari->execute();
$totalTransHari = $stmtHari->get_result()->fetch_assoc()['total'] ?? 0;
$stmtHari->close();

$bulan = date('Y-m');
$sqlBulan = "
    SELECT
        SUM(CASE WHEN jenis = 'masuk' THEN qty ELSE 0 END) AS masuk_bulan,
        SUM(CASE WHEN jenis = 'keluar' THEN qty ELSE 0 END) AS keluar_bulan
    FROM transaksi
    WHERE DATE_FORMAT(tanggal, '%Y-%m') = ?
";
$stmtBulan = $conn->prepare($sqlBulan);
$stmtBulan->bind_param("s", $bulan);
$stmtBulan->execute();
$dataBulan = $stmtBulan->get_result()->fetch_assoc();
$stmtBulan->close();

$sqlRecent = "
    SELECT t.*, b.nama_barang, b.satuan, u.username
    FROM transaksi t
    JOIN barang b ON b.id_barang = t.id_barang
    JOIN users u ON u.id_user = t.id_user
    ORDER BY t.id_transaksi DESC
    LIMIT 10
";
$recentTrans = $conn->query($sqlRecent)->fetch_all(MYSQLI_ASSOC);

$kritis = array_filter($barangList, fn($b) => $b['stok_sekarang'] >= 0 && $b['stok_sekarang'] <= 4);
?>

<style>
body {
    background: #f6f8fb;
}

#main-wrapper,
#content {
    overflow-x: hidden;
}

#content {
    padding: 22px;
}

#topbar {
    background: #ffffff;
    border-bottom: 1px solid #e9edf3;
    padding: 14px 22px;
    position: sticky;
    top: 0;
    z-index: 50;
}

.page-title {
    font-weight: 700;
    font-size: 1.15rem;
    color: #1f2937;
}

.user-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f3f6fa;
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

.dashboard-title {
    margin-bottom: 18px;
}

.dashboard-title h5 {
    color: #111827;
}

.dashboard-title small {
    color: #6b7280;
}

.stat-card {
    border: 0;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    transition: all 0.22s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09);
}

.stat-card .card-body {
    padding: 18px;
}

.icon-box {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.82rem;
    margin-bottom: 2px;
}

.stat-value {
    color: #111827;
    font-size: 1.45rem;
    font-weight: 800;
    line-height: 1.2;
}

.card {
    border-radius: 18px;
}

.table-card {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
}

.table-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #edf1f7;
    padding: 16px 18px;
    font-weight: 700;
    color: #111827;
}

.table-responsive-mobile {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.table thead th {
    background: #f9fafb;
    color: #6b7280;
    font-weight: 700;
    border-bottom: 1px solid #edf1f7;
    white-space: nowrap;
    padding: 13px 14px;
}

.table tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    color: #374151;
}

.table-hover tbody tr:hover {
    background-color: #f8fafc;
}

.badge {
    border-radius: 999px;
    font-weight: 600;
    padding: 6px 9px;
}

.bg-orange-soft {
    background: rgba(253, 126, 20, 0.12);
    color: #fd7e14;
}

.bg-primary-soft {
    background: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
}

.bg-success-soft {
    background: rgba(25, 135, 84, 0.12);
    color: #198754;
}

.bg-danger-soft {
    background: rgba(220, 53, 69, 0.12);
    color: #dc3545;
}

.bg-warning-soft {
    background: rgba(255, 193, 7, 0.18);
    color: #b58100;
}

.bg-info-soft {
    background: rgba(13, 202, 240, 0.14);
    color: #0aa2c0;
}

.empty-state {
    padding: 35px 15px;
    color: #9ca3af;
}

.text-truncate-note {
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 992px) {
    #content {
        padding: 18px;
    }

    .stat-card .card-body {
        padding: 15px;
    }

    .icon-box {
        width: 44px;
        height: 44px;
        min-width: 44px;
        font-size: 1.2rem;
    }

    .stat-value {
        font-size: 1.25rem;
    }
}

@media (max-width: 768px) {
    #topbar {
        padding: 12px 14px;
    }

    #content {
        padding: 14px;
    }

    .page-title {
        font-size: 1rem;
    }

    .dashboard-title {
        margin-bottom: 14px;
    }

    .dashboard-title h5 {
        font-size: 1rem;
    }

    .dashboard-title small {
        font-size: 0.78rem;
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

    .stat-card {
        border-radius: 15px;
    }

    .stat-card .card-body {
        padding: 13px;
        gap: 10px !important;
    }

    .icon-box {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        font-size: 1.05rem;
    }

    .stat-label {
        font-size: 0.72rem;
    }

    .stat-value {
        font-size: 1.12rem;
    }

    .table-card {
        border-radius: 15px;
    }

    .table-card .card-header {
        padding: 13px 14px;
        font-size: 0.9rem;
    }

    .table {
        min-width: 720px;
        font-size: 0.8rem;
    }

    .table thead th,
    .table tbody td {
        padding: 10px 11px;
    }

    .badge {
        font-size: 0.7rem;
        padding: 5px 7px;
    }
}

@media (max-width: 576px) {
    #content {
        padding: 12px;
    }

    .row {
        --bs-gutter-x: 0.7rem;
        --bs-gutter-y: 0.7rem;
    }

    .stat-card .card-body {
        flex-direction: row;
        align-items: center;
    }

    .icon-box {
        width: 36px;
        height: 36px;
        min-width: 36px;
        font-size: 0.95rem;
    }

    .stat-label {
        font-size: 0.68rem;
    }

    .stat-value {
        font-size: 1rem;
    }

    .text-truncate-note {
        max-width: 120px;
    }
}
</style>

<div id="main-wrapper">

    <div id="topbar" class="d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="page-title">Dashboard</div>
        </div>

        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <span class="d-none d-sm-inline">
                <?= htmlspecialchars($_SESSION['username']) ?>
            </span>
            <span class="d-inline d-sm-none">
                <?= htmlspecialchars(substr($_SESSION['username'], 0, 10)) ?>
            </span>
            <span class="role-badge <?= isAdmin() ? 'bg-primary text-white' : 'bg-success text-white' ?>">
                <?= $_SESSION['role'] == 'admin' ? 'Admin' : 'User' ?>
            </span>
        </div>
    </div>

    <div id="content">

        <div class="dashboard-title">
            <h5 class="fw-bold mb-0">Ringkasan Sistem</h5>
            <small>Monitoring stok barang dan transaksi secara real-time</small>
        </div>

        <div class="row mb-3 mb-md-4">
            <div class="col-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-primary-soft">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Barang</div>
                            <div class="stat-value"><?= number_format($totalBarang) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-danger-soft">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Stok Habis</div>
                            <div class="stat-value"><?= number_format($stokHabis) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-warning-soft">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <div class="stat-label">Stok Menipis</div>
                            <div class="stat-value"><?= number_format($stokMenipis) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-success-soft">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Stok Cukup</div>
                            <div class="stat-value"><?= number_format($stokCukup) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 mb-md-4">
            <div class="col-12 col-md-4">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-info-soft">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Masuk Bulan Ini</div>
                            <div class="stat-value">
                                <?= number_format($dataBulan['masuk_bulan'] ?? 0) ?> unit
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-orange-soft">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Keluar Bulan Ini</div>
                            <div class="stat-value">
                                <?= number_format($dataBulan['keluar_bulan'] ?? 0) ?> unit
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-primary-soft">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <div class="stat-label">Transaksi Hari Ini</div>
                            <div class="stat-value"><?= number_format($totalTransHari) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($kritis)): ?>
        <div class="card table-card mb-3 mb-md-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                    Peringatan Stok Kritis
                </span>
                <span class="badge bg-danger"><?= count($kritis) ?> Item</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive-mobile">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kritis as $b): ?>
                            <tr>
                                <td class="fw-semibold text-nowrap">
                                    <?= htmlspecialchars($b['nama_barang']) ?>
                                </td>
                                <td>
                                    <span class="badge <?= $b['kategori'] === 'chemical' ? 'bg-info' : 'bg-secondary' ?>">
                                        <?= $b['kategori'] === 'chemical' ? 'Chemical' : 'Peralatan' ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold <?= $b['stok_sekarang'] <= 0 ? 'text-danger' : 'text-warning' ?>">
                                    <?= number_format($b['stok_sekarang']) ?>
                                </td>
                                <td>
                                    <?php if ($b['stok_sekarang'] <= 0): ?>
                                        <span class="badge bg-danger">Habis</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Menipis</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card table-card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>
                10 Transaksi Terakhir
            </div>
            <div class="card-body p-0">
                <div class="table-responsive-mobile">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jenis</th>
                                <th class="text-center">Qty</th>
                                <th>Input Oleh</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentTrans)): ?>
                            <tr>
                                <td colspan="6" class="text-center empty-state">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada transaksi.
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($recentTrans as $t): ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <?= date('d M Y', strtotime($t['tanggal'])) ?>
                                    </td>
                                    <td class="fw-semibold text-nowrap">
                                        <?= htmlspecialchars($t['nama_barang']) ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <?php if ($t['jenis'] === 'masuk'): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-arrow-down me-1"></i>Masuk
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-arrow-up me-1"></i>Keluar
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center fw-bold">
                                        <?= number_format($t['qty']) ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <?= htmlspecialchars($t['username']) ?>
                                    </td>
                                    <td class="text-muted small text-truncate-note">
                                        <?= htmlspecialchars($t['notes'] ?? '-') ?>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>