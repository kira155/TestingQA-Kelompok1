<?php
// actions/barang_action.php
session_start();

// PERBAIKAN: Path yang benar ke config
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/auth.php';
requireAdmin();
require_once __DIR__ . '/../includes/csrf.php';
cleaning_require_post_csrf();

$action = $_POST['action'] ?? '';

// Function upload gambar
function uploadGambar($file, $old_image = null) {
    // Jika tidak ada file yang diupload
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return $old_image;
    }
    
    // Jika ada error lain
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Error upload file: ' . $file['error']];
        return false;
    }
    
    // Validasi ekstensi
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed, true)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Format gambar tidak didukung (JPG, JPEG, PNG, GIF, WEBP)'];
        return false;
    }
    
    // Bukan hanya ekstensi: pastikan isinya gambar asli.
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $mimeMap = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','webp'=>'image/webp'];
    if (($mimeMap[$ext] ?? null) !== $mime) {
        $_SESSION['flash'] = ['type'=>'danger','msg'=>'Isi file tidak sesuai format gambar.'];
        return false;
    }

    // Validasi ukuran (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Ukuran gambar maksimal 2MB'];
        return false;
    }
    
    // Path upload yang benar (dari root project)
    $new_filename = time() . '_' . uniqid() . '.' . $ext;
    $upload_path = __DIR__ . '/../uploads/';
    
    // Buat folder jika belum ada
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $upload_path . $new_filename)) {
        // Hapus gambar lama jika ada dan berbeda
        if ($old_image && basename($old_image) === $old_image && $old_image !== 'null' && $old_image !== 'NULL' && !empty($old_image) && $old_image !== $new_filename) {
            $old_file = $upload_path . $old_image;
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }
        return $new_filename;
    }
    
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal mengupload gambar'];
    return false;
}

if ($action === 'tambah_barang') {
    $nama = trim($_POST['nama_barang']);
    $kategori = $_POST['kategori'];
$harga = isset($_POST['harga_barang']) && $_POST['harga_barang'] !== ''
    ? (int)$_POST['harga_barang']
    : 0;
    $satuan = $_POST['satuan'];
    $stok_awal = (int) ($_POST['stok_awal'] ?? 0);
    if ($stok_awal < 0 || $harga < 0 || !in_array($kategori, ['chemical','peralatan'], true) || $nama === '') {
        $_SESSION['flash'] = ['type'=>'danger', 'msg'=>'Nama, kategori, stok dan harga harus valid.'];
        header('Location: /cleaning/pages/barang.php'); exit;
    }

    // Upload gambar
    $gambar = uploadGambar($_FILES['gambar']);
    
    if ($gambar === false && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        header('Location: /cleaning/pages/barang.php');
        exit;
    }
    
    // Jika tidak ada gambar, set NULL
    if (empty($gambar) || $gambar === 'null') {
        $gambar = NULL;
    }
    
    $stmt = $conn->prepare("INSERT INTO barang (nama_barang, kategori, harga_barang, satuan, stok_awal, stok_sekarang, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisiis", $nama, $kategori, $harga, $satuan, $stok_awal, $stok_awal, $gambar);
    
    if ($stmt->execute()) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Barang berhasil ditambahkan'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal menambahkan barang: ' . $stmt->error];
    }
    $stmt->close();
    
} elseif ($action === 'edit_barang') {
    $id = (int) $_POST['id_barang'];
    $nama = trim($_POST['nama_barang']);
    $kategori = $_POST['kategori'];
   $harga = isset($_POST['harga_barang']) && $_POST['harga_barang'] !== ''
    ? (int)$_POST['harga_barang']
    : 0;
    $satuan = $_POST['satuan'];
    $stok_awal = (int) $_POST['stok_awal'];
    $q = $conn->prepare('SELECT gambar FROM barang WHERE id_barang=?');
    $q->bind_param('i',$id); $q->execute();
    $gambar_lama = $q->get_result()->fetch_assoc()['gambar'] ?? null;
    $q->close();
    
    // Validasi stok_awal tidak boleh negatif
    if ($stok_awal < 0) {
        $stok_awal = 0;
    }
    
    // Debug log (akan muncul di error log server)
    error_log("Edit Barang - ID: $id, Nama: $nama, Satuan: $satuan, Stok Awal: $stok_awal");
    
    // Cek apakah ada file baru yang diupload
    $ada_file_baru = isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE;
    
    if ($ada_file_baru) {
        // Ada file baru
        $gambar_baru = uploadGambar($_FILES['gambar'], $gambar_lama);
        
        if ($gambar_baru === false) {
            header('Location: /cleaning/pages/barang.php');
            exit;
        }
        
        // Update dengan gambar baru
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=?, kategori=?, harga_barang=?, satuan=?, stok_sekarang=stok_sekarang + (? - stok_awal), stok_awal=?, gambar=? WHERE id_barang=?");
        $stmt->bind_param("ssisiisi", $nama, $kategori, $harga, $satuan, $stok_awal, $stok_awal, $gambar_baru, $id);
    } else {
        // Update tanpa gambar
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=?, kategori=?, harga_barang=?, satuan=?, stok_sekarang=stok_sekarang + (? - stok_awal), stok_awal=? WHERE id_barang=?");
        $stmt->bind_param("ssisiii", $nama, $kategori, $harga, $satuan, $stok_awal, $stok_awal, $id);
    }
    
    if ($stmt->execute()) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Barang berhasil diperbarui'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal memperbarui barang: ' . $stmt->error];
    }
    $stmt->close();
    
} elseif ($action === 'hapus_barang') {
    $id = (int) ($_POST['id_barang'] ?? 0);
    $check = $conn->prepare("SELECT (SELECT COUNT(*) FROM transaksi WHERE id_barang=?) +
                                    (SELECT COUNT(*) FROM permintaan WHERE id_barang=?) AS dipakai");
    $check->bind_param('ii', $id, $id);
    $check->execute();
    $count = (int)($check->get_result()->fetch_assoc()['dipakai'] ?? 0);
    $check->close();
    if ($count > 0) {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Barang memiliki riwayat transaksi atau permintaan. Hapus diblokir untuk menjaga audit trail.'];
        header('Location: /cleaning/pages/barang.php'); exit;
    }
    $stmt = $conn->prepare('SELECT gambar FROM barang WHERE id_barang=?');
    $stmt->bind_param('i',$id); $stmt->execute();
    $gambar = $stmt->get_result()->fetch_assoc()['gambar'] ?? null;
    $stmt->close();
    $stmt = $conn->prepare('DELETE FROM barang WHERE id_barang=?');
    $stmt->bind_param('i',$id);
    if ($stmt->execute() && $stmt->affected_rows === 1) {
        if ($gambar && basename($gambar) === $gambar) {
            $filepath = __DIR__ . '/../uploads/' . $gambar;
            if (is_file($filepath)) { unlink($filepath); }
        }
        $_SESSION['flash'] = ['type'=>'success', 'msg'=>'Barang berhasil dihapus.'];
    } else {
        $_SESSION['flash'] = ['type'=>'danger', 'msg'=>'Barang tidak ditemukan atau gagal dihapus.'];
    }
    $stmt->close();
}

header('Location: /cleaning/pages/barang.php');
exit;
?>