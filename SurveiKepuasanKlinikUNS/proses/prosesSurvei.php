<?php
session_start();
include '../server/koneksi.php'; 

// CEK SESSION MENGGUNAKAN NIK
if (!isset($_SESSION['nik'])) {
    header("Location: ../login.php");
    exit();
}

// Kita masukkan NIK ke dalam kolom email di database
$email = $_SESSION['nik']; 
$kategori = $_POST['kategori'];
$q1 = (int)$_POST['q1'];
$q2 = (int)$_POST['q2'];
$q3 = (int)$_POST['q3'];
$q4 = (int)$_POST['q4'];
$saran = trim($_POST['saran']); 

// 1. Validasi Kategori Whitelist (Evaluasi 1D)
$kategori_valid = ['Mahasiswa', 'Dosen', 'Karyawan', 'Umum'];
if (!in_array($kategori, $kategori_valid)) {
    echo "<script>alert('Error: Data kategori tidak valid!'); window.location='../survei.php';</script>";
    exit();
}

// 2. Insert dengan Prepared Statement
$query = "INSERT INTO surveys (email, kategori, q1, q2, q3, q4, saran) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ssiiiis", $email, $kategori, $q1, $q2, $q3, $q4, $saran);

if (mysqli_stmt_execute($stmt)) {
    
    // UBAH STATUS KUNJUNGAN MENJADI 'Sudah'
    $id_kunjungan = $_SESSION['id_kunjungan_aktif'];
    mysqli_query($koneksi, "UPDATE kunjungan SET status_survei = 'Sudah' WHERE id = '$id_kunjungan'");
    
    // Hapus sesi kunjungan agar token hangus
    unset($_SESSION['id_kunjungan_aktif']);

    // Redirect ke Landing Page
    echo "<script>
            alert('🎉 Terima kasih! Survei kepuasan berhasil tersimpan. Penilaian Anda sangat berarti bagi kami.');
            window.location='../index.php';
          </script>";
} else {
    // INI YANG TADI HILANG
    echo "Gagal menyimpan survei: " . mysqli_error($koneksi);
}
mysqli_stmt_close($stmt);
?>