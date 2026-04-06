<?php
session_start();

// 1. Harus sudah login
if (!isset($_SESSION['nik'])) {
    header("Location: login.php");
    exit();
}

// 2. Harus sudah lolos verifikasi Token/Tanggal di Dashboard
if (!isset($_SESSION['id_kunjungan_aktif'])) {
    header("Location: dashboard.php");
    exit();
}

// Bikin Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Isi Survei — UNS Medical Center</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    /* ... (CSS tidak berubah) ... */
    *{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}
    body{background:linear-gradient(135deg,#dbeafe 0%,#e0f2fe 50%,#f0fdf4 100%);min-height:100vh;overflow-x:hidden;}
    .blob{position:fixed;border-radius:50%;filter:blur(60px);pointer-events:none;z-index:0;}
    .clay-card{background:#fff;border-radius:28px; box-shadow:8px 8px 24px rgba(99,149,210,.22),-2px -2px 8px rgba(255,255,255,.9), inset 3px 3px 8px rgba(255,255,255,.85),inset -3px -3px 8px rgba(180,210,245,.35); border:1.5px solid rgba(255,255,255,.75);}
    .clay-btn{border-radius:16px;font-weight:700;border:none;cursor:pointer; box-shadow:5px 5px 14px rgba(59,130,246,.35),-1px -1px 5px rgba(255,255,255,.7), inset 2px 2px 5px rgba(255,255,255,.4),inset -2px -2px 5px rgba(37,99,235,.25); transition:all .18s ease;}
    .clay-btn:hover{transform:translateY(-2px) scale(1.01);}
    .survey-nav{background:linear-gradient(135deg,#1e3a6e,#1e40af);box-shadow:0 4px 20px rgba(30,58,138,.3);}
    .nav-exit{color:rgba(255,255,255,.8);font-size:.875rem;font-weight:600;padding:.5rem .9rem; border-radius:10px;cursor:pointer;transition:background .15s,color .15s; background:none;border:none;display:flex;align-items:center;gap:6px;}
    .progress-track{background:#e8f0fe;border-radius:999px;overflow:hidden; box-shadow:inset 2px 2px 5px rgba(180,210,245,.5),inset -1px -1px 3px rgba(255,255,255,.8);}
    .progress-fill{height:100%;border-radius:999px; background:linear-gradient(90deg,#3b82f6,#06b6d4); transition:width .5s ease; position:relative;overflow:hidden;}
    .progress-fill.done{background:linear-gradient(90deg,#22c55e,#16a34a);}
    .step-dot{width:10px;height:10px;border-radius:50%;background:#dbeafe;border:2px solid #bfdbfe;transition:all .3s ease;}
    .step-dot.filled{background:#3b82f6;border-color:#2563eb;transform:scale(1.15);}
    .clay-select, .clay-textarea{background:#f1f8ff;border:1.5px solid rgba(147,197,253,.6);border-radius:14px; box-shadow:inset 2px 2px 6px rgba(180,210,245,.4),inset -2px -2px 5px rgba(255,255,255,.85); padding:.75rem 1rem;width:100%;outline:none;font-size:.95rem;color:#374151;}
    .rating-group{display:flex;gap:6px;flex-wrap:wrap;}
    .rating-label{display:flex;flex-direction:column;align-items:center;gap:2px;cursor:pointer;}
    .rating-label input[type="radio"]{display:none;}
    .rating-box{width:46px;height:46px;border-radius:14px;background:#f1f8ff; border:1.5px solid rgba(147,197,253,.5); display:flex;align-items:center;justify-content:center; font-size:1rem;font-weight:800;color:#94a3b8;transition:all .18s ease;}
    .rating-label input[type="radio"]:checked ~ .rating-box{ background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;border-color:#2563eb; transform:translateY(-2px) scale(1.07);}
    .rating-num{font-size:.63rem;color:#94a3b8;font-weight:600;}
    .q-block{border-bottom:1.5px dashed rgba(186,230,253,.8);padding-bottom:1rem;margin-bottom:1rem;}
    .q-block.answered .q-num-badge{background:linear-gradient(135deg,#22c55e,#16a34a);}
    .section-num{background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;border-radius:10px; width:26px;height:26px;display:flex;align-items:center;justify-content:center; font-size:.72rem;font-weight:800;flex-shrink:0;}
    .q-num-badge{width:20px;height:20px;border-radius:6px; background:linear-gradient(135deg,#94a3b8,#64748b);color:white; display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800; flex-shrink:0;}
    label.field-label{font-size:.83rem;font-weight:600;color:#374151;margin-bottom:4px;display:block;}
  </style>
</head>
<body class="overflow-x-hidden">
  <div class="blob w-80 h-80 bg-blue-200 opacity-35" style="top:-5rem;left:-5rem;"></div>
  
  <nav class="survey-nav sticky top-0 z-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-5 py-3 flex items-center justify-between">
      <div class="flex items-center gap-2.5 min-w-0">
        <img src="https://senirupa.fkip.uns.ac.id/wp-content/uploads/2021/07/logo_putih.png" alt="Logo UNS" class="h-7 sm:h-8 object-contain opacity-90"/>
        <div class="min-w-0">
          <p class="font-extrabold text-white text-xs sm:text-sm">UNS Medical Center</p>
          <p class="text-blue-200 text-xs hidden sm:block">Sesi Pengisian Survei</p>
        </div>
      </div>
      <button onclick="window.location.href='index.php'" class="nav-exit">Keluar Survei</button>
    </div>
  </nav>

  <main class="max-w-3xl mx-auto px-4 sm:px-5 py-5 sm:py-7 space-y-4 sm:space-y-5 pb-16 relative z-10">
    
    <div class="clay-card p-4 sm:p-5">
      <div class="flex items-center justify-between mb-2">
        <span class="font-bold text-gray-700 text-sm">Progress Pengisian</span>
        <span id="prog-pct" class="font-extrabold text-blue-600 text-lg">0%</span>
      </div>
      <div class="progress-track mb-3" style="height:12px;"><div id="progress-fill" class="progress-fill" style="width:0%;"></div></div>
    </div>

    <form action="proses/prosesSurvei.php" method="POST" class="space-y-4 sm:space-y-5">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      
      <div class="clay-card p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-3"><span class="section-num">1</span><h2 class="font-bold text-gray-700 text-sm">Identitas Responden</h2></div>
        <label class="field-label">Kategori Pengunjung *</label>
        <select name="kategori" id="kategori" class="clay-select" required onchange="updateProgress()">
          <option value="" disabled selected>-- Pilih Kategori --</option>
          <option value="Mahasiswa">Mahasiswa</option>
          <option value="Dosen">Dosen / Tenaga Pengajar</option>
          <option value="Karyawan">Tenaga Kependidikan</option>
          <option value="Umum">Masyarakat Umum</option>
        </select>
      </div>

      <div class="clay-card p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-1"><span class="section-num">2</span><h2 class="font-bold text-gray-700 text-sm">Penilaian Layanan</h2></div>
        
        <div class="q-block" id="qblock-q1">
          <p class="text-sm font-semibold text-gray-700 mb-2">Q1. Keramahan & Kesopanan Petugas</p>
          <div class="rating-group">
            <label class="rating-label"><input type="radio" name="q1" value="1" required onchange="updateProgress()"><div class="rating-box">1</div></label>
            <label class="rating-label"><input type="radio" name="q1" value="2" onchange="updateProgress()"><div class="rating-box">2</div></label>
            <label class="rating-label"><input type="radio" name="q1" value="3" onchange="updateProgress()"><div class="rating-box">3</div></label>
            <label class="rating-label"><input type="radio" name="q1" value="4" onchange="updateProgress()"><div class="rating-box">4</div></label>
            <label class="rating-label"><input type="radio" name="q1" value="5" onchange="updateProgress()"><div class="rating-box">5</div></label>
          </div>
        </div>

        <div class="q-block" id="qblock-q2">
          <p class="text-sm font-semibold text-gray-700 mb-2">Q2. Kecepatan & Ketepatan Pelayanan</p>
          <div class="rating-group">
            <label class="rating-label"><input type="radio" name="q2" value="1" required onchange="updateProgress()"><div class="rating-box">1</div></label>
            <label class="rating-label"><input type="radio" name="q2" value="2" onchange="updateProgress()"><div class="rating-box">2</div></label>
            <label class="rating-label"><input type="radio" name="q2" value="3" onchange="updateProgress()"><div class="rating-box">3</div></label>
            <label class="rating-label"><input type="radio" name="q2" value="4" onchange="updateProgress()"><div class="rating-box">4</div></label>
            <label class="rating-label"><input type="radio" name="q2" value="5" onchange="updateProgress()"><div class="rating-box">5</div></label>
          </div>
        </div>

        <div class="q-block" id="qblock-q3">
          <p class="text-sm font-semibold text-gray-700 mb-2">Q3. Kebersihan & Kenyamanan Fasilitas</p>
          <div class="rating-group">
            <label class="rating-label"><input type="radio" name="q3" value="1" required onchange="updateProgress()"><div class="rating-box">1</div></label>
            <label class="rating-label"><input type="radio" name="q3" value="2" onchange="updateProgress()"><div class="rating-box">2</div></label>
            <label class="rating-label"><input type="radio" name="q3" value="3" onchange="updateProgress()"><div class="rating-box">3</div></label>
            <label class="rating-label"><input type="radio" name="q3" value="4" onchange="updateProgress()"><div class="rating-box">4</div></label>
            <label class="rating-label"><input type="radio" name="q3" value="5" onchange="updateProgress()"><div class="rating-box">5</div></label>
          </div>
        </div>

        <div class="q-block" id="qblock-q4">
          <p class="text-sm font-semibold text-gray-700 mb-2">Q4. Kualitas Penanganan Medis</p>
          <div class="rating-group">
            <label class="rating-label"><input type="radio" name="q4" value="1" required onchange="updateProgress()"><div class="rating-box">1</div></label>
            <label class="rating-label"><input type="radio" name="q4" value="2" onchange="updateProgress()"><div class="rating-box">2</div></label>
            <label class="rating-label"><input type="radio" name="q4" value="3" onchange="updateProgress()"><div class="rating-box">3</div></label>
            <label class="rating-label"><input type="radio" name="q4" value="4" onchange="updateProgress()"><div class="rating-box">4</div></label>
            <label class="rating-label"><input type="radio" name="q4" value="5" onchange="updateProgress()"><div class="rating-box">5</div></label>
          </div>
        </div>
      </div>

      <div class="clay-card p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-3"><span class="section-num">3</span><h2 class="font-bold text-gray-700 text-sm">Kritik & Saran</h2></div>
        <textarea name="saran" id="saran" class="clay-textarea" placeholder="Tulis masukan Anda..."></textarea>
      </div>

      <button type="submit" id="submit-btn" class="clay-btn w-full py-3.5 sm:py-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-sm" disabled style="opacity:.45;">
        Kirim Survei 🚀
      </button>
    </form>
  </main>

  <script>
    function updateProgress(){
      const checks = [
        !!document.getElementById('kategori').value,
        !!document.querySelector('input[name="q1"]:checked'),
        !!document.querySelector('input[name="q2"]:checked'),
        !!document.querySelector('input[name="q3"]:checked'),
        !!document.querySelector('input[name="q4"]:checked')
      ];
      const filled = checks.filter(Boolean).length;
      const pct    = Math.round((filled/5)*100);
      const done   = filled === 5;

      const fill = document.getElementById('progress-fill');
      fill.style.width = pct + '%';
      fill.classList.toggle('done', done);
      document.getElementById('prog-pct').textContent = pct + '%';

      ['q1','q2','q3','q4'].forEach((q,i)=>{
        document.getElementById('qblock-'+q).classList.toggle('answered', checks[i+1]);
      });

      const btn = document.getElementById('submit-btn');
      if(done){
        btn.disabled = false; btn.style.opacity='1'; btn.style.cursor='pointer';
      } else {
        btn.disabled = true; btn.style.opacity='.45'; btn.style.cursor='not-allowed';
      }
    }
  </script>
</body>
</html>