<?php
$pageTitle = 'Stok Barang';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

// ── Filter ───────────────────────────────────────────────────────────────────
$filterKategori = $_GET['kategori'] ?? '';
$filterNama     = trim($_GET['nama'] ?? '');
$filterStatus   = $_GET['status'] ?? '';

$where  = [];
$params = [];
$types  = '';

if ($filterKategori !== '') {
    $where[]  = "b.kategori = ?";
    $params[] = $filterKategori;
    $types   .= 's';
}
if ($filterNama !== '') {
    $where[]  = "b.nama_barang LIKE ?";
    $params[] = "%{$filterNama}%";
    $types   .= 's';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Query stok ───────────────────────────────────────────────────────────────
$sql = "
    SELECT
        b.id_barang,
        b.nama_barang,
        b.kategori,
        b.satuan,
        b.stok_awal,
        COALESCE(SUM(CASE WHEN t.jenis = 'masuk'  THEN t.qty ELSE 0 END), 0) AS total_masuk,
        COALESCE(SUM(CASE WHEN t.jenis = 'keluar' THEN t.qty ELSE 0 END), 0) AS total_keluar,
        (b.stok_awal
            + COALESCE(SUM(CASE WHEN t.jenis = 'masuk'  THEN t.qty ELSE 0 END), 0)
            - COALESCE(SUM(CASE WHEN t.jenis = 'keluar' THEN t.qty ELSE 0 END), 0)
        ) AS stok_sekarang
    FROM barang b
    LEFT JOIN transaksi t ON t.id_barang = b.id_barang
    {$whereSql}
    GROUP BY b.id_barang
    ORDER BY b.kategori, b.nama_barang
";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$barangList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Helper status stok
function statusStok(int $stok): array {
    if ($stok <= 0) return ['label' => 'Habis', 'class' => 'danger', 'filter' => 'habis'];
    if ($stok <= 4) return ['label' => 'Menipis', 'class' => 'warning', 'filter' => 'menipis'];
    return ['label' => 'Cukup', 'class' => 'success', 'filter' => 'cukup'];
}

// Filter berdasarkan status
if ($filterStatus !== '') {
    $barangList = array_filter($barangList, function($item) use ($filterStatus) {
        $status = statusStok((int)$item['stok_sekarang']);
        return $status['filter'] === $filterStatus;
    });
    $barangList = array_values($barangList);
}

// Hitung statistik untuk badge
$countHabis = 0;
$countMenipis = 0;
$countCukup = 0;
foreach ($barangList as $b) {
    $stok = (int)$b['stok_sekarang'];
    if ($stok <= 0) $countHabis++;
    elseif ($stok <= 4) $countMenipis++;
    else $countCukup++;
}
?>

<style>
body {
    background-color: #f8fafc;
}

/* Topbar */
#topbar {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

#content {
    padding: 20px;
}

.page-title {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
}

.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
    padding: 7px 12px;
    font-size: 13px;
    color: #374151;
    white-space: nowrap;
}

.role-badge {
    border-radius: 999px;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
}

