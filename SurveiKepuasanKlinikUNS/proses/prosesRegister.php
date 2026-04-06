<?php
session_start();
include '../server/koneksi.php';

$nik      = trim($_POST['nik']);
$nama     = trim($_POST['nama']);
$no_hp    = trim($_POST['no_hp']);
$password = $_POST['password'];

// 1. Validasi NIK Harus TEPAT 16 Digit
if (strlen($nik) !== 16) {
    echo "<script>alert('Error: NIK harus tepat 16 digit!'); window.history.back();</script>";
    exit();
}

// 2. Validasi Password (Angka, Huruf Besar, Min 6)
if (strlen($password) < 6 || !preg_match("/[0-9]/", $password) || !preg_match("/[A-Z]/", $password)) {
    echo "<script>alert('Error: Password tidak memenuhi syarat keamanan!'); window.history.back();</script>";
    exit();
}

// 3. Cek Duplikasi (NIK atau No HP)
$stmt_cek = mysqli_prepare($koneksi, "SELECT id FROM users WHERE nik = ? OR no_hp = ?");
mysqli_stmt_bind_param($stmt_cek, "ss", $nik, $no_hp);
mysqli_stmt_execute($stmt_cek);
mysqli_stmt_store_result($stmt_cek);

if (mysqli_stmt_num_rows($stmt_cek) > 0) {
    echo "<script>alert('Gagal: NIK atau Nomor HP sudah terdaftar!'); window.history.back();</script>";
    exit();
}
mysqli_stmt_close($stmt_cek);

// 4. Proses Simpan
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt_insert = mysqli_prepare($koneksi, "INSERT INTO users (nik, nama, no_hp, password) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt_insert, "ssss", $nik, $nama, $no_hp, $password_hash);

if (mysqli_stmt_execute($stmt_insert)){
    echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='../login.php';</script>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
mysqli_stmt_close($stmt_insert);
?>