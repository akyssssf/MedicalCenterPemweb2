<?php
session_start();
// Wajib login untuk masuk ke sini
if (!isset($_SESSION['nik'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Pasien — UNS Medical Center</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    /* Menggunakan library CSS kita sebelumnya */
    *{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}
    body{background:linear-gradient(135deg,#dbeafe 0%,#e0f2fe 50%,#f0fdf4 100%);min-height:100vh;overflow-x:hidden;}
    .blob{position:fixed;border-radius:50%;filter:blur(60px);pointer-events:none;z-index:0;}
    .clay-card{background:#fff;border-radius:28px; box-shadow:8px 8px 24px rgba(99,149,210,.22),-2px -2px 8px rgba(255,255,255,.9), inset 3px 3px 8px rgba(255,255,255,.85),inset -3px -3px 8px rgba(180,210,245,.35); border:1.5px solid rgba(255,255,255,.75);}
    .clay-btn{border-radius:16px;font-weight:700;border:none;cursor:pointer; box-shadow:5px 5px 14px rgba(59,130,246,.35),-1px -1px 5px rgba(255,255,255,.7), inset 2px 2px 5px rgba(255,255,255,.4),inset -2px -2px 5px rgba(37,99,235,.25); transition:all .18s ease;}
    .clay-btn:hover{transform:translateY(-2px) scale(1.01);}
    .clay-input{background:#f1f8ff;border:1.5px solid rgba(147,197,253,.6);border-radius:14px; padding:.75rem 1rem;width:100%;outline:none;font-size:.95rem;}
    .clay-nav{background:rgba(255,255,255,.82);backdrop-filter:blur(16px);border-bottom:1.5px solid rgba(255,255,255,.6);box-shadow:0 4px 20px rgba(99,149,210,.12);}
  </style>
</head>
<body class="relative">
  <div class="blob w-80 h-80 bg-blue-200 opacity-35" style="top:-5rem;left:-5rem;"></div>

  <nav class="clay-nav sticky top-0 z-50">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="font-extrabold text-gray-800">Dashboard Pasien</div>
      <div class="flex items-center gap-3">
        <span class="text-xs font-bold text-gray-600">👤 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <button onclick="document.getElementById('form-logout').submit();" class="text-xs text-red-500 font-bold hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Logout</button>
      </div>
    </div>
  </nav>

  <main class="max-w-4xl mx-auto px-4 py-8 relative z-10">
    <div class="text-center mb-8">
      <h1 class="text-2xl font-extrabold text-gray-800 mb-2">Pilih Riwayat Kunjungan</h1>
      <p class="text-sm text-gray-500">Untuk menjaga keaslian data, silakan pilih kunjungan medis yang ingin Anda evaluasi.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      
      <div class="clay-card p-6 flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-2xl mb-4 shadow-inner">🎫</div>
          <h2 class="font-bold text-gray-800 text-lg mb-1">Jalur Cepat (Punya Struk)</h2>
          <p class="text-xs text-gray-500 mb-5 leading-relaxed">Masukkan kode Token yang tertera pada bagian bawah struk kunjungan klinik Anda hari ini.</p>
        </div>
        
        <form action="proses/cekKunjungan.php" method="POST" class="space-y-3">
          <input type="hidden" name="jalur" value="token">
          <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">Kode Token Kunjungan</label>
            <input type="text" name="token" class="clay-input uppercase" placeholder="Contoh: TKN-001" required>
          </div>
          <button type="submit" class="clay-btn w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm">Verifikasi Token</button>
        </form>
      </div>

      <div class="clay-card p-6 flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-2xl mb-4 shadow-inner">📅</div>
          <h2 class="font-bold text-gray-800 text-lg mb-1">Cek Manual Riwayat</h2>
          <p class="text-xs text-gray-500 mb-5 leading-relaxed">Struk hilang? Tidak masalah. Silakan pilih tanggal Anda berobat, sistem akan mencocokkannya dengan database medis kami.</p>
        </div>

        <form action="proses/cekKunjungan.php" method="POST" class="space-y-3">
          <input type="hidden" name="jalur" value="manual">
          <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">Tanggal Kunjungan</label>
            <input type="date" name="tanggal" class="clay-input" required>
          </div>
          <button type="submit" class="clay-btn w-full py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm">Cari Kunjungan</button>
        </form>
      </div>

    </div>
  </main>

  <form id="form-logout" action="proses/logout.php" method="POST" style="display:none;"></form>

</body>
</html>
