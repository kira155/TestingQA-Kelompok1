<?php
$pageTitle = 'Laporan Bulanan';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
requireAdmin();

// Filter bulan
$bulan = $_GET['bulan'] ?? date('Y-m');

// ── Rekap per barang ──────────────────────────────────────────────────────────
$sqlRekap = "
    SELECT
        b.nama_barang, b.kategori, b.satuan,
        COALESCE(SUM(CASE WHEN t.jenis='masuk'  THEN t.qty ELSE 0 END),0) AS total_masuk,
        COALESCE(SUM(CASE WHEN t.jenis='keluar' THEN t.qty ELSE 0 END),0) AS total_keluar
    FROM barang b
    LEFT JOIN transaksi t
        ON t.id_barang = b.id_barang
        AND DATE_FORMAT(t.tanggal, '%Y-%m') = ?
    GROUP BY b.id_barang
    HAVING total_masuk > 0 OR total_keluar > 0
    ORDER BY b.kategori, b.nama_barang
";
$stmtRekap = $conn->prepare($sqlRekap);
$stmtRekap->bind_param("s", $bulan);
$stmtRekap->execute();
$rekapList = $stmtRekap->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtRekap->close();

// ── Detail transaksi bulan ini ────────────────────────────────────────────────
$sqlDetail = "
    SELECT t.*, b.nama_barang, b.satuan, u.username
    FROM transaksi t
    JOIN barang b ON b.id_barang = t.id_barang
    JOIN users  u ON u.id_user  = t.id_user
    WHERE DATE_FORMAT(t.tanggal, '%Y-%m') = ?
    ORDER BY t.tanggal DESC, t.id_transaksi DESC
";
$stmtDetail = $conn->prepare($sqlDetail);
$stmtDetail->bind_param("s", $bulan);
$stmtDetail->execute();
$detailList = $stmtDetail->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtDetail->close();

// Total
$grandMasuk  = array_sum(array_column($rekapList, 'total_masuk'));
$grandKeluar = array_sum(array_column($rekapList, 'total_keluar'));
?>

<div id="main-wrapper">
    <div id="topbar">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="page-title">Laporan Bulanan</div>
        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <?= htmlspecialchars($_SESSION['username']) ?>
            <span class="role-badge bg-primary text-white">admin</span>
        </div>
    </div>

    <div id="content">

        <!-- ── Filter Bulan ───────────────────────────────────────────────── -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-sm-4">
                        <label class="form-label small mb-1">Pilih Bulan</label>
                        <input type="month" name="bulan" class="form-control form-control-sm"
                               value="<?= htmlspecialchars($bulan) ?>">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Tampilkan
                        </button>
                    </div>
                    <div class="col-sm-6 text-sm-end text-muted small pt-2">
                        Menampilkan laporan:
                        <strong><?= date('F Y', strtotime($bulan . '-01')) ?></strong>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── Kartu Total ────────────────────────────────────────────────── -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="text-muted small mb-1">Total Transaksi</div>
                    <div class="fw-bold fs-3"><?= count($detailList) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="text-success small mb-1">Total Masuk</div>
                    <div class="fw-bold fs-3 text-success">+<?= number_format($grandMasuk) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="text-danger small mb-1">Total Keluar</div>
                    <div class="fw-bold fs-3 text-danger">-<?= number_format($grandKeluar) ?></div>
                </div>
            </div>
        </div>

        <!-- ── Rekap per Barang ───────────────────────────────────────────── -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-table me-2 text-primary"></i>Rekap per Barang
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Masuk</th>
                                <th class="text-center">Keluar</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($rekapList)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada transaksi di bulan ini.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($rekapList as $i => $r): ?>
                        <tr>
                            <td class="text-muted"><?= $i+1 ?></td>
                            <td class="fw-medium"><?= htmlspecialchars($r['nama_barang']) ?></td>
                            <td>
                                <span class="badge <?= $r['kategori']==='chemical'?'bg-info text-dark':'bg-secondary' ?>">
                                    <?= $r['kategori'] ?>
                                </span>
                            </td>
                            <td class="text-center text-success fw-semibold">+<?= $r['total_masuk'] ?></td>
                            <td class="text-center text-danger fw-semibold">-<?= $r['total_keluar'] ?></td>
                            <td class="text-muted"><?= htmlspecialchars($r['satuan'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ── Detail Semua Transaksi ─────────────────────────────────────── -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-list-ul me-2 text-primary"></i>
                Detail Transaksi
                <span class="badge bg-secondary ms-1"><?= count($detailList) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jenis</th>
                                <th class="text-center">Qty</th>
                                <th>Input oleh</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($detailList as $t): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= date('d M Y', strtotime($t['tanggal'])) ?>
                            </td>
                            <td><?= htmlspecialchars($t['nama_barang']) ?></td>
                            <td>
                                <?php if ($t['jenis']==='masuk'): ?>
                                    <span class="badge bg-success">Masuk</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Keluar</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-semibold <?= $t['jenis']==='masuk'?'text-success':'text-danger' ?>">
                                <?= $t['jenis']==='masuk' ? '+' : '-' ?><?= $t['qty'] ?>
                            </td>
                            <td><?= htmlspecialchars($t['username']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($t['notes'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>