<?php
$pageTitle = 'Laporan Bulanan';
require_once __DIR__ . '/../config/db.php';


$bulan    = (int)($_GET['bulan'] ?? date('n'));
$tahun    = (int)($_GET['tahun'] ?? date('Y'));
$kategori = $_GET['kategori'] ?? '';
$export   = $_GET['export'] ?? '';

$bulanStr  = sprintf('%04d-%02d', $tahun, $bulan);
$namaBulan = [
    '',
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

$whereKat = $kategori ? "AND b.kategori = '" . $conn->real_escape_string($kategori) . "'" : '';

$sql = "
    SELECT 
        b.id_barang,
        b.nama_barang,
        b.kategori,
        b.satuan,
        b.harga_barang,
        b.stok_awal,
        b.stok_sekarang,

        (
            COALESCE(b.stok_awal, 0) + COALESCE((
                SELECT SUM(CASE WHEN t.jenis = 'masuk' THEN t.qty ELSE -t.qty END)
                FROM transaksi t
                WHERE t.id_barang = b.id_barang 
                AND t.tanggal < '$bulanStr-01'
            ), 0)
        ) AS stok_awal_bulan,

        COALESCE((
            SELECT SUM(p.qty) 
            FROM permintaan p 
            WHERE p.id_barang = b.id_barang 
            AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$bulanStr'
        ), 0) AS qty_diminta,

        COALESCE((
            SELECT SUM(p.estimasi_total) 
            FROM permintaan p 
            WHERE p.id_barang = b.id_barang 
            AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$bulanStr'
        ), 0) AS nilai_diminta,

        COALESCE((
            SELECT SUM(t.qty) 
            FROM transaksi t 
            WHERE t.id_barang = b.id_barang 
            AND t.jenis = 'masuk' 
            AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$bulanStr'
        ), 0) AS qty_masuk,

        COALESCE((
            SELECT SUM(t.total_harga) 
            FROM transaksi t 
            WHERE t.id_barang = b.id_barang 
            AND t.jenis = 'masuk' 
            AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$bulanStr'
        ), 0) AS nilai_masuk,

        COALESCE((
            SELECT SUM(t.qty) 
            FROM transaksi t 
            WHERE t.id_barang = b.id_barang 
            AND t.jenis = 'keluar' 
            AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$bulanStr'
        ), 0) AS qty_keluar,

        COALESCE((
            SELECT SUM(t.total_harga) 
            FROM transaksi t 
            WHERE t.id_barang = b.id_barang 
            AND t.jenis = 'keluar' 
            AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$bulanStr'
        ), 0) AS nilai_keluar

    FROM barang b
    WHERE 1=1 $whereKat
    ORDER BY b.kategori, b.nama_barang
";

$result = $conn->query($sql);

if (!$result) {
    die("Error SQL: " . $conn->error);
}

$rows = $result->fetch_all(MYSQLI_ASSOC);

$laporanData = [];
$totalDiminta = 0;
$totalNilaiDiminta = 0;
$totalMasuk = 0;
$totalNilaiMasuk = 0;
$totalKeluar = 0;
$totalNilaiKeluar = 0;
$stockOut = 0;

foreach ($rows as $r) {
    $stok_awal_bulan = (int)($r['stok_awal_bulan'] ?? 0);
    $qty_masuk       = (int)($r['qty_masuk'] ?? 0);
    $qty_keluar      = (int)($r['qty_keluar'] ?? 0);
    $qty_diminta     = (int)($r['qty_diminta'] ?? 0);

    $nilai_masuk     = (int)($r['nilai_masuk'] ?? 0);
    $nilai_keluar    = (int)($r['nilai_keluar'] ?? 0);
    $nilai_diminta   = (int)($r['nilai_diminta'] ?? 0);
    $harga_barang    = (int)($r['harga_barang'] ?? 0);

    $sisa_qty = $stok_awal_bulan + $qty_masuk - $qty_keluar;
    $sisa_nilai = ($stok_awal_bulan * $harga_barang) + $nilai_masuk - $nilai_keluar;

    $ada_aktivitas = (
        $qty_diminta > 0 ||
        $qty_masuk > 0 ||
        $qty_keluar > 0 ||
        $stok_awal_bulan > 0
    );

    if ($sisa_qty <= 0 && $ada_aktivitas) {
        $stockOut++;
    }

    $totalDiminta += $qty_diminta;
    $totalNilaiDiminta += $nilai_diminta;
    $totalMasuk += $qty_masuk;
    $totalNilaiMasuk += $nilai_masuk;
    $totalKeluar += $qty_keluar;
    $totalNilaiKeluar += $nilai_keluar;

    $laporanData[] = [
        'kategori'       => $r['kategori'],
        'nama_barang'    => $r['nama_barang'],
        'satuan'         => $r['satuan'],
        'harga_barang'   => $harga_barang,
        'stok_awal'      => $stok_awal_bulan,
        'qty_diminta'    => $qty_diminta,
        'nilai_diminta'  => $nilai_diminta,
        'qty_masuk'      => $qty_masuk,
        'nilai_masuk'    => $nilai_masuk,
        'qty_keluar'     => $qty_keluar,
        'nilai_keluar'   => $nilai_keluar,
        'sisa_qty'       => $sisa_qty,
        'sisa_nilai'     => $sisa_nilai,
        'ada_aktivitas'  => $ada_aktivitas
    ];
}

function rupiah($angka) {
    if ($angka <= 0) return '-';
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

if ($export === 'excel') {
    $filename = 'Laporan_' . $namaBulan[$bulan] . '_' . $tahun . '.xls';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    echo '<html>';
    echo '<head><meta charset="UTF-8"><title>Laporan Bulanan</title></head>';
    echo '<body>';
    echo '<h3 align="center">LAPORAN BARANG CLEANING SERVICE</h3>';
    echo '<h4 align="center">' . $namaBulan[$bulan] . ' ' . $tahun . '</h4>';

    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kategori</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Satuan</th>
            <th rowspan="2">Harga</th>
            <th rowspan="2">Stok Awal</th>
            <th colspan="2">Diminta</th>
            <th colspan="2">Masuk</th>
            <th colspan="2">Keluar</th>
            <th colspan="2">Sisa</th>
        </tr>
        <tr>
            <th>Qty</th>
            <th>Nilai</th>
            <th>Qty</th>
            <th>Nilai</th>
            <th>Qty</th>
            <th>Nilai</th>
            <th>Qty</th>
            <th>Nilai</th>
        </tr>
    ';

    $no = 1;
    $currentKat = '';

    foreach ($laporanData as $d) {
        if ($currentKat != $d['kategori']) {
            $currentKat = $d['kategori'];
            echo '<tr style="background:#eaf2fb; font-weight:bold;">
                    <td colspan="14">' . strtoupper($d['kategori']) . '</td>
                  </tr>';
        }

        echo '<tr>
                <td>' . $no++ . '</td>
                <td>' . htmlspecialchars($d['kategori']) . '</td>
                <td>' . htmlspecialchars($d['nama_barang']) . '</td>
                <td>' . htmlspecialchars($d['satuan']) . '</td>
                <td>' . number_format($d['harga_barang']) . '</td>
                <td>' . number_format($d['stok_awal']) . '</td>
                <td>' . ($d['qty_diminta'] > 0 ? number_format($d['qty_diminta']) : '-') . '</td>
                <td>' . number_format($d['nilai_diminta']) . '</td>
                <td>' . ($d['qty_masuk'] > 0 ? number_format($d['qty_masuk']) : '-') . '</td>
                <td>' . number_format($d['nilai_masuk']) . '</td>
                <td>' . ($d['qty_keluar'] > 0 ? number_format($d['qty_keluar']) : '-') . '</td>
                <td>' . number_format($d['nilai_keluar']) . '</td>
                <td>' . number_format($d['sisa_qty']) . '</td>
                <td>' . number_format($d['sisa_nilai']) . '</td>
              </tr>';
    }

    echo '<tr style="background:#ddd; font-weight:bold;">
            <td colspan="6" align="right">TOTAL</td>
            <td>' . number_format($totalDiminta) . '</td>
            <td>' . number_format($totalNilaiDiminta) . '</td>
            <td>' . number_format($totalMasuk) . '</td>
            <td>' . number_format($totalNilaiMasuk) . '</td>
            <td>' . number_format($totalKeluar) . '</td>
            <td>' . number_format($totalNilaiKeluar) . '</td>
            <td>-</td>
            <td>-</td>
          </tr>';

    echo '</table>';
    echo '<br><br>';
    echo '<div>Dicetak: ' . date('d/m/Y H:i:s') . '</div>';
    echo '</body></html>';
    exit;
}

$grouped = [];
foreach ($laporanData as $d) {
    $grouped[$d['kategori']][] = $d;
}

$tampilSemua = isset($_GET['semua']) && $_GET['semua'] === '1';

$labelKat = [
    'chemical' => 'Chemical Kebersihan',
    'peralatan' => 'Peralatan Kebersihan'
];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
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

.page-intro {
    margin-bottom: 18px;
}

.page-intro h5 {
    color: #111827;
}

.page-intro small {
    color: #6b7280;
}

.modern-card {
    border: 0;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

.modern-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #edf1f7;
    padding: 16px 18px;
    font-weight: 700;
    color: #111827;
}

.modern-card .card-body {
    padding: 18px;
}

.summary-card {
    border: 0;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    padding: 18px;
    height: 100%;
}

.summary-label {
    font-size: 0.8rem;
    color: #6b7280;
}

.summary-value {
    font-size: 1.45rem;
    font-weight: 800;
    line-height: 1.2;
}

.summary-money {
    font-size: 0.78rem;
    color: #6b7280;
}

.btn-excel {
    background: #28a745;
    color: white;
    border: none;
}

.btn-excel:hover {
    background: #218838;
    color: white;
}

.table-responsive-report {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.tbl-laporan {
    min-width: 1180px;
}

.tbl-laporan th,
.tbl-laporan td {
    white-space: nowrap;
    font-size: 12px;
    vertical-align: middle;
    padding: 8px 9px;
}

.tbl-laporan th {
    background: #1a6fb5;
    color: #fff;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 1;
}

.tbl-laporan td.nama-barang {
    white-space: normal;
    max-width: 180px;
    font-weight: 600;
    color: #111827;
}

.tbl-laporan .kat-header td {
    background: #eaf2fb;
    font-weight: 800;
    font-size: 12px;
    color: #0d4f8b;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.cell-diminta {
    background: #fff8e1;
    text-align: center;
}

.cell-masuk {
    background: #e8f5e9;
    text-align: center;
}

.cell-keluar {
    background: #fce4ec;
    text-align: center;
}

.cell-sisa {
    background: #e3f2fd;
    text-align: center;
    font-weight: 700;
}

.cell-stok-awal {
    text-align: center;
}

.cell-harga {
    text-align: right;
    font-family: monospace;
    font-weight: 700;
    color: #15803d;
    background: #f0fdf4;
}

.cell-nilai {
    font-family: monospace;
    font-size: 11px;
    text-align: right;
}

.sisa-warning {
    background: #fff3e0 !important;
    color: #e65100 !important;
}

.sisa-danger {
    background: #ffebee !important;
    color: #b71c1c !important;
}

.harga-info {
    font-size: 10px;
    color: #16a34a;
    margin-top: 2px;
    font-weight: 700;
}

@media(max-width: 768px) {
    #topbar {
        padding: 12px 14px;
    }

    #content {
        padding: 14px;
    }

    .page-title {
        font-size: 1rem;
    }

    .page-intro h5 {
        font-size: 1rem;
    }

    .page-intro small {
        font-size: 0.78rem;
    }

    .user-badge {
        padding: 6px 9px;
        font-size: 0.78rem;
    }

    .role-badge {
        font-size: 0.65rem;
        padding: 3px 6px;
    }

    .summary-card {
        padding: 14px;
        border-radius: 15px;
    }

    .summary-value {
        font-size: 1.15rem;
    }

    .modern-card {
        border-radius: 15px;
    }

    .modern-card .card-header {
        padding: 13px 14px;
        font-size: 0.9rem;
    }

    .tbl-laporan td.nama-barang {
        max-width: 120px;
        font-size: 11px;
    }

    .tbl-laporan th,
    .tbl-laporan td {
        font-size: 10px;
        padding: 5px 6px;
    }
}
</style>

<div id="main-wrapper">
    <div id="topbar" class="d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="page-title">Laporan Bulanan</div>
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

        <div class="page-intro">
            <h5 class="fw-bold mb-0">Laporan Bulanan</h5>
            <small>
                Laporan stok, permintaan, barang masuk, barang keluar, dan nilai sisa barang.
            </small>
        </div>

        <div class="card modern-card mb-4">
            <div class="card-body py-3">
                <form method="GET" class="row g-2 align-items-end" id="filterForm">
                    <div class="col-6 col-sm-auto">
                        <label class="form-label mb-1 small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m === $bulan ? 'selected' : '' ?>>
                                    <?= $namaBulan[$m] ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-6 col-sm-auto">
                        <label class="form-label mb-1 small">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <?php for ($y = 2024; $y <= date('Y') + 1; $y++): ?>
                                <option value="<?= $y ?>" <?= $y === $tahun ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-6 col-sm-auto">
                        <label class="form-label mb-1 small">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="chemical" <?= $kategori === 'chemical' ? 'selected' : '' ?>>
                                Chemical
                            </option>
                            <option value="peralatan" <?= $kategori === 'peralatan' ? 'selected' : '' ?>>
                                Peralatan
                            </option>
                        </select>
                    </div>

                    <div class="col-6 col-sm-auto">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-funnel me-1"></i>Tampilkan
                        </button>
                    </div>

                    <div class="col-auto ms-sm-auto">
                        <button type="button" class="btn btn-excel btn-sm" onclick="exportExcel()">
                            <i class="bi bi-file-excel me-1"></i>Export Excel
                        </button>
                    </div>

                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cetakLaporan()">
                            <i class="bi bi-printer me-1"></i>Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="summary-card text-center">
                    <div class="summary-label">Total Diminta</div>
                    <div class="summary-value text-warning"><?= number_format($totalDiminta) ?></div>
                    <div class="summary-money"><?= rupiah($totalNilaiDiminta) ?></div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="summary-card text-center">
                    <div class="summary-label">Total Masuk</div>
                    <div class="summary-value text-success"><?= number_format($totalMasuk) ?></div>
                    <div class="summary-money"><?= rupiah($totalNilaiMasuk) ?></div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="summary-card text-center">
                    <div class="summary-label">Total Keluar</div>
                    <div class="summary-value text-danger"><?= number_format($totalKeluar) ?></div>
                    <div class="summary-money"><?= rupiah($totalNilaiKeluar) ?></div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="summary-card text-center">
                    <div class="summary-label">Barang Habis</div>
                    <div class="summary-value <?= $stockOut > 0 ? 'text-danger' : 'text-success' ?>">
                        <?= number_format($stockOut) ?>
                    </div>
                    <div class="summary-money">Item</div>
                </div>
            </div>
        </div>

        <div class="card modern-card" id="areaLaporan">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>
                    <i class="bi bi-table me-2 text-primary"></i>
                    Laporan <?= $namaBulan[$bulan] ?> <?= $tahun ?>
                </span>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="toggleSemua"
                            <?= $tampilSemua ? 'checked' : '' ?>
                            onchange="location.href='?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&kategori=<?= htmlspecialchars($kategori) ?>&semua='+(this.checked?'1':'0')">
                        <label class="form-check-label small text-muted" for="toggleSemua">
                            Tampil semua barang
                        </label>
                    </div>

                    <input 
                        type="text" 
                        id="searchTbl" 
                        class="form-control form-control-sm" 
                        style="width:160px" 
                        placeholder="Cari barang...">
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive-report">
                    <table class="table table-bordered tbl-laporan mb-0" id="tblLaporan">
                        <thead>
                            <tr>
                                <th rowspan="2" style="min-width:30px">No</th>
                                <th rowspan="2" style="min-width:150px;text-align:left">Nama Barang</th>
                                <th rowspan="2">Satuan</th>
                                <th rowspan="2">Harga</th>
                                <th rowspan="2">Stok Awal</th>
                                <th colspan="2" class="cell-diminta">Diminta</th>
                                <th colspan="2" class="cell-masuk">Masuk</th>
                                <th colspan="2" class="cell-keluar">Keluar</th>
                                <th colspan="2" class="cell-sisa">Sisa</th>
                            </tr>

                            <tr>
                                <th class="cell-diminta">Qty</th>
                                <th class="cell-diminta">Nilai</th>
                                <th class="cell-masuk">Qty</th>
                                <th class="cell-masuk">Nilai</th>
                                <th class="cell-keluar">Qty</th>
                                <th class="cell-keluar">Nilai</th>
                                <th class="cell-sisa">Qty</th>
                                <th class="cell-sisa">Nilai</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($grouped)): ?>
                                <tr>
                                    <td colspan="13" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada data barang.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($grouped as $kat => $items): ?>
                                    <tr class="kat-header">
                                        <td colspan="13">
                                            <?= $labelKat[$kat] ?? ucfirst($kat) ?>
                                        </td>
                                    </tr>

                                    <?php
                                    $no = 1;
                                    foreach ($items as $r):
                                        $sisa_qty = $r['sisa_qty'];
                                        $sisa_nilai = $r['sisa_nilai'];
                                        $ada_aktivitas = $r['ada_aktivitas'];

                                        if (!$tampilSemua && !$ada_aktivitas) {
                                            continue;
                                        }

                                        $sisaklass = '';
                                        if ($sisa_qty <= 0 && $ada_aktivitas) {
                                            $sisaklass = 'sisa-danger';
                                        } elseif ($sisa_qty <= 2 && $ada_aktivitas) {
                                            $sisaklass = 'sisa-warning';
                                        }
                                    ?>
                                        <tr data-nama="<?= strtolower(htmlspecialchars($r['nama_barang'])) ?>">
                                            <td class="text-center text-muted">
                                                <?= $no++ ?>
                                            </td>

                                            <td class="nama-barang">
                                                <?= htmlspecialchars($r['nama_barang']) ?>
                                                <div class="harga-info">
                                                    <?= rupiah($r['harga_barang']) ?>/<?= htmlspecialchars($r['satuan'] ?? '-') ?>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <?= htmlspecialchars($r['satuan'] ?? '-') ?>
                                            </td>

                                            <td class="cell-harga">
                                                <?= rupiah($r['harga_barang']) ?>
                                            </td>

                                            <td class="cell-stok-awal">
                                                <?= number_format($r['stok_awal']) ?>
                                            </td>

                                            <td class="cell-diminta">
                                                <?= $r['qty_diminta'] > 0 ? number_format($r['qty_diminta']) : '-' ?>
                                            </td>

                                            <td class="cell-diminta cell-nilai">
                                                <?= rupiah($r['nilai_diminta']) ?>
                                            </td>

                                            <td class="cell-masuk">
                                                <?= $r['qty_masuk'] > 0 ? number_format($r['qty_masuk']) : '-' ?>
                                            </td>

                                            <td class="cell-masuk cell-nilai">
                                                <?= rupiah($r['nilai_masuk']) ?>
                                            </td>

                                            <td class="cell-keluar">
                                                <?= $r['qty_keluar'] > 0 ? number_format($r['qty_keluar']) : '-' ?>
                                            </td>

                                            <td class="cell-keluar cell-nilai">
                                                <?= rupiah($r['nilai_keluar']) ?>
                                            </td>

                                            <td class="cell-sisa <?= $sisaklass ?>">
                                                <?= number_format($sisa_qty) ?>
                                                <?php if ($sisa_qty <= 0 && $ada_aktivitas): ?>
                                                    <i class="bi bi-exclamation-triangle-fill ms-1" title="Stok habis"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td class="cell-sisa cell-nilai <?= $sisaklass ?>">
                                                <?= rupiah($sisa_nilai) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>

                                <tr class="table-dark fw-bold">
                                    <td colspan="5" class="text-end">TOTAL</td>
                                    <td class="text-center"><?= number_format($totalDiminta) ?></td>
                                    <td class="text-end"><?= rupiah($totalNilaiDiminta) ?></td>
                                    <td class="text-center"><?= number_format($totalMasuk) ?></td>
                                    <td class="text-end"><?= rupiah($totalNilaiMasuk) ?></td>
                                    <td class="text-center"><?= number_format($totalKeluar) ?></td>
                                    <td class="text-end"><?= rupiah($totalNilaiKeluar) ?></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
function exportExcel() {
    let bulan = document.querySelector('select[name="bulan"]').value;
    let tahun = document.querySelector('select[name="tahun"]').value;
    let kategori = document.querySelector('select[name="kategori"]').value;
    let semua = document.querySelector('#toggleSemua')?.checked ? '1' : '0';

    window.location.href =
        'export_laporan.php' +
        '?bulan=' + encodeURIComponent(bulan) +
        '&tahun=' + encodeURIComponent(tahun) +
        '&kategori=' + encodeURIComponent(kategori) +
        '&semua=' + encodeURIComponent(semua);
}
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>