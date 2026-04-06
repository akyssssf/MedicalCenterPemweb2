<?php
session_start();

// Hanya izinkan aksi jika berupa POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_destroy();
    header("Location: ../login.php");
    exit();
} else {
    // Jika ada yang iseng ngetik URL /proses/logout.php, tendang balik ke index
    header("Location: ../index.php");
    exit();
}
?>