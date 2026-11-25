<?php
// auth/register.php - Halaman Registrasi User
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_user'])) {
    header("Location: /cleaning/pages/dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/csrf.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    cleaning_require_post_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    // Registrasi publik hanya boleh menghasilkan akun staff.
    // Hak admin ditetapkan oleh admin aktif melalui Kelola User.
    $role = 'staff';
    
    // Validasi
    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        // Cek username sudah ada atau belum
        $check_sql = "SELECT id_user FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user baru
            $insert_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $username, $hashed_password, $role);
            
            if ($insert_stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
                // Kosongkan form
                $username = '';
            } else {
                $error = 'Registrasi belum berhasil. Silakan coba kembali atau hubungi administrator.';
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Cleaning Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .register-container {
            max-width: 450px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: none;
            text-align: center;
            padding: 2rem 2rem 0 2rem;
        }
        
        .card-header h3 {
            font-weight: 700;
            color: #1e2a3a;
            margin-bottom: 0.5rem;
        }
        
        .card-header p {
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .logo-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="card">
                <div class="card-header">
                    <div class="logo-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Daftar Akun Baru</h3>
                    <p>Bergabunglah dengan sistem inventaris kami</p>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <?= cleaning_csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Username
                            </label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                                   placeholder="Masukkan username" required>
                            <small class="text-muted">Minimal 3 karakter</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Masukkan password" required>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-check-circle"></i> Konfirmasi Password
                            </label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   placeholder="Ulangi password" required>
                        </div>
                        
                        <div class="mb-3 text-muted small">
                            <i class="fas fa-user-shield" aria-hidden="true"></i>
                            Akun baru didaftarkan sebagai Staff. Akses Admin dikelola melalui administrator sistem.
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-register">
                            <i class="fas fa-user-plus"></i> Daftar
                        </button>
                    </form>
                    
                    <div class="login-link">
                        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>