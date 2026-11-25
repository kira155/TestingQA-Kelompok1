<?php
// export_laporan.php
require_once __DIR__ . '/../config/db.php';

function formatAngka($angka) {
    if ($angka <= 0) return '-';
    return number_format($angka, 0, ',', '.');
}

function rupiah($angka) {
    if ($angka <= 0) return '-';
    return '&nbsp;Rp ' . number_format($angka, 0, ',', '.');
}

$bulan    = (int)($_GET['bulan'] ?? date('n'));
$tahun    = (int)($_GET['tahun'] ?? date('Y'));
$kategori = $_GET['kategori'] ?? '';
$semua    = $_GET['semua'] ?? '0';

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

$bulanStr = sprintf('%04d-%02d', $tahun, $bulan);
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

$totalDiminta = 0;
$totalNilaiDiminta = 0;
$totalMasuk = 0;
$totalNilaiMasuk = 0;
$totalKeluar = 0;
$totalNilaiKeluar = 0;

$filename = 'Laporan_' . $namaBulan[$bulan] . '_' . $tahun . '.xls';

header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Laporan Bulanan</title>';
echo '
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    h3, h4 {
        text-align: center;
        margin: 4px 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th {
        background: #d9eaf7;
        color: #000;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #000;
    }

    td {
        border: 1px solid #000;
        padding: 4px;
        vertical-align: middle;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .kategori {
        background: #eaf2fb;
        font-weight: bold;
        text-transform: uppercase;
    }

    .total {
        background: #d9d9d9;
        font-weight: bold;
    }

.uang {
    mso-number-format:"\@";
    text-align: right;
    white-space: nowrap;
}
</style>
';
echo '</head>';
echo '<body>';

echo '<h3>LAPORAN BARANG CLEANING SERVICE </h3>';
echo '<h4>' . htmlspecialchars($namaBulan[$bulan]) . ' ' . htmlspecialchars($tahun) . '</h4>';
echo '<br>';

echo '<table>';
echo '<thead>';
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
echo '</thead>';
echo '<tbody>';

$no = 1;
$currentKat = '';

foreach ($rows as $r) {
    $stok_awal_bulan = (int)($r['stok_awal_bulan'] ?? 0);
    $qty_masuk       = (int)($r['qty_masuk'] ?? 0);
    $qty_keluar      = (int)($r['qty_keluar'] ?? 0);
    $qty_diminta     = (int)($r['qty_diminta'] ?? 0);

    $nilai_masuk     = (int)($r['nilai_masuk'] ?? 0);
    $nilai_keluar    = (int)($r['nilai_keluar'] ?? 0);
    $nilai_diminta   = (int)($r['nilai_diminta'] ?? 0);
    $harga_barang    = (int)($r['harga_barang'] ?? 0);

    $sisa_qty   = $stok_awal_bulan + $qty_masuk - $qty_keluar;
    $sisa_nilai = ($stok_awal_bulan * $harga_barang) + $nilai_masuk - $nilai_keluar;

    $ada_aktivitas = (
        $qty_diminta > 0 ||
        $qty_masuk > 0 ||
        $qty_keluar > 0 ||
        $stok_awal_bulan > 0
    );

    if ($semua !== '1' && !$ada_aktivitas) {
        continue;
    }

    if ($currentKat !== $r['kategori']) {
        $currentKat = $r['kategori'];

        echo '<tr>';
        echo '<td colspan="14" class="kategori text-left">' . strtoupper(htmlspecialchars($currentKat)) . '</td>';
        echo '</tr>';
    }

    $totalDiminta      += $qty_diminta;
    $totalNilaiDiminta += $nilai_diminta;
    $totalMasuk        += $qty_masuk;
    $totalNilaiMasuk   += $nilai_masuk;
    $totalKeluar       += $qty_keluar;
    $totalNilaiKeluar  += $nilai_keluar;

    echo '<tr>';
    echo '<td class="text-center">' . $no++ . '</td>';
    echo '<td class="text-left">' . htmlspecialchars($r['kategori']) . '</td>';
    echo '<td class="text-left">' . htmlspecialchars($r['nama_barang']) . '</td>';
    echo '<td class="text-center">' . htmlspecialchars($r['satuan'] ?? '-') . '</td>';

    echo '<td class="uang">' . rupiah($harga_barang) . '</td>';
    echo '<td class="text-right">' . formatAngka($stok_awal_bulan) . '</td>';

    echo '<td class="text-right">' . ($qty_diminta > 0 ? formatAngka($qty_diminta) : '-') . '</td>';
    echo '<td class="uang">' . rupiah($nilai_diminta) . '</td>';

    echo '<td class="text-right">' . ($qty_masuk > 0 ? formatAngka($qty_masuk) : '-') . '</td>';
    echo '<td class="uang">' . rupiah($nilai_masuk) . '</td>';

    echo '<td class="text-right">' . ($qty_keluar > 0 ? formatAngka($qty_keluar) : '-') . '</td>';
    echo '<td class="uang">' . rupiah($nilai_keluar) . '</td>';

    echo '<td class="text-right">' . formatAngka($sisa_qty) . '</td>';
    echo '<td class="uang">' . rupiah($sisa_nilai) . '</td>';
    echo '</tr>';
}

echo '<tr class="total">';
echo '<td colspan="6" class="text-right">TOTAL</td>';
echo '<td class="text-right">' . formatAngka($totalDiminta) . '</td>';
echo '<td class="uang">' . rupiah($totalNilaiDiminta) . '</td>';
echo '<td class="text-right">' . formatAngka($totalMasuk) . '</td>';
echo '<td class="uang">' . rupiah($totalNilaiMasuk) . '</td>';
echo '<td class="text-right">' . formatAngka($totalKeluar) . '</td>';
echo '<td class="uang">' . rupiah($totalNilaiKeluar) . '</td>';
echo '<td class="text-center">-</td>';
echo '<td class="text-center">-</td>';
echo '</tr>';

echo '</tbody>';
echo '</table>';

echo '<br><br>';
echo '<div>Dicetak: ' . date('d/m/Y H:i:s') . '</div>';

echo '</body>';
echo '</html>';
exit;
?>