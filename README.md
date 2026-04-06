# 🏥 Sistem Survei Kepuasan Pasien - UNS Medical Center

![PHP](https://img.shields.io/badge/PHP-Native-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

Sebuah aplikasi web dinamis untuk mengumpulkan umpan balik dan evaluasi layanan dari pasien di UNS Medical Center. Proyek ini dibangun menggunakan **PHP Native**, **MySQL**, dan **Tailwind CSS**, dengan fokus pada keamanan sistem (Autentikasi berlapis, Password Hashing, CSRF Protection) dan antarmuka pengguna (UI/UX) yang responsif.

Proyek ini dikembangkan sebagai bagian dari Praktikum Pemrograman Web - D3 Teknik Informatika Universitas Sebelas Maret (UNS).

## ✨ Fitur Utama

- **Laman Publik Interaktif:** Landing page informatif mengenai layanan poli klinik.
- **Sistem Autentikasi Aman:** - Login & Registrasi dengan validasi ketat (Wajib 16 digit NIK, indikator kekuatan password *real-time*).
  - Enkripsi kata sandi menggunakan `password_hash`.
  - Perlindungan *Session Hijacking* dan *CSRF Token*.
- **Reset Password (Lupa Sandi):** Pemulihan akun mandiri dengan mencocokkan kombinasi NIK dan Nomor WhatsApp.
- **Verifikasi Kunjungan Pasien:** - **Jalur Token:** Menggunakan kode unik struk.
  - **Jalur Manual:** Mencocokkan NIK pasien dengan tanggal kunjungan di *database*.
- **Dynamic Survey Form:** Formulir kuesioner dengan *progress bar* dinamis dan *rating card*.

## 🛠️ Prasyarat Instalasi

Pastikan lingkungan pengembanganmu sudah memiliki:
- **XAMPP / Laragon** (PHP 7.4+ & MySQL)
- Web Browser modern (Chrome, Edge, Firefox)
- Koneksi internet (untuk memuat Tailwind CSS via CDN)

## 🚀 Cara Instalasi & Menjalankan Aplikasi

1. **Clone Repositori**
   ```bash
   git clone [https://github.com/imamabidin999-maker/Praktikum-3-Pemrograman-Web.git](https://github.com/imamabidin999-maker/Praktikum-3-Pemrograman-Web.git)
