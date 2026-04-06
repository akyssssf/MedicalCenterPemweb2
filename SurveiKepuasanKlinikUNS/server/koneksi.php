<?php
// MENCEGAH SESSION HIJACKING: Amankan cookie session
ini_set('session.cookie_httponly', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "uns_medicalcenter"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Error Database Asli: " . mysqli_connect_error());
}

?>