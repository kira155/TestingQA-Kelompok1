<?php
// auth/login.php - Halaman Login Cleaning Service
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['id_user']) && !isset($_GET['logout'])) {
    header("Location: /cleaning/pages/dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/db.php';

$error = '';
$success = '';

// Tampilkan pesan logout jika ada
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $success = 'Anda telah logout. Silakan login kembali.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi!';
    } else {
        $sql = "SELECT id_user, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_time'] = time();
                
                header("Location: /cleaning/pages/dashboard.php");
                exit();
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Username tidak ditemukan!';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Cleaning Stock Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            padding: 0;
            margin: 0;
            color: #1a1f36;
            box-sizing: border-box;
            word-wrap: break-word;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;
        }
        
        body {
            min-height: 100%;
            background-color: #ffffff;
        }
        
        h1 {
            letter-spacing: -1px;
        }
        
        a {
            color: #5469d4;
            text-decoration: unset;
        }
        
        .login-root {
            background: #fff;
            display: flex;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }
        
        .loginbackground {
            min-height: 692px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            top: 0;
            z-index: 0;
            overflow: hidden;
        }
        
        .flex-flex {
            display: flex;
        }
        
        .align-center {
            align-items: center; 
        }
        
        .center-center {
            align-items: center;
            justify-content: center;
        }
        
        .box-root {
            box-sizing: border-box;
        }
        
        .flex-direction--column {
            flex-direction: column;
        }
        
        .loginbackground-gridContainer {
            display: grid;
            grid-template-columns: [start] 1fr [left-gutter] repeat(16,86.6px) [left-gutter] 1fr [end];
            grid-template-rows: [top] 1fr [top-gutter] repeat(8,64px) [bottom-gutter] 1fr [bottom];
            justify-content: center;
            margin: 0 -2%;
            transform: rotate(-12deg) skew(-12deg);
        }
        
        .box-divider--light-all-2 {
            box-shadow: inset 0 0 0 2px #e3e8ee;
        }
        
        .box-background--blue {
            background-color: #00b4d8;
        }
        
        .box-background--white {
            background-color: #ffffff; 
        }
        
        .box-background--blue800 {
            background-color: #023e8a;
        }
        
        .box-background--gray100 {
            background-color: #e3e8ee;
        }
        
        .box-background--cyan200 {
            background-color: #48cae4;
        }
        
        .padding-top--64 {
            padding-top: 64px;
        }
        
        .padding-top--24 {
            padding-top: 24px;
        }
        
        .padding-top--48 {
            padding-top: 48px;
        }
        
        .padding-bottom--24 {
            padding-bottom: 24px;
        }
        
        .padding-horizontal--48 {
            padding: 48px;
        }
        
        .padding-bottom--15 {
            padding-bottom: 15px;
        }
        
        .flex-justifyContent--center {
            justify-content: center;
        }
        
        .formbg {
            margin: 0px auto;
            width: 100%;
            max-width: 448px;
            background: white;
            border-radius: 8px;
            box-shadow: rgba(60, 66, 87, 0.12) 0px 7px 14px 0px, rgba(0, 0, 0, 0.12) 0px 3px 6px 0px;
            position: relative;
            z-index: 10;
        }
        
        .formbg-inner {
            padding: 48px;
        }
        
        span.title {
            display: block;
            font-size: 24px;
            line-height: 32px;
            color: #1a1f36;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .subtitle {
            font-size: 16px;
            line-height: 24px;
            color: #697386;
            margin-bottom: 32px;
        }
        
        .field {
            margin-bottom: 24px;
        }
        
        label {
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            display: block;
            color: #1a1f36;
        }
        
        .field input {
            font-size: 16px;
            line-height: 28px;
            padding: 12px 16px;
            width: 100%;
            min-height: 48px;
            border: unset;
            border-radius: 6px;
            outline-color: #00b4d8;
            background-color: rgb(255, 255, 255);
            box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(60, 66, 87, 0.16) 0px 0px 0px 1px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px;
            transition: all 0.3s ease;
        }
        
        .field input:focus {
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.1), 
                        rgba(60, 66, 87, 0.16) 0px 0px 0px 1px;
        }
        
        input[type="submit"] {
            background: linear-gradient(135deg, #00b4d8 0%, #023e8a 100%);
            box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0.12) 0px 1px 1px 0px, 
                        #00b4d8 0px 0px 0px 1px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(0, 0, 0, 0) 0px 0px 0px 0px, 
                        rgba(60, 66, 87, 0.08) 0px 2px 5px 0px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            border: none;
            padding: 14px;
            font-size: 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 180, 216, 0.3);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 5px;
            transition: color 0.3s;
        }
        
        .toggle-password:hover {
            color: #00b4d8;
        }
        
        .footer-link {
            margin-top: 32px;
            text-align: center;
        }
        
        .footer-link span {
            font-size: 14px;
            color: #697386;
        }
        
        .footer-link a {
            color: #00b4d8;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 6px;
            padding: 16px;
            border-left: 4px solid #ef4444;
            background: linear-gradient(135deg, #fee, #fdd);
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            animation: slideIn 0.3s ease;
        }
        
        .alert-success {
            border-left-color: #10b981;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }
        
        .alert i {
            font-size: 20px;
        }
        
        .credit-text {
            font-size: 14px;
            color: #64748b;
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            background: rgba(0, 180, 216, 0.05);
            border-radius: 6px;
            border: 1px solid rgba(0, 180, 216, 0.1);
        }
        
        .credit-text i {
            color: #00b4d8;
            margin-right: 6px;
        }

        .credit-brand {
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 0.1px;
        }

        .credit-subtitle {
            margin-top: 5px;
            font-size: 12px;
            color: #64748b;
        }

        .credit-copyright {
            margin-top: 10px;
            padding-top: 9px;
            border-top: 1px solid rgba(0, 180, 216, 0.12);
            font-size: 11px;
            color: #94a3b8;
        }
        
        .btn-portal {
            display: block;
            width: 100%;
            padding: 12px;
            text-align: center;
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            margin-top: 12px;
        }
        
        .btn-portal:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }
        
        .btn-register {
            display: inline-block;
            padding: 12px 24px;
            background: white;
            color: #00b4d8;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #00b4d8;
            margin-top: 16px;
        }
        
        .btn-register:hover {
            background: #00b4d8;
            color: white;
            transform: translateY(-2px);
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animationRightLeft {
            animation: animationRightLeft 20s ease-in-out infinite;
        }
        
        .animationLeftRight {
            animation: animationLeftRight 20s ease-in-out infinite;
        }
        
        .tans3s {
            animation: animationLeftRight 30s ease-in-out infinite;
        }
        
        .tans4s {
            animation: animationLeftRight 40s ease-in-out infinite;
        }
        
        @keyframes animationLeftRight {
            0% {
                transform: translateX(0px) rotate(-12deg) skew(-12deg);
            }
            50% {
                transform: translateX(500px) rotate(-12deg) skew(-12deg);
            }
            100% {
                transform: translateX(0px) rotate(-12deg) skew(-12deg);
            }
        } 
        
        @keyframes animationRightLeft {
            0% {
                transform: translateX(0px) rotate(-12deg) skew(-12deg);
            }
            50% {
                transform: translateX(-500px) rotate(-12deg) skew(-12deg);
            }
            100% {
                transform: translateX(0px) rotate(-12deg) skew(-12deg);
            }
        }
        
        @media (max-width: 768px) {
            .formbg-inner {
                padding: 32px 24px;
            }
            
            .loginbackground-gridContainer {
                display: none;
            }
            
            .loginbackground {
                background: linear-gradient(135deg, #00b4d8 0%, #023e8a 100%);
            }
        }
        
        @media (max-width: 480px) {
            .formbg {
                margin: 20px;
            }
            
            .formbg-inner {
                padding: 24px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-root">
        <div class="loginbackground box-background--white padding-top--64">
            <div class="loginbackground-gridContainer">
                <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
                    <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
                    </div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
                    <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
                    <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
                    <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
                    <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
                    <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
                    <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
                    <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
                    <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
                </div>
            </div>
        </div>
        
        <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
            <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
                <h1><a href="../index.php" style="color: #00b4d8; text-decoration: none;">
                    <i class="fas fa-broom"></i> Cleaning Service System
                </a></h1>
            </div>
            
            <div class="formbg-outer">
                <div class="formbg">
                    <div class="formbg-inner">
                        <span class="title">Cleaning Service Access Portal</span>
                        <p class="subtitle">Silakan masuk dengan kredensial Anda</p>
                        
                        <?php if($error): ?>
                            <div class="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span><?php echo htmlspecialchars($error); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo htmlspecialchars($success); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="field">
                                <label for="username">Username</label>
                                <input type="text" 
                                       id="username" 
                                       name="username" 
                                       placeholder="Masukkan username" 
                                       required 
                                       autofocus>
                            </div>
                            
                            <div class="field">
                                <label for="password">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Masukkan password" 
                                           required>
                                    <button type="button" class="toggle-password" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="field">
                                <input type="submit" name="login" value="Login">
                            </div>
                            
                            <a href="../index.php" class="btn-portal">
                                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                            </a>
                            
                            <div style="text-align: center;">
                                <a href="register.php" class="btn-register">
                                    <i class="fas fa-user-plus"></i> Belum punya akun? Daftar
                                </a>
                            </div>
                        </form>
                        
                        <footer class="footer-link" aria-label="Informasi aplikasi">
                            <div class="credit-text">
                                <div class="credit-brand"><i class="fas fa-boxes-stacked" aria-hidden="true"></i> Cleaning Stock Management</div>
                                <div class="credit-subtitle">Sistem Inventaris &amp; Operasional</div>
                                <div class="credit-copyright">&copy; <?= date('Y') ?> Cleaning Stock Management</div>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if(togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        }
        
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    if(alert.parentNode) alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>