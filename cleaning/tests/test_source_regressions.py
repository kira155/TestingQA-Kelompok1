"""Pemeriksaan statis perubahan Cleaning, tanpa database atau akses browser.

Bukan pengganti penetration test, integration test, atau E2E test.
"""
from pathlib import Path
import re
import unittest

ROOT = Path(__file__).resolve().parents[1]


def source(relative):
    return (ROOT / relative).read_text(encoding='utf-8')


class SourceRegressionChecks(unittest.TestCase):
    def test_footer_professional_dan_login(self):
        for file in ('includes/footer.php', 'auth/login.php'):
            text = source(file)
            self.assertIn('Cleaning Stock Management', text, file)
            self.assertNotRegex(text, r'(?i)created\s+by\s+kelompok\s*1', file)
            self.assertIn("date('Y')", text, file)

    def test_registrasi_role_ditentukan_server(self):
        text = source('auth/register.php')
        self.assertIn("$role = 'staff';", text)
        self.assertNotIn("$_POST['role']", text)
        self.assertNotIn('<select name="role"', text)
        self.assertIn('/cleaning/pages/dashboard.php', text)

    def test_setup_admin_hanya_cli(self):
        text = source('scripts/create_admin.php')
        self.assertIn("PHP_SAPI !== 'cli'", text)
        self.assertIn('http_response_code(403)', text)
        self.assertLess(text.find("PHP_SAPI !== 'cli'"), text.find("require_once __DIR__ . '/../config/db.php'"))
        self.assertNotIn('Password: <strong>', text)

    def test_sidebar_semantik_dan_navigasi_mobile(self):
        self.assertIn('aria-current=', source('includes/sidebar.php'))
        text = source('includes/footer.php')
        for keyword in ('aria-expanded', 'Escape', 'sidebar.classList.toggle', 'sidebar.contains'):
            self.assertIn(keyword, text)

    def test_database_mendukung_env_dan_error_generik(self):
        text = source('config/db.php')
        self.assertIn("getenv('CLEANING_DB_USER')", text)
        self.assertIn("getenv('CLEANING_DB_PASS')", text)
        self.assertIn('http_response_code(503)', text)
        self.assertNotIn('die("Koneksi database gagal:', text)


    def test_mutasi_melindungi_akses_dan_csrf(self):
        for file in ('actions/barang_action.php', 'actions/permintaan_action.php',
                     'actions/transaksi_action.php', 'actions/user_action.php', 'actions/admin_action.php'):
            self.assertIn('cleaning_require_post_csrf();', source(file), file)
        self.assertIn('requireLogin();', source('actions/transaksi_action.php'))
        self.assertIn('hash_equals', source('includes/csrf.php'))

    def test_stock_and_history_are_protected_in_source(self):
        barang = source('pages/barang.php')
        self.assertIn('FROM transaksi GROUP BY id_barang', barang)
        self.assertIn('FROM permintaan GROUP BY id_barang', barang)
        action = source('actions/barang_action.php')
        self.assertIn('stok_awal, stok_sekarang, gambar', action)
        self.assertIn('Hapus diblokir', action)
        self.assertIn('FOR UPDATE', source('actions/transaksi_action.php'))
        self.assertIn("$reqPattern = $reqRef . ' | %';", source('actions/permintaan_action.php'))
        self.assertIn('FOR UPDATE', source('actions/permintaan_action.php'))
        self.assertNotIn("if ($action === 'input_transaksi')", source('actions/permintaan_action.php'))
        self.assertNotIn("LIKE '%REQ-{$id_permintaan}%'", source('actions/permintaan_action.php'))
        self.assertNotIn('DELETE FROM transaksi', source('actions/admin_action.php'))


if __name__ == '__main__':
    unittest.main()
