<?php
$pageTitle = 'Kelola User';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/auth.php';
requireAdmin();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

// Ambil data users
$result = $conn->query("SELECT * FROM users ORDER BY role, username");
if (!$result) {
    die("Error query: " . $conn->error);
}
$users = $result->fetch_all(MYSQLI_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<div id="main-wrapper">
    <div id="topbar">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="page-title">Kelola User</div>
        <div class="user-badge">
            <i class="bi bi-person-circle"></i>
            <?= htmlspecialchars($_SESSION['username']) ?>
            <span class="role-badge bg-primary text-white">admin</span>
        </div>
    </div>

    <div id="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show shadow-sm">
            <i class="bi bi-info-circle me-2"></i><?= $flash['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="mb-3 text-end">
            <!-- Tombol Tambah User dengan data-bs-toggle -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-person-plus me-1"></i>Tambah User
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-people me-2 text-primary"></i>Daftar User
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Username</th>
                                <th width="100">Role</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox me-2"></i>Tidak ada data user
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $i => $u): ?>
                            <tr>
                                <td class="text-muted small"><?= $i+1 ?></td>
                                <td class="fw-medium">
                                    <?= htmlspecialchars($u['username']) ?>
                                    <?php if ($u['id_user'] == $_SESSION['id_user']): ?>
                                        <span class="badge bg-secondary ms-1">Anda</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $u['role']==='admin'?'bg-primary':'bg-success' ?>">
                                        <?= htmlspecialchars($u['role']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- Reset Password -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning reset-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalReset"
                                                data-id="<?= $u['id_user'] ?>"
                                                data-nama="<?= htmlspecialchars($u['username']) ?>">
                                            <i class="bi bi-key"></i>
                                        </button>
                                        
                                        <!-- Hapus -->
                                        <?php if ($u['id_user'] != $_SESSION['id_user']): ?>
                                        <form method="POST" action="/cleaning/actions/user_action.php" 
                                              onsubmit="return confirm('Hapus user <?= htmlspecialchars($u['username']) ?>
                <?= cleaning_csrf_field() ?>?')"
                                              style="display: inline;">
                                            <input type="hidden" name="action" value="hapus_user">
                                            <input type="hidden" name="id_user" value="<?= $u['id_user'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
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

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/cleaning/actions/user_action.php">
                <?= cleaning_csrf_field() ?>
                <input type="hidden" name="action" value="tambah_user">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" 
                               minlength="6" required placeholder="Min. 6 karakter">
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="modalReset" tabindex="-1" aria-labelledby="modalResetLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/cleaning/actions/user_action.php">
                <?= cleaning_csrf_field() ?>
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="id_user" id="resetId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResetLabel">Reset Password — <span id="resetNama"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" 
                               minlength="6" required placeholder="Min. 6 karakter">
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Pastikan Bootstrap modal berfungsi dengan benar
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Cek apakah Bootstrap tersedia
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS tidak ditemukan!');
        alert('Peringatan: Bootstrap JS tidak ditemukan. Modal mungkin tidak berfungsi.');
    } else {
        console.log('Bootstrap JS tersedia');
    }
    
    // Event handler untuk modal reset password
    var modalReset = document.getElementById('modalReset');
    if (modalReset) {
        modalReset.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            if (button && button.classList.contains('reset-btn')) {
                var id = button.getAttribute('data-id');
                var nama = button.getAttribute('data-nama');
                
                document.getElementById('resetId').value = id;
                document.getElementById('resetNama').textContent = nama;
                
                console.log('Reset password untuk:', nama, 'ID:', id);
            }
        });
    }
    
    // Validasi form tambah user
    var formTambah = document.querySelector('#modalTambah form');
    if (formTambah) {
        formTambah.addEventListener('submit', function(e) {
            var username = this.querySelector('[name="username"]').value.trim();
            var password = this.querySelector('[name="password"]').value;
            
            if (!username) {
                e.preventDefault();
                alert('Username harus diisi!');
                return false;
            }
            
            if (!password || password.length < 6) {
                e.preventDefault();
                alert('Password harus diisi minimal 6 karakter!');
                return false;
            }
            
            console.log('Form tambah user valid, akan disubmit');
        });
    }
    
    // Validasi form reset password
    var formReset = document.querySelector('#modalReset form');
    if (formReset) {
        formReset.addEventListener('submit', function(e) {
            var password = this.querySelector('[name="password"]').value;
            
            if (!password || password.length < 6) {
                e.preventDefault();
                alert('Password baru harus diisi minimal 6 karakter!');
                return false;
            }
            
            console.log('Form reset password valid, akan disubmit');
        });
    }
    
    // Test tombol tambah
    var btnTambah = document.querySelector('[data-bs-target="#modalTambah"]');
    if (btnTambah) {
        console.log('Tombol tambah ditemukan');
        btnTambah.addEventListener('click', function() {
            console.log('Tombol tambah diklik');
        });
    } else {
        console.error('Tombol tambah tidak ditemukan!');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>