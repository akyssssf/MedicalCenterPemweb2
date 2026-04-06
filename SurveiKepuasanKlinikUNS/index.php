<?php
session_start();
include 'server/koneksi.php'; 

// HAPUS Pengecekan Login di sini karena ini jadi Landing Page Publik
$is_logged_in = isset($_SESSION['nik']);

$query = "SELECT * FROM surveys";
$result = mysqli_query($koneksi, $query);
$real_surveys = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rata = ($row['q1'] + $row['q2'] + $row['q3'] + $row['q4']) / 4;
        $real_surveys[] = [
            'kategori' => $row['kategori'],
            'penilaian' => [
                'keramahan' => (int)$row['q1'],
                'kecepatan' => (int)$row['q2'],
                'kebersihan' => (int)$row['q3'],
                'kualitasMedis' => (int)$row['q4']
            ],
            'rataRata' => number_format($rata, 2, '.', '')
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>UNS Medical Center — Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    *{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}
    body{background:linear-gradient(135deg,#dbeafe 0%,#e0f2fe 50%,#f0fdf4 100%);min-height:100vh;overflow-x:hidden;}
    .blob{position:fixed;border-radius:50%;filter:blur(60px);pointer-events:none;z-index:0;}
    .clay-card{background:#fff;border-radius:28px; box-shadow:8px 8px 24px rgba(99,149,210,.22),-2px -2px 8px rgba(255,255,255,.9), inset 3px 3px 8px rgba(255,255,255,.85),inset -3px -3px 8px rgba(180,210,245,.35); border:1.5px solid rgba(255,255,255,.75);transition:transform .2s,box-shadow .2s;}
    .clay-card:hover{transform:translateY(-3px); box-shadow:12px 12px 32px rgba(99,149,210,.28),-2px -2px 10px rgba(255,255,255,.95), inset 3px 3px 8px rgba(255,255,255,.85),inset -3px -3px 8px rgba(180,210,245,.35);}
    .clay-btn{border-radius:16px;font-weight:700;border:none;cursor:pointer; box-shadow:5px 5px 14px rgba(59,130,246,.35),-1px -1px 5px rgba(255,255,255,.7), inset 2px 2px 5px rgba(255,255,255,.4),inset -2px -2px 5px rgba(37,99,235,.25); transition:all .18s ease;}
    .clay-btn:hover{transform:translateY(-2px) scale(1.01);}
    .clay-nav{background:rgba(255,255,255,.82);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px); border-bottom:1.5px solid rgba(255,255,255,.6);box-shadow:0 4px 20px rgba(99,149,210,.12);}
    .nav-link{color:#64748b;font-size:.875rem;font-weight:600;padding:.5rem 1rem;border-radius:10px; transition:background .15s,color .15s;text-decoration:none;}
    .nav-link:hover,.nav-link.active{background:rgba(59,130,246,.1);color:#2563eb;}
    .nav-danger{color:#ef4444;font-size:.875rem;font-weight:600;padding:.5rem 1rem;border-radius:10px; cursor:pointer;transition:background .15s;background:none;border:none;}
    .hero-bg{background:linear-gradient(135deg,#1e3a6e 0%,#1e40af 55%,#0284c7 100%); border-radius:28px; box-shadow:8px 8px 28px rgba(30,58,138,.3),inset 3px 3px 10px rgba(255,255,255,.1); overflow:hidden; position:relative;}
    .stat-pill{background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.3); border-radius:50px;padding:.4rem .9rem;backdrop-filter:blur(8px);}
    .info-icon{border-radius:16px;box-shadow:3px 3px 10px rgba(99,149,210,.2),inset 2px 2px 5px rgba(255,255,255,.8);}
    .social-link{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:14px; text-decoration:none;font-size:.85rem;font-weight:600;transition:all .18s ease;border:1.5px solid transparent;}
    .social-maps{background:#eff6ff;border-color:#bfdbfe;color:#1e40af;}
    #poli-slides{display:flex;height:100%;}
    .poli-slide{min-width:100%;padding:16px;display:flex;flex-direction:column;justify-content:center;border-radius:16px;transition:opacity .3s;}
    .poli-dot{width:7px;height:7px;border-radius:50%;background:#dbeafe;border:1.5px solid #bfdbfe;transition:all .25s ease;cursor:pointer;}
    .poli-dot.active{background:#3b82f6;border-color:#2563eb;width:20px;border-radius:4px;}
  </style>
</head>
<body class="overflow-x-hidden">
  <div class="blob w-80 h-80 bg-blue-200 opacity-35" style="top:-5rem;left:-5rem;"></div>
  <div class="blob w-96 h-96 bg-cyan-200 opacity-25" style="bottom:-5rem;right:-5rem;"></div>

  <nav class="clay-nav sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-5 py-3 flex items-center justify-between gap-2">
      <div class="flex items-center gap-2.5 min-w-0">
        <img src="https://uns.ac.id/id/wp-content/uploads/2023/06/logo-uns-biru.png" alt="Logo UNS" class="h-8 sm:h-9 object-contain flex-shrink-0" style="filter:drop-shadow(0 2px 4px rgba(30,58,138,.3))"/>
        <div class="hidden sm:block min-w-0">
          <p class="font-extrabold text-gray-800 text-sm leading-tight truncate">UNS Medical Center</p>
          <p class="text-blue-400 text-xs font-medium">Survei Kepuasan Pasien</p>
        </div>
      </div>
      <div class="flex items-center gap-1 flex-shrink-0">
        <?php if($is_logged_in): ?>
            <a href="dashboard.php" class="nav-link active">Ke Dashboard</a>
            <span class="text-xs font-bold text-gray-600 ml-3"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <?php else: ?>
            <a href="login.php" class="clay-btn bg-blue-600 text-white px-5 py-2 text-xs">Login Pasien</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <main class="max-w-5xl mx-auto px-4 sm:px-5 py-6 sm:py-8 space-y-5 sm:space-y-6 pb-6 relative z-10">
    <div class="hero-bg p-6 sm:p-10">
      <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
        <div class="flex-1">
          <div class="flex items-center gap-2 mb-2 flex-wrap">
            <span class="stat-pill text-white text-xs font-semibold">👋 Selamat Datang di</span>
          </div>
          <h1 class="text-white font-extrabold text-xl sm:text-3xl leading-tight mt-1">UNS Medical Center</h1>
          <p class="text-blue-200 text-sm mt-2 max-w-md leading-6">Bantu kami meningkatkan pelayanan dengan mengisi survei kepuasan. Pendapat Anda sangat berarti bagi evaluasi klinik kami!</p>
          <div class="flex flex-wrap gap-2 mt-3 sm:mt-4">
            <span class="stat-pill text-white text-xs font-semibold">✅ Anonim & Aman</span>
            <span class="stat-pill text-white text-xs font-semibold">⏱ < 2 Menit</span>
          </div>
          
          <?php if($is_logged_in): ?>
            <button onclick="window.location.href='dashboard.php'" class="clay-btn bg-white text-blue-800 px-6 sm:px-7 py-3 sm:py-3.5 text-sm mt-5 inline-block font-bold">Buka Dashboard Survei →</button>
          <?php else: ?>
            <button onclick="window.location.href='login.php'" class="clay-btn bg-white text-blue-800 px-6 sm:px-7 py-3 sm:py-3.5 text-sm mt-5 inline-block font-bold">Login untuk Mengisi Survei →</button>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
      <div class="clay-card p-5 sm:p-6">
        <h2 class="font-extrabold text-gray-800 text-base mb-2">Tentang UNS Medical Center</h2>
        <p class="text-gray-500 text-sm leading-6">Unit pelayanan kesehatan terpadu milik Universitas Sebelas Maret Surakarta, melayani sivitas akademika dan masyarakat umum secara profesional, terjangkau, dan ramah.</p>
        <div class="space-y-2 text-xs sm:text-sm mt-4">
          <div class="flex justify-between items-center bg-blue-50 rounded-xl px-3 py-2">
            <span class="text-gray-600 font-medium">Senin – Jumat</span><span class="font-bold text-blue-700">07.00 – 21.00 WIB</span>
          </div>
        </div>
      </div>

      <div class="clay-card p-5 sm:p-6 flex flex-col">
        <h3 class="font-bold text-gray-700 text-sm sm:text-base mb-4 flex items-center gap-2">
          <span class="info-icon bg-blue-50 w-8 h-8 flex items-center justify-center text-base">🏥</span> Layanan Poli Kami
        </h3>
        <div class="flex-1 relative overflow-hidden rounded-2xl" style="min-height:130px;">
          <div id="poli-slides" class="flex transition-transform duration-400 ease-in-out h-full"></div>
        </div>
      </div>
    </div>

  </main>

  <script>
    const POLI_DATA = [
      { icon:'🏥', name:'Poli Umum',   color:'#eff6ff', accent:'#2563eb', border:'#bfdbfe', desc:'Pemeriksaan dan pengobatan keluhan kesehatan umum.', jam:'Setiap hari kerja' },
      { icon:'🦷', name:'Poli Gigi',   color:'#f0fdf4', accent:'#15803d', border:'#bbf7d0', desc:'Perawatan gigi dan mulut oleh dokter gigi profesional.', jam:'Senin – Sabtu' },
      { icon:'🤱', name:'KIA',          color:'#fdf2f8', accent:'#9d174d', border:'#fbcfe8', desc:'Layanan Kesehatan Ibu dan Anak serta konsultasi KB.', jam:'Senin – Jumat' }
    ];
    let poliIdx = 0;
    let poliTimer;
    function buildPoliSlider(){
      const wrap = document.getElementById('poli-slides');
      wrap.innerHTML = POLI_DATA.map((p,i)=>`
        <div class="poli-slide" style="background:${p.color};border:1.5px solid ${p.border};">
          <div class="flex items-center gap-2 mb-2">
            <span style="font-size:1.6rem;line-height:1;">${p.icon}</span>
            <span style="font-weight:800;font-size:.95rem;color:${p.accent};">${p.name}</span>
          </div>
          <p style="font-size:.78rem;color:#475569;line-height:1.6;margin:0 0 8px;">${p.desc}</p>
        </div>`).join('');
      goSlide(0,false);
      startPoliAuto();
    }
    function goSlide(idx, animate=true){
      poliIdx = (idx + POLI_DATA.length) % POLI_DATA.length;
      const wrap = document.getElementById('poli-slides');
      if(animate) wrap.style.transition='transform .38s ease';
      wrap.style.transform = `translateX(-${poliIdx * 100}%)`;
    }
    function startPoliAuto(){ poliTimer = setInterval(()=>goSlide(poliIdx+1), 3500); }
    buildPoliSlider();
  </script>
</body>
</html>