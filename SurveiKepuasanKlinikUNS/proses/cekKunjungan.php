<?php
session_start();
include '../server/koneksi.php';

// Cek apakah user benar-benar login
if (!isset($_SESSION['nik'])) {
    header("Location: ../login.php");
    exit();
}

$nik = $_SESSION['nik'];
$jalur = $_POST['jalur'];

// Logika 1: Jika user milih Jalur Token
if ($jalur === 'token') {
    $token = trim($_POST['token']);
    $query = "SELECT id, status_survei FROM kunjungan WHERE token = ? AND nik_pasien = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $token, $nik);
} 
// Logika 2: Jika user milih Jalur Manual (Tanggal)
elseif ($jalur === 'manual') {
    $tanggal = $_POST['tanggal'];
    // Cari kunjungan di tanggal tersebut (Ambil 1 saja)
    $query = "SELECT id, status_survei FROM kunjungan WHERE tgl_kunjungan = ? AND nik_pasien = ? LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $tanggal, $nik);
} else {
    header("Location: ../dashboard.php");
    exit();
}

// Eksekusi pencarian di database
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Cek apakah sudah pernah diisi?
    if ($row['status_survei'] === 'Sudah') {
        echo "<script>alert('⚠️ Survei untuk kunjungan ini SUDAH Anda isi sebelumnya. Terima kasih!'); window.location='../dashboard.php';</script>";
    } else {
        // Lolos Verifikasi! Simpan ID kunjungan sementara, lalu buka gembok form survei
        $_SESSION['id_kunjungan_aktif'] = $row['id'];
        header("Location: ../survei.php");
    }
} else {
    // Jika data tidak ada atau NIK tidak cocok
    echo "<script>alert('❌ Gagal: Data kunjungan tidak ditemukan atau tidak cocok dengan NIK Anda.'); window.location='../dashboard.php';</script>";
}

mysqli_stmt_close($stmt);
?>