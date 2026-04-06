<?php
session_start();
include '../server/koneksi.php';

// Identitas bisa berisi NIK atau No HP
$identitas = trim($_POST['identitas']); 
$password = $_POST['password'];

// Cari user berdasarkan NIK atau No HP
$query = "SELECT * FROM users WHERE nik = ? OR no_hp = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ss", $identitas, $identitas);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Simpan sesi menggunakan NIK
        $_SESSION['nik'] = $user['nik'];
        $_SESSION['nama'] = $user['nama'];
        header("Location: ../dashboard.php"); // Arahkan ke dashboard pasien
        exit();
    } else {
        echo "<script>alert('Password salah!'); window.location='../login.php';</script>";
    }
} else {
    echo "<script>alert('Data Pasien tidak ditemukan!'); window.location='../login.php';</script>";
}
mysqli_stmt_close($stmt);
?>