/* Card modern */
.card {
    border-radius: 12px;
    border: 1px solid #eef1f5;
    transition: all 0.25s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Icon box */
.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

/* Filter */
.filter-section {
    background: #ffffff;
    border: 1px solid #eef1f5;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, 0.03);
}

.filter-actions {
    display: flex;
    gap: 8px;
}

/* Table */
.table {
    font-size: 0.9rem;
}

.table thead th {
    font-weight: 600;
    color: #6b7280;
    background-color: #f9fafb;
    border-bottom: 1px solid #eef1f5;
    padding-top: 14px;
    padding-bottom: 14px;
}

.table td {
    vertical-align: middle;
    padding-top: 13px;
    padding-bottom: 13px;
}

.table tbody tr:hover {
    background-color: #f1f5f9;
}

/* Badge modern */
.badge {
    border-radius: 8px;
    font-weight: 500;
}

/* Status filter pills */
.status-filter-pills {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.status-pill {
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid transparent;
}

.status-pill:hover {
    transform: translateY(-1px);
}

.status-pill.active {
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
}

.status-pill-all {
    background: #f1f5f9;
    color: #475569;
    border-color: #e2e8f0;
}

.status-pill-all:hover,
.status-pill-all.active {
    background: #64748b;
    color: #ffffff;
}

.status-pill-habis {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fecaca;
}

.status-pill-habis:hover,
.status-pill-habis.active {
    background: #dc3545;
    color: #ffffff;
}

.status-pill-menipis {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}

.status-pill-menipis:hover,
.status-pill-menipis.active {
    background: #ffc107;
    color: #212529;
}

.status-pill-cukup {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}

.status-pill-cukup:hover,
.status-pill-cukup.active {
    background: #198754;
    color: #ffffff;
}

.stock-stat-cukup {
    color: #198754;
    font-weight: bold;
}

.stock-stat-menipis {
    color: #ffc107;
    font-weight: bold;
}

.stock-stat-habis {
    color: #dc3545;
    font-weight: bold;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stock-item-card {
    animation: fadeIn 0.3s ease-out;
}

/* Mobile responsive styles */
@media (max-width: 768px) {
    #content {
        padding: 12px !important;
    }

    #topbar {
        padding: 12px !important;
        flex-wrap: wrap;
        gap: 10px;
    }

    .page-title {
        font-size: 18px !important;
        flex: 1;
    }

    .user-badge {
        font-size: 12px !important;
        padding: 6px 10px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stock-card-view {
        display: block !important;
    }

    .stock-table-view {
        display: none !important;
    }

    .filter-section {
        padding: 14px;
        margin-bottom: 14px;
    }

    .filter-actions {
        margin-top: 12px;
    }

    .filter-actions button,
    .filter-actions a {
        flex: 1;
        padding: 10px;
        font-size: 14px;
    }

    .status-filter-pills {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 12px;
    }

    .status-pill {
        justify-content: center;
        text-align: center;
        padding: 9px 10px;
        font-size: 12px;
    }

    .stock-item-card {
        background: #ffffff;
        border: 1px solid #eef1f5;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
        transition: all 0.25s ease;
    }

    .stock-item-card:hover {
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.09);
        transform: translateY(-2px);
    }

    .stock-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eef1f5;
    }

    .stock-name {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        flex: 1;
        line-height: 1.35;
    }

    .stock-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    .stat-item {
        text-align: center;
        padding: 10px 8px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #eef1f5;
    }

    .stat-label {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .stat-value {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
    }

    .stock-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px solid #eef1f5;
    }

    .stock-meta {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: #6b7280;
    }

    .badge {
        font-size: 11px !important;
        padding: 5px 8px !important;
    }

    .btn-sm {
        padding: 10px 16px !important;
        min-height: 44px;
        font-size: 14px !important;
    }

    select,
    input {
        min-height: 44px;
        font-size: 16px !important;
    }
}

@media (min-width: 769px) {
    .stock-card-view {
        display: none !important;
    }

    .stock-table-view {
        display: block !important;
    }
}

