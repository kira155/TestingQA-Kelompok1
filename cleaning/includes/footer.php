<?php
// Footer konsisten untuk halaman aplikasi (tahun mengikuti waktu server).
?>
<style>
    .app-footer {
        margin: 20px 0 0 var(--sidebar-width, 240px);
        padding: 20px 28px;
        background: #ffffff;
        border-top: 1px solid #e5eaf1;
        color: #64748b;
        font-size: 0.82rem;
    }
    .app-footer-inner {
        max-width: 1440px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .app-footer-brand { display: flex; align-items: center; gap: 11px; }
    .app-footer-logo {
        display: grid;
        place-items: center;
        width: 38px;
        height: 38px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 11px;
        font-size: 1.08rem;
    }
    .app-footer-name { color: #17243b; font-size: 0.88rem; font-weight: 700; }
    .app-footer-caption { font-size: 0.74rem; margin-top: 2px; }
    .app-footer-meta { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .app-footer-status { color: #475569; display: inline-flex; align-items: center; gap: 6px; }
    .app-footer-status i { color: #16a34a; }
    .app-footer-copy { color: #64748b; }
    @media (max-width: 767.98px) {
        .app-footer { margin-left: 0; padding: 18px 20px; }
        .app-footer-inner { align-items: flex-start; flex-direction: column; gap: 14px; }
        .app-footer-meta { gap: 8px 14px; }
    }
</style>

<footer class="app-footer" role="contentinfo" aria-label="Informasi aplikasi">
    <div class="app-footer-inner">
        <div class="app-footer-brand">
            <span class="app-footer-logo" aria-hidden="true"><i class="bi bi-box-seam"></i></span>
            <div>
                <div class="app-footer-name">Cleaning Stock Management</div>
                <div class="app-footer-caption">Sistem Inventaris &amp; Operasional</div>
            </div>
        </div>
        <div class="app-footer-meta">
            <span class="app-footer-status"><i class="bi bi-shield-check" aria-hidden="true"></i> Manajemen stok terintegrasi</span>
            <span class="app-footer-copy">&copy; <?= date('Y') ?> Cleaning Stock Management</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.setAttribute('type', 'button');
        sidebarToggle.setAttribute('aria-controls', 'sidebar');
        sidebarToggle.setAttribute('aria-label', 'Buka atau tutup menu navigasi');
        sidebarToggle.setAttribute('aria-expanded', 'false');

        const closeSidebar = () => {
            sidebar.classList.remove('show');
            sidebarToggle.setAttribute('aria-expanded', 'false');
        };
        sidebarToggle.addEventListener('click', function () {
            const isOpen = sidebar.classList.toggle('show');
            sidebarToggle.setAttribute('aria-expanded', String(isOpen));
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') closeSidebar();
        });
        document.addEventListener('click', function (event) {
            if (window.innerWidth < 768 && sidebar.classList.contains('show') &&
                !sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                closeSidebar();
            }
        });
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) closeSidebar();
        });
    }

    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('.alert.show').forEach(function (alert) {
            window.setTimeout(function () {
                if (alert.isConnected && alert.classList.contains('show')) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            }, 5000);
        });
    }
});
</script>
</body>
</html>
