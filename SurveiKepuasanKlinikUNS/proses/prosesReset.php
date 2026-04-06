<?php
session_start();
include '../server/koneksi.php';

$nik = trim($_POST['nik']);
$no_hp = trim($_POST['no_hp']);
$password_baru = $_POST['password_baru'];

// 1. Validasi Keamanan Password Baru (Aturan yang sama dengan Register)
if (strlen($password_baru) < 6 || !preg_match("/[0-9]/", $password_baru) || !preg_match("/[A-Z]/", $password_baru)) {
    echo "<script>alert('Error: Password Baru tidak memenuhi syarat keamanan (Min 6 karakter, mengandung angka & huruf besar)!'); window.history.back();</script>";
    exit();
}

// 2. Cek apakah NIK dan No HP tersebut cocok/berada di akun yang sama
$query_cek = "SELECT id FROM users WHERE nik = ? AND no_hp = ?";
$stmt_cek = mysqli_prepare($koneksi, $query_cek);
mysqli_stmt_bind_param($stmt_cek, "ss", $nik, $no_hp);
mysqli_stmt_execute($stmt_cek);
mysqli_stmt_store_result($stmt_cek);

if (mysqli_stmt_num_rows($stmt_cek) === 1) {
    // 3. Jika cocok, Enkripsi password baru dan UPDATE ke database
    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
    
    $query_update = "UPDATE users SET password = ? WHERE nik = ?";
    $stmt_update = mysqli_prepare($koneksi, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ss", $password_hash, $nik);
    
    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>alert('✅ Berhasil! Password Anda telah direset. Silakan login menggunakan password baru.'); window.location='../login.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate password sistem: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
    }
    mysqli_stmt_close($stmt_update);

} else {
    // Jika tidak cocok
    echo "<script>alert('❌ Gagal: Kombinasi NIK dan Nomor WhatsApp tidak ditemukan di sistem kami!'); window.history.back();</script>";
}

mysqli_stmt_close($stmt_cek);
?>