@media (max-width: 480px) {
    .status-filter-pills {
        grid-template-columns: 1fr;
    }

    .stock-stats {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .stock-header {
        flex-direction: column;
        gap: 8px;
    }

    .stock-category {
        align-self: flex-start;
    }

    .stat-value {
        font-size: 16px;
    }

    .filter-actions {
        flex-direction: column;
    }

    .stock-footer {
        flex-direction: column;
        align-items: flex-start;
    }

    .stock-meta {
        flex-direction: column;
        gap: 4px;
        align-items: flex-start;
    }
}
</style>

<div id="main-wrapper">

    <!-- Topbar -->
    <div id="topbar">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="page-title">Stok Barang</div>
        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <?= htmlspecialchars($_SESSION['username']) ?>
            <span class="role-badge <?= isAdmin() ? 'bg-primary text-white' : 'bg-success text-white' ?>">
                <?= htmlspecialchars($_SESSION['role']) ?>
            </span>
        </div>
    </div>

    <div id="content">

        <!-- Subtitle Dashboard -->
        <div class="mb-3">
            <h5 class="fw-bold mb-0">Ringkasan Sistem</h5>
            <small class="text-muted">Monitoring stok & transaksi secara real-time</small>
        </div>

        <!-- Filter -->
        <div class="filter-section">
            <form method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label class="form-label small mb-1">Nama Barang</label>
                        <input type="text" name="nama" class="form-control form-control-sm"
                               placeholder="Cari nama barang..."
                               value="<?= htmlspecialchars($filterNama) ?>"
                               id="searchInput">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small mb-1">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm" id="kategoriSelect">
                            <option value="">Semua Kategori</option>
                            <option value="chemical"  <?= $filterKategori === 'chemical'  ? 'selected' : '' ?>>Chemical</option>
                            <option value="peralatan" <?= $filterKategori === 'peralatan' ? 'selected' : '' ?>>Peralatan</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="stok.php" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Status Filter Pills -->
        <div class="status-filter-pills">
            <a href="?<?= http_build_query(array_merge($_GET, ['status' => '', 'page' => 1])) ?>"
               class="status-pill status-pill-all <?= $filterStatus === '' ? 'active' : '' ?>">
                <i class="bi bi-grid-3x3-gap-fill"></i> Semua
                <span class="badge bg-secondary bg-opacity-25 rounded-pill"><?= count($barangList) ?></span>
            </a>
            <a href="?<?= http_build_query(array_merge($_GET, ['status' => 'habis', 'page' => 1])) ?>"
               class="status-pill status-pill-habis <?= $filterStatus === 'habis' ? 'active' : '' ?>">
                <i class="bi bi-exclamation-octagon-fill"></i> Habis
                <span class="badge bg-danger bg-opacity-25 rounded-pill"><?= $countHabis ?></span>
            </a>
            <a href="?<?= http_build_query(array_merge($_GET, ['status' => 'menipis', 'page' => 1])) ?>"
               class="status-pill status-pill-menipis <?= $filterStatus === 'menipis' ? 'active' : '' ?>">
                <i class="bi bi-hourglass-split"></i> Menipis
                <span class="badge bg-warning bg-opacity-25 rounded-pill"><?= $countMenipis ?></span>
            </a>
            <a href="?<?= http_build_query(array_merge($_GET, ['status' => 'cukup', 'page' => 1])) ?>"
               class="status-pill status-pill-cukup <?= $filterStatus === 'cukup' ? 'active' : '' ?>">
                <i class="bi bi-check-circle-fill"></i> Cukup
                <span class="badge bg-success bg-opacity-25 rounded-pill"><?= $countCukup ?></span>
            </a>
        </div>

        <!-- Result count -->
        <div class="mb-3 text-muted small">
            <i class="bi bi-box-seam me-1"></i>
            Menampilkan <?= count($barangList) ?> barang
            <?php if ($filterStatus !== ''): ?>
                <span class="badge bg-secondary ms-2">
                    Filter: <?= ucfirst(htmlspecialchars($filterStatus)) ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['status' => ''])) ?>" class="text-white ms-1">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                </span>
            <?php endif; ?>
        </div>

        <!-- Desktop Table View -->
        <div class="stock-table-view">
            <div class="card shadow-sm bg-white">
                <div class="card-header bg-white fw-semibold border-0 pb-0 d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-box-seam me-2 text-primary"></i>
                        Daftar Stok Barang
                    </span>
                    <span class="badge bg-secondary bg-opacity-75"><?= count($barangList) ?> barang</span>
                </div>
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Stok Awal</th>
                                    <th class="text-center">Masuk</th>
                                    <th class="text-center">Keluar</th>
                                    <th class="text-center">Stok Saat Ini</th>
                                    <th>Satuan</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($barangList)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada barang ditemukan.
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($barangList as $i => $b):
                                    $status = statusStok((int)$b['stok_sekarang']);
                                    $statusBadgeClass = match ($status['class']) {
                                        'danger' => 'bg-danger bg-opacity-75',
                                        'warning' => 'bg-warning bg-opacity-75 text-dark',
                                        default => 'bg-success bg-opacity-75'
                                    };
                                ?>
                                <tr>
                                    <td class="text-muted small"><?= $i + 1 ?></td>
                                    <td class="fw-medium"><?= htmlspecialchars($b['nama_barang']) ?></td>
                                    <td>
                                        <span class="badge <?= $b['kategori'] === 'chemical' ? 'bg-info bg-opacity-75 text-dark' : 'bg-secondary bg-opacity-75' ?>">
                                            <?= $b['kategori'] === 'chemical' ? 'Chemical' : 'Peralatan' ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $b['stok_awal'] ?></td>
                                    <td class="text-center text-success fw-semibold">+<?= $b['total_masuk'] ?></td>
                                    <td class="text-center text-danger fw-semibold">-<?= $b['total_keluar'] ?></td>
                                    <td class="text-center">
                                        <span class="fw-bold fs-6 text-<?= $status['class'] ?>">
                                            <?= $b['stok_sekarang'] ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small"><?= htmlspecialchars($b['satuan'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $statusBadgeClass ?>">
                                            <?= $status['label'] ?>
                                            <?php if ($status['label'] === 'Menipis'): ?>
                                                (<?= $b['stok_sekarang'] ?>/4)
                                            <?php elseif ($status['label'] === 'Habis'): ?>
                                                (0)
                                            <?php endif; ?>
                                        </span>
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

        <!-- Mobile Card View -->
        <div class="stock-card-view">
            <?php if (empty($barangList)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p>Tidak ada barang ditemukan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($barangList as $i => $b):
                    $status = statusStok((int)$b['stok_sekarang']);
                    $statusBadgeClass = match ($status['class']) {
                        'danger' => 'bg-danger bg-opacity-75',
                        'warning' => 'bg-warning bg-opacity-75 text-dark',
                        default => 'bg-success bg-opacity-75'
                    };
                ?>
                    <div class="stock-item-card">
                        <div class="stock-header">
                            <div class="stock-name">
                                <?= htmlspecialchars($b['nama_barang']) ?>
                            </div>
                            <div class="stock-category">
                                <span class="badge <?= $b['kategori'] === 'chemical' ? 'bg-info bg-opacity-75 text-dark' : 'bg-secondary bg-opacity-75' ?>">
                                    <?= $b['kategori'] === 'chemical' ? 'Chemical' : 'Peralatan' ?>
                                </span>
                            </div>
                        </div>

                        <div class="stock-stats">
                            <div class="stat-item">
                                <div class="stat-label">Stok Awal</div>
                                <div class="stat-value"><?= $b['stok_awal'] ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Masuk</div>
                                <div class="stat-value text-success">+<?= $b['total_masuk'] ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Keluar</div>
                                <div class="stat-value text-danger">-<?= $b['total_keluar'] ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Stok Saat Ini</div>
                                <div class="stat-value">
                                    <strong class="stock-stat-<?= strtolower($status['label']) ?>">
                                        <?= $b['stok_sekarang'] ?>
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="stock-footer">
                            <div class="stock-meta">
                                <span><i class="bi bi-rulers"></i> <?= htmlspecialchars($b['satuan'] ?? '-') ?></span>
                            </div>
                            <div>
                                <span class="badge <?= $statusBadgeClass ?>">
                                    <?= $status['label'] ?>
                                    <?php if ($status['label'] === 'Menipis'): ?>
                                        (<?= $b['stok_sekarang'] ?>/4)
                                    <?php elseif ($status['label'] === 'Habis'): ?>
                                        (0)
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
// Auto-submit filter on select change
const kategoriSelect = document.getElementById('kategoriSelect');
const filterForm = document.getElementById('filterForm');
const searchInput = document.getElementById('searchInput');

kategoriSelect?.addEventListener('change', function() {
    filterForm?.submit();
});

// Debounced search for better performance
let searchTimeout;
searchInput?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        filterForm?.submit();
    }, 500);
});

// Highlight active status filter
const statusPills = document.querySelectorAll('.status-pill');
statusPills.forEach(pill => {
    pill.addEventListener('click', function() {
        statusPills.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>