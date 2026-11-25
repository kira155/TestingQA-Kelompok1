<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$isAdmin     = isAdmin();

function navLink(string $href, string $icon, string $label, string $current): string {
    $file   = basename($href);
    $active = ($current === $file) ? 'active' : '';
    $currentAttr = ($current === $file) ? ' aria-current="page"' : '';
    return "
        <a href='/cleaning/pages/{$href}' class='nav-link {$active}'{$currentAttr}>
            <i class='bi bi-{$icon}'></i> {$label}
        </a>
    ";
}
?>

<div id="sidebar">
    <div class="brand">
        <span><i class="bi bi-box-seam me-2" aria-hidden="true"></i>Cleaning Stock</span>
    </div>

    <!-- Menu Utama -->
    <div class="nav-section">Menu</div>
    <nav aria-label="Menu utama">
        <?= navLink('dashboard.php', 'speedometer2', 'Dashboard',   $currentPage) ?>
        <?= navLink('stok.php',      'box-seam',     'Stok Barang', $currentPage) ?>
    </nav>

    <!-- Transaksi (staff & admin) -->
    <div class="nav-section">Transaksi</div>
    <nav aria-label="Menu transaksi">
        <?= navLink('permintaan.php',       'cart-plus',         'Permintaan Barang', $currentPage) ?>
        <?= navLink('transaksi_masuk.php',  'arrow-down-circle', 'Barang Masuk',      $currentPage) ?>
        <?= navLink('transaksi_keluar.php', 'arrow-up-circle',   'Barang Keluar',     $currentPage) ?>
    </nav>

    <!-- Admin only -->
    <?php if ($isAdmin): ?>
    <div class="nav-section">Admin</div>
    <nav aria-label="Menu administrasi">
        <?= navLink('barang.php',         'tags',      'Kelola Barang',   $currentPage) ?>
        <?= navLink('laporan_bulanan.php','bar-chart', 'Laporan Bulanan', $currentPage) ?>
        <?= navLink('users.php',          'people',    'Kelola User',     $currentPage) ?>
    </nav>
    <?php endif; ?>

    <div style="position:absolute;bottom:0;width:100%;padding:12px 8px;border-top:1px solid rgba(255,255,255,.07);">
        <a href="/cleaning/auth/logout.php" class="nav-link text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>