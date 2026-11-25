<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: /cleaning/auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/csrf.php';
cleaning_require_post_csrf();

$BACK = '/cleaning/pages/permintaan.php';
$action = $_POST['action'] ?? '';

// ── 1. BUAT PERMINTAAN ─────────────────────────────────────────────────────
if ($action === 'buat_permintaan') {
    $id_barang  = (int)($_POST['id_barang'] ?? 0);
    $qty        = (int)($_POST['qty'] ?? 0);
    $tanggal    = $_POST['tanggal'] ?? date('Y-m-d');
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (!$id_barang || $qty < 1) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak lengkap.'];
        header('Location: ' . $BACK);
        exit;
    }

    $harga = $conn->query("SELECT harga_barang FROM barang WHERE id_barang = $id_barang")->fetch_assoc();
    $estimasi_harga = $harga['harga_barang'] ?? 0;
    $estimasi_total = $qty * $estimasi_harga;

    $stmt = $conn->prepare(
        "INSERT INTO permintaan (id_barang, id_user, qty, estimasi_harga, estimasi_total, tanggal, keterangan)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('iiiiiss', $id_barang, $_SESSION['id_user'], $qty, $estimasi_harga, $estimasi_total, $tanggal, $keterangan);

    if ($stmt->execute()) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Permintaan berhasil dicatat.'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal: ' . $conn->error];
    }
    $stmt->close();
    header('Location: ' . $BACK);
    exit;
}

// ── 2. CATAT BARANG MASUK (dari permintaan) ─────────────────────────────────
if ($action === 'catat_masuk') {
    $id_permintaan = (int)($_POST['id_permintaan'] ?? 0);
    $qty_masuk     = (int)($_POST['qty_masuk'] ?? 0);
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? date('Y-m-d');
    $notes         = trim($_POST['notes'] ?? '');

    if (!$id_permintaan || $qty_masuk < 1) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak lengkap.'];
        header('Location: ' . $BACK);
        exit;
    }

    $conn->begin_transaction();
    try {
        $req = $conn->prepare("SELECT p.id_barang, p.qty AS diminta, b.harga_barang
                               FROM permintaan p JOIN barang b ON b.id_barang=p.id_barang
                               WHERE p.id_permintaan=? FOR UPDATE");
        $req->bind_param('i', $id_permintaan);
        $req->execute(); $row=$req->get_result()->fetch_assoc(); $req->close();
        if (!$row) { throw new RuntimeException('Permintaan tidak ditemukan.'); }
        $id_barang=(int)$row['id_barang'];
        $harga_satuan=(int)$row['harga_barang'];
        $reqRef = 'REQ-' . $id_permintaan;
        $reqPattern = $reqRef . ' | %';
        $sum = $conn->prepare("SELECT COALESCE(SUM(qty),0) AS total FROM transaksi
                               WHERE id_barang=? AND jenis='masuk'
                               AND (notes=? OR notes LIKE ?)");
        $sum->bind_param('iss',$id_barang,$reqRef,$reqPattern);
        $sum->execute(); $sudah_masuk=(int)$sum->get_result()->fetch_assoc()['total']; $sum->close();
        $sisa=(int)$row['diminta']-$sudah_masuk;
        if ($qty_masuk > $sisa) { throw new RuntimeException("Jumlah masuk melebihi sisa ($sisa)."); }
        $notes_full = $reqRef . ($notes ? ' | ' . $notes : '');
        $total_harga=$qty_masuk*$harga_satuan;
        $id_user=(int)$_SESSION['id_user'];
        $stmt=$conn->prepare("INSERT INTO transaksi
            (id_barang,id_user,jenis,qty,harga_satuan,total_harga,tanggal,notes)
            VALUES (?,?, 'masuk', ?,?,?,?,?)");
        $stmt->bind_param('iiiiiss',$id_barang,$id_user,$qty_masuk,$harga_satuan,$total_harga,$tanggal_masuk,$notes_full);
        $stmt->execute(); $stmt->close();
        // after_transaksi_insert secara otomatis menambah cached saldo.
        $conn->commit();
        $_SESSION['flash']=['type'=>'success','msg'=>"Barang masuk {$qty_masuk} berhasil dicatat."];
    } catch (Throwable $e) {
        $conn->rollback();
        error_log('Penerimaan permintaan gagal: '.$e->getMessage());
        $_SESSION['flash']=['type'=>'danger','msg'=>$e instanceof RuntimeException ? $e->getMessage() : 'Gagal menyimpan penerimaan.'];
    }
    header('Location: ' . $BACK);
    exit;
}

// ── 3. HAPUS PERMINTAAN ─────────────────────────────────────────────────────
if ($action === 'hapus_permintaan') {
    $id = (int)($_POST['id_permintaan'] ?? 0);
    if ($id) {
        $ref = "REQ-{$id}";
        $refLike = $ref . ' | %';
        $guard = $conn->prepare("SELECT COUNT(*) AS n FROM transaksi WHERE jenis='masuk' AND (notes=? OR notes LIKE ?)");
        $guard->bind_param('ss', $ref, $refLike); $guard->execute();
        $jumlah = (int)$guard->get_result()->fetch_assoc()['n']; $guard->close();
        if ($jumlah > 0) {
            $_SESSION['flash'] = ['type'=>'warning','msg'=>'Permintaan sudah memiliki penerimaan; penghapusan diblokir.'];
        } else {
            $del = $conn->prepare('DELETE FROM permintaan WHERE id_permintaan=?');
            $del->bind_param('i',$id); $del->execute(); $del->close();
            $_SESSION['flash'] = ['type'=>'success','msg'=>'Permintaan dihapus.'];
        }
    }
    header('Location: ' . $BACK);
    exit;
}

// Endpoint ini hanya menerima buat_permintaan, catat_masuk, atau hapus_permintaan.
// Mutasi transaksi umum dipusatkan pada transaksi_action.php untuk memastikan
// guard session, CSRF, dan row lock yang sama.
$_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Aksi tidak dikenal.'];
header('Location: ' . $BACK);
exit;
?>
