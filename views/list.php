<?php
include_once "controllers/BukuController.php";

$controller = new BukuController();
$data = $controller->model->getAll();
?>
<!DOCTYPE html>
<html lang="id" data-theme="pink">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Perpustakaan Digital</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
  <style>

    /* ═══════════════════════════════════════════
       THEME TOKENS — switched via data-theme
    ═══════════════════════════════════════════ */

    /* 🌸 Pink (default) */
    [data-theme="pink"] {
      --primary:        #e91e8c;
      --primary-light:  #f06bb5;
      --primary-soft:   #fce4f0;
      --primary-mid:    #f8c0de;
      --primary-glow:   rgba(233,30,140,0.12);
      --header-bg:      linear-gradient(135deg,#e91e8c 0%,#f06bb5 60%,#ff8fab 100%);
      --btn-text:       #fff;
      --accent2:        #ff5fa0;
    }

    /* 🩵 Baby Blue */
    [data-theme="blue"] {
      --primary:        #2196f3;
      --primary-light:  #64b5f6;
      --primary-soft:   #e3f2fd;
      --primary-mid:    #bbdefb;
      --primary-glow:   rgba(33,150,243,0.12);
      --header-bg:      linear-gradient(135deg,#1565c0 0%,#2196f3 60%,#64b5f6 100%);
      --btn-text:       #fff;
      --accent2:        #42a5f5;
    }

    /* 💜 Lavender */
    [data-theme="purple"] {
      --primary:        #9c27b0;
      --primary-light:  #ce93d8;
      --primary-soft:   #f3e5f5;
      --primary-mid:    #e1bee7;
      --primary-glow:   rgba(156,39,176,0.12);
      --header-bg:      linear-gradient(135deg,#6a1b9a 0%,#9c27b0 60%,#ce93d8 100%);
      --btn-text:       #fff;
      --accent2:        #ab47bc;
    }

    /* 🌿 Mint Green */
    [data-theme="green"] {
      --primary:        #00897b;
      --primary-light:  #4db6ac;
      --primary-soft:   #e0f2f1;
      --primary-mid:    #b2dfdb;
      --primary-glow:   rgba(0,137,123,0.12);
      --header-bg:      linear-gradient(135deg,#00574b 0%,#00897b 60%,#4db6ac 100%);
      --btn-text:       #fff;
      --accent2:        #26a69a;
    }

    /* 🍊 Peach / Coral */
    [data-theme="coral"] {
      --primary:        #f4511e;
      --primary-light:  #ff7043;
      --primary-soft:   #fbe9e7;
      --primary-mid:    #ffccbc;
      --primary-glow:   rgba(244,81,30,0.12);
      --header-bg:      linear-gradient(135deg,#bf360c 0%,#f4511e 60%,#ff7043 100%);
      --btn-text:       #fff;
      --accent2:        #ff6e40;
    }

    /* ═══════════════════════════════════════════
       BASE / GLOBAL
    ═══════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Nunito', sans-serif;
      background: #ffffff;
      min-height: 100vh;
      color: #2d2d2d;
      overflow-x: hidden;
    }

    /* Subtle dot pattern on white bg */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: radial-gradient(var(--primary-mid) 1px, transparent 1px);
      background-size: 28px 28px;
      opacity: .35;
      pointer-events: none;
      z-index: 0;
    }

    .page {
      position: relative;
      z-index: 1;
      max-width: 1080px;
      margin: 0 auto;
      padding: 0 24px 80px;
    }

    /* ═══════════════════════════════════════════
       THEME SWITCHER PILL (top-right)
    ═══════════════════════════════════════════ */
    .theme-bar {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 999;
      display: flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      border: 1.5px solid var(--primary-mid);
      border-radius: 50px;
      padding: 6px 14px 6px 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.10);
      animation: slideIn .5s ease both;
    }

    .theme-bar-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: #999;
      margin-right: 2px;
    }

    .theme-dot {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      border: 2.5px solid transparent;
      cursor: pointer;
      transition: transform .2s ease, border-color .2s ease;
      position: relative;
    }

    .theme-dot:hover        { transform: scale(1.2); }
    .theme-dot.active       { border-color: #2d2d2d; transform: scale(1.15); }
    .theme-dot[data-t="pink"]   { background: #e91e8c; }
    .theme-dot[data-t="blue"]   { background: #2196f3; }
    .theme-dot[data-t="purple"] { background: #9c27b0; }
    .theme-dot[data-t="green"]  { background: #00897b; }
    .theme-dot[data-t="coral"]  { background: #f4511e; }

    /* ═══════════════════════════════════════════
       HERO HEADER
    ═══════════════════════════════════════════ */
    .hero {
      background: var(--header-bg);
      border-radius: 0 0 36px 36px;
      padding: 56px 40px 48px;
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 16px 48px var(--primary-glow);
      animation: fadeDown .6s ease both;
    }

    /* Decorative circles inside hero */
    .hero::before, .hero::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,0.10);
      pointer-events: none;
    }
    .hero::before { width: 260px; height: 260px; top: -80px; left: -60px; }
    .hero::after  { width: 180px; height: 180px; bottom: -60px; right: -40px; }

    .hero-tag {
      display: inline-block;
      background: rgba(255,255,255,0.22);
      backdrop-filter: blur(6px);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      padding: 5px 16px;
      border-radius: 20px;
      margin-bottom: 16px;
      border: 1px solid rgba(255,255,255,0.35);
    }

    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.2rem, 5vw, 3.4rem);
      color: #fff;
      font-weight: 700;
      line-height: 1.1;
      text-shadow: 0 2px 12px rgba(0,0,0,0.15);
      position: relative;
      z-index: 1;
    }

    .hero p {
      margin-top: 10px;
      color: rgba(255,255,255,0.82);
      font-size: 15px;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }

    /* Wavy divider at bottom of hero */
    .hero-wave {
      position: absolute;
      bottom: -1px; left: 0; right: 0;
    }

    /* ═══════════════════════════════════════════
       STATS ROW
    ═══════════════════════════════════════════ */
    .stats-row {
      display: flex;
      gap: 16px;
      margin-bottom: 28px;
      flex-wrap: wrap;
      animation: fadeUp .6s ease .1s both;
    }

    .stat-chip {
      display: flex;
      align-items: center;
      gap: 10px;
      background: var(--primary-soft);
      border: 1.5px solid var(--primary-mid);
      border-radius: 14px;
      padding: 12px 20px;
      font-size: 13.5px;
      font-weight: 600;
      color: var(--primary);
    }

    .stat-chip .stat-icon { font-size: 20px; }
    .stat-chip .stat-val  { font-size: 22px; font-weight: 900; }

    /* ═══════════════════════════════════════════
       CARD + TOOLBAR
    ═══════════════════════════════════════════ */
    .card {
      background: #fff;
      border: 1.5px solid var(--primary-mid);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 40px var(--primary-glow), 0 2px 8px rgba(0,0,0,0.06);
      animation: fadeUp .6s ease .2s both;
    }

    .toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 24px;
      background: var(--primary-soft);
      border-bottom: 1.5px solid var(--primary-mid);
      flex-wrap: wrap;
      gap: 12px;
    }

    .toolbar-title {
      font-size: 14px;
      font-weight: 700;
      color: var(--primary);
    }

    .btn-add {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--primary);
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-size: 13.5px;
      font-weight: 700;
      padding: 10px 22px;
      border-radius: 12px;
      text-decoration: none;
      transition: all .25s ease;
      box-shadow: 0 4px 16px var(--primary-glow);
    }

    .btn-add:hover {
      filter: brightness(1.1);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px var(--primary-glow);
    }

    .btn-add svg { width: 16px; height: 16px; }

    /* ═══════════════════════════════════════════
       TABLE
    ═══════════════════════════════════════════ */
    table { width: 100%; border-collapse: collapse; }

    thead th {
      padding: 13px 20px;
      text-align: left;
      font-size: 11.5px;
      font-weight: 800;
      letter-spacing: 1.8px;
      text-transform: uppercase;
      color: var(--primary);
      background: var(--primary-soft);
      border-bottom: 2px solid var(--primary-mid);
    }

    thead th:first-child { text-align: center; width: 58px; }
    thead th:last-child  { text-align: center; width: 110px; }

    tbody tr {
      border-bottom: 1px solid #f3f3f3;
      transition: background .18s ease;
      animation: rowIn .35s ease both;
    }

    tbody tr:hover { background: var(--primary-soft); }

    tbody td {
      padding: 15px 20px;
      font-size: 14.5px;
      vertical-align: middle;
      color: #333;
    }

    .td-no {
      text-align: center;
      font-weight: 800;
      color: var(--primary-light);
      font-size: 13px;
    }

    .td-nama { font-weight: 700; color: #222; }

    /* ═══════════════════════════════════════════
       GENRE BADGE
    ═══════════════════════════════════════════ */
    .genre-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 12.5px;
      font-weight: 700;
      border: 1.5px solid;
    }

    .genre-pendidikan { background:#e3f2fd; color:#1565c0; border-color:#90caf9; }
    .genre-fiksi      { background:#f3e5f5; color:#7b1fa2; border-color:#ce93d8; }
    .genre-nonfiksi   { background:#e8f5e9; color:#2e7d32; border-color:#a5d6a7; }
    .genre-penelitian { background:#fff8e1; color:#f57f17; border-color:#ffe082; }
    .genre-sains      { background:#e0f7fa; color:#00695c; border-color:#80cbc4; }
    .genre-sejarah    { background:#fce4ec; color:#c62828; border-color:#ef9a9a; }
    .genre-default    { background:#f5f5f5; color:#616161; border-color:#e0e0e0; }

    /* ═══════════════════════════════════════════
       ACTION BUTTONS
    ═══════════════════════════════════════════ */
    .td-aksi { text-align: center; }

    .action-group {
      display: inline-flex;
      gap: 8px;
      align-items: center;
    }

    .btn-action {
      position: relative;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 10px;
      text-decoration: none;
      transition: all .22s ease;
      border: 1.5px solid;
    }

    .btn-action svg { width: 15px; height: 15px; }

    /* Tooltip */
    .btn-action::after {
      content: attr(data-tip);
      position: absolute;
      bottom: calc(100% + 7px);
      left: 50%;
      transform: translateX(-50%);
      background: #222;
      color: #fff;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 9px;
      border-radius: 6px;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity .18s ease;
    }
    .btn-action:hover::after { opacity: 1; }

    .btn-edit  { background:#e3f2fd; border-color:#90caf9; color:#1565c0; }
    .btn-edit:hover  { background:#bbdefb; transform:translateY(-2px); box-shadow:0 4px 12px rgba(33,150,243,.2); }

    .btn-hapus { background:#fce4ec; border-color:#f48fb1; color:#c62828; }
    .btn-hapus:hover { background:#f8bbd0; transform:translateY(-2px); box-shadow:0 4px 12px rgba(244,67,54,.2); }

    /* ═══════════════════════════════════════════
       EMPTY STATE
    ═══════════════════════════════════════════ */
    .empty { text-align:center; padding:60px 20px; color:#aaa; }
    .empty-icon { font-size:52px; margin-bottom:12px; }
    .empty p { font-size:15px; font-weight:600; }

    /* ═══════════════════════════════════════════
       BOTTOM ADD BUTTON
    ═══════════════════════════════════════════ */
    .footer-strip {
      display: flex;
      justify-content: center;
      margin-top: 24px;
      animation: fadeUp .6s ease .3s both;
    }

    .btn-add-outline {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      color: var(--primary);
      font-family: 'Nunito', sans-serif;
      font-size: 13.5px;
      font-weight: 700;
      padding: 11px 26px;
      border-radius: 14px;
      text-decoration: none;
      border: 2px solid var(--primary);
      transition: all .25s ease;
    }
    .btn-add-outline:hover {
      background: var(--primary-soft);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px var(--primary-glow);
    }
    .btn-add-outline svg { width: 16px; height: 16px; }

    /* ═══════════════════════════════════════════
       ANIMATIONS
    ═══════════════════════════════════════════ */
    @keyframes fadeDown {
      from { opacity:0; transform:translateY(-20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    @keyframes rowIn {
      from { opacity:0; transform:translateX(-8px); }
      to   { opacity:1; transform:translateX(0); }
    }
    @keyframes slideIn {
      from { opacity:0; transform:translateX(20px); }
      to   { opacity:1; transform:translateX(0); }
    }

    tbody tr:nth-child(1)   { animation-delay:.15s; }
    tbody tr:nth-child(2)   { animation-delay:.20s; }
    tbody tr:nth-child(3)   { animation-delay:.25s; }
    tbody tr:nth-child(4)   { animation-delay:.30s; }
    tbody tr:nth-child(5)   { animation-delay:.35s; }
    tbody tr:nth-child(n+6) { animation-delay:.40s; }

    /* Responsive */
    @media (max-width: 640px) {
      .hero { padding: 40px 20px 36px; border-radius: 0 0 24px 24px; }
      .toolbar { flex-direction: column; align-items: flex-start; }
      thead th:nth-child(3), tbody td:nth-child(3) { display:none; }
      .theme-bar { top:12px; right:12px; padding:5px 10px 5px 8px; gap:6px; }
      .theme-dot { width:20px; height:20px; }
    }
  </style>
</head>
<body>

<!-- ── Theme Switcher ── -->
<div class="theme-bar">
  <span class="theme-bar-label">Tema</span>
  <span class="theme-dot active" data-t="pink"   title="Pink Blossom"></span>
  <span class="theme-dot"        data-t="blue"   title="Sky Blue"></span>
  <span class="theme-dot"        data-t="purple" title="Lavender"></span>
  <span class="theme-dot"        data-t="green"  title="Mint Green"></span>
  <span class="theme-dot"        data-t="coral"  title="Coral Sunset"></span>
</div>

<div class="page">

  <!-- ── Hero Header ── -->
  <div class="hero">
    <div class="hero-tag">📚 Sistem Informasi Perpustakaan</div>
    <h1>Data Buku</h1>
    <p>Kelola seluruh koleksi buku perpustakaan dengan mudah</p>
    <!-- Wave SVG -->
    <svg class="hero-wave" viewBox="0 0 1440 40" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0,20 C360,40 1080,0 1440,20 L1440,40 L0,40 Z" fill="#ffffff"/>
    </svg>
  </div>

  <!-- ── Stats Row ── -->
  <div class="stats-row">
    <div class="stat-chip">
      <span class="stat-icon">📖</span>
      <div>
        <div class="stat-val" id="totalBuku">—</div>
        <div>Total Buku</div>
      </div>
    </div>
    <div class="stat-chip">
      <span class="stat-icon">🗂️</span>
      <div>
        <div class="stat-val" id="totalGenre">—</div>
        <div>Jenis Buku</div>
      </div>
    </div>
  </div>

  <!-- ── Card ── -->
  <div class="card">

    <!-- Toolbar -->
    <div class="toolbar">
      <span class="toolbar-title">📋 Daftar Koleksi Buku</span>
      <a href="views/tambah.php" class="btn-add">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        Tambah Buku
      </a>
    </div>

    <!-- Table -->
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Buku</th>
          <th>Jenis</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>

        <?php

        function getGenreInfo(string $jenis): array {
          $j = strtolower(trim($jenis));
          $map = [
            'pendidikan'       => ['genre-pendidikan','🎓'],
            'fiksi'            => ['genre-fiksi',      '🧚'],
            'nonfiksi'         => ['genre-nonfiksi',   '📰'],
            'non fiksi'        => ['genre-nonfiksi',   '📰'],
            'non-fiksi'        => ['genre-nonfiksi',   '📰'],
            'penelitian'       => ['genre-penelitian', '🔬'],
            'sains'            => ['genre-sains',      '⚗️'],
            'ilmu pengetahuan' => ['genre-sains',      '🔭'],
            'sejarah'          => ['genre-sejarah',    '🏛️'],
            'agama'            => ['genre-pendidikan', '📖'],
            'teknologi'        => ['genre-sains',      '💻'],
            'ekonomi'          => ['genre-penelitian', '📊'],
            'kesehatan'        => ['genre-nonfiksi',   '🏥'],
          ];
          foreach ($map as $key => $val) {
            if (str_contains($j, $key)) return $val;
          }
          return ['genre-default','📚'];
        }

        $no = 1; $count = 0; $genres = [];

        if ($data && $data->num_rows > 0):
          while ($row = $data->fetch_assoc()):
            [$gClass, $gIcon] = getGenreInfo($row['jenis_buku']);
            $genres[] = $row['jenis_buku'];
            $count++;
        ?>
        <tr>
          <td class="td-no"><?= $no++; ?></td>
          <td class="td-nama"><?= htmlspecialchars($row['nama_buku']); ?></td>
          <td>
            <span class="genre-badge <?= $gClass; ?>">
              <?= $gIcon; ?> <?= htmlspecialchars($row['jenis_buku']); ?>
            </span>
          </td>
          <td class="td-aksi">
            <div class="action-group">
              <!-- Edit -->
              <a href="views/edit.php?id=<?= $row['id_buku']; ?>"
                 class="btn-action btn-edit"
                 data-tip="Edit Buku">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </a>
              <!-- Hapus -->
              <a href="index.php?hapus=<?= $row['id_buku']; ?>"
                 class="btn-action btn-hapus"
                 data-tip="Hapus Buku"
                 data-nama="<?= htmlspecialchars($row['nama_buku'], ENT_QUOTES); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
              </a>
            </div>
          </td>
        </tr>
        <?php
          endwhile;
        else:
        ?>
        <tr>
          <td colspan="4">
            <div class="empty">
              <div class="empty-icon">📭</div>
              <p>Belum ada data buku. Silakan tambah buku baru!</p>
            </div>
          </td>
        </tr>
        <?php endif; ?>

      </tbody>
    </table>
  </div><!-- /card -->



<script>
  /* ── Stats ── */
  const total  = <?= $count; ?>;
  const genres = <?= json_encode(array_values(array_unique($genres))); ?>;
  document.getElementById('totalBuku').textContent  = total;
  document.getElementById('totalGenre').textContent = genres.length || 0;

  /* ── Confirm hapus ── */
  document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', e => {
      const nama = btn.dataset.nama || 'buku ini';
      if (!confirm(`Yakin ingin menghapus buku "${nama}"?`)) e.preventDefault();
    });
  });

  /* ── Theme switcher ── */
  const dots = document.querySelectorAll('.theme-dot');
  const html  = document.documentElement;

  // Restore saved theme
  const saved = localStorage.getItem('lib-theme') || 'pink';
  applyTheme(saved);

  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      applyTheme(dot.dataset.t);
      localStorage.setItem('lib-theme', dot.dataset.t);
    });
  });

  function applyTheme(t) {
    html.setAttribute('data-theme', t);
    dots.forEach(d => d.classList.toggle('active', d.dataset.t === t));
  }
</script>

</body>
</html>