<?php
// auth/logout.php
session_start();

// Hapus semua session variables
$_SESSION = array();

// Hapus session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// 🔥 PERBAIKAN: Gunakan session_start() dengan session_id baru
// dan jangan simpan logout_message di session dulu, gunakan GET parameter

// Redirect ke login dengan parameter message (lebih aman)
header("Location: /cleaning/auth/login.php?logout=success");
exit;
?>