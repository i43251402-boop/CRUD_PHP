<?php
/* ═══════════════════════════════════════════════════════
   PERPUSTAKAAN DIGITAL — Single File App
   Semua halaman (list, tambah, edit, hapus) dalam 1 file
   Routing via $_GET['page'] dan $_GET['aksi']
═══════════════════════════════════════════════════════ */

include_once "controllers/BukuController.php";
$controller = new BukuController();

/* ─── Routing & Action Handler ─── */
$page    = $_GET['page']  ?? 'list';
$message = '';
$msgType = '';

/* HAPUS */
if (isset($_GET['hapus'])) {
    $controller->model->delete((int)$_GET['hapus']);
    header("Location: index.php?page=list&msg=hapus");
    exit;
}

/* SIMPAN TAMBAH */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'tambah') {
    $nama  = trim($_POST['nama_buku']  ?? '');
    $jenis = trim($_POST['jenis_buku'] ?? '');
    if ($nama && $jenis) {
        $controller->model->insert($nama, $jenis);
        header("Location: index.php?page=list&msg=tambah");
        exit;
    }
    $message = 'Semua field wajib diisi!';
    $msgType = 'error';
}

/* SIMPAN EDIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'edit') {
    $id    = (int)($_POST['id_buku']    ?? 0);
    $nama  = trim($_POST['nama_buku']   ?? '');
    $jenis = trim($_POST['jenis_buku']  ?? '');
    if ($id && $nama && $jenis) {
        $controller->model->update($id, $nama, $jenis);
        header("Location: index.php?page=list&msg=edit");
        exit;
    }
    $message = 'Semua field wajib diisi!';
    $msgType = 'error';
}

/* Pesan sukses dari redirect */
if (empty($message) && isset($_GET['msg'])) {
    $msgs = [
        'tambah' => '✅ Buku berhasil ditambahkan!',
        'edit'   => '✏️ Buku berhasil diperbarui!',
        'hapus'  => '🗑️ Buku berhasil dihapus!',
    ];
    $message = $msgs[$_GET['msg']] ?? '';
    $msgType = 'success';
}

/* Data untuk halaman edit */
$editRow = [];
if ($page === 'edit' && isset($_GET['id'])) {
    $res = $controller->model->getById((int)$_GET['id']);
    if ($res) $editRow = $res->fetch_assoc();
    if (!$editRow) { header("Location: index.php"); exit; }
}

/* Data untuk list */
$allData = [];
$genres  = [];
if ($page === 'list') {
    $result = $controller->model->getAll();
    if ($result && $result->num_rows > 0) {
        while ($r = $result->fetch_assoc()) {
            $allData[] = $r;
            $genres[]  = $r['jenis_buku'];
        }
    }
}

/* ─── Genre helper ─── */
function getGenreInfo(string $jenis): array {
    $j   = strtolower(trim($jenis));
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

/* Judul per halaman */
$titles = [
    'list'   => 'Data Buku',
    'tambah' => 'Tambah Buku',
    'edit'   => 'Edit Buku',
];
$pageTitle = $titles[$page] ?? 'Perpustakaan';
?>
<!DOCTYPE html>
<html lang="id" data-theme="pink">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $pageTitle ?> — Perpustakaan Digital</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"/>
  <style>
  /* ══════════════════════════════════════════
     THEME TOKENS
  ══════════════════════════════════════════ */
  [data-theme="pink"] {
    --primary:       #e91e8c;
    --primary-light: #f06bb5;
    --primary-soft:  #fce4f0;
    --primary-mid:   #f8c0de;
    --primary-glow:  rgba(233,30,140,.12);
    --header-bg:     linear-gradient(135deg,#e91e8c 0%,#f06bb5 60%,#ff8fab 100%);
  }
  [data-theme="blue"] {
    --primary:       #2196f3;
    --primary-light: #64b5f6;
    --primary-soft:  #e3f2fd;
    --primary-mid:   #bbdefb;
    --primary-glow:  rgba(33,150,243,.12);
    --header-bg:     linear-gradient(135deg,#1565c0 0%,#2196f3 60%,#64b5f6 100%);
  }
  [data-theme="purple"] {
    --primary:       #9c27b0;
    --primary-light: #ce93d8;
    --primary-soft:  #f3e5f5;
    --primary-mid:   #e1bee7;
    --primary-glow:  rgba(156,39,176,.12);
    --header-bg:     linear-gradient(135deg,#6a1b9a 0%,#9c27b0 60%,#ce93d8 100%);
  }
  [data-theme="green"] {
    --primary:       #00897b;
    --primary-light: #4db6ac;
    --primary-soft:  #e0f2f1;
    --primary-mid:   #b2dfdb;
    --primary-glow:  rgba(0,137,123,.12);
    --header-bg:     linear-gradient(135deg,#00574b 0%,#00897b 60%,#4db6ac 100%);
  }
  [data-theme="coral"] {
    --primary:       #f4511e;
    --primary-light: #ff7043;
    --primary-soft:  #fbe9e7;
    --primary-mid:   #ffccbc;
    --primary-glow:  rgba(244,81,30,.12);
    --header-bg:     linear-gradient(135deg,#bf360c 0%,#f4511e 60%,#ff7043 100%);
  }

  /* ══════════════════════════════════════════
     RESET & BASE
  ══════════════════════════════════════════ */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Nunito', sans-serif;
    background: #ffffff;
    min-height: 100vh;
    color: #2d2d2d;
    overflow-x: hidden;
  }

  /* Dot pattern background */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: radial-gradient(var(--primary-mid) 1.2px, transparent 1.2px);
    background-size: 28px 28px;
    opacity: .30;
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

  /* ══════════════════════════════════════════
     THEME SWITCHER
  ══════════════════════════════════════════ */
  .theme-bar {
    position: fixed;
    top: 20px; right: 20px;
    z-index: 999;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1.5px solid var(--primary-mid);
    border-radius: 50px;
    padding: 6px 14px 6px 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,.10);
    animation: slideIn .5s ease both;
  }
  .theme-label {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
  }
  .theme-dot {
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 2.5px solid transparent;
    cursor: pointer;
    transition: transform .2s, border-color .2s;
  }
  .theme-dot:hover    { transform: scale(1.2); }
  .theme-dot.active   { border-color: #333; transform: scale(1.15); }
  .theme-dot[data-t="pink"]   { background: #e91e8c; }
  .theme-dot[data-t="blue"]   { background: #2196f3; }
  .theme-dot[data-t="purple"] { background: #9c27b0; }
  .theme-dot[data-t="green"]  { background: #00897b; }
  .theme-dot[data-t="coral"]  { background: #f4511e; }

  /* ══════════════════════════════════════════
     HERO HEADER
  ══════════════════════════════════════════ */
  .hero {
    background: var(--header-bg);
    border-radius: 0 0 36px 36px;
    padding: 52px 40px 56px;
    text-align: center;
    margin-bottom: 36px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 16px 48px var(--primary-glow);
    animation: fadeDown .55s ease both;
  }
  .hero::before {
    content: ''; position: absolute;
    width: 260px; height: 260px; top:-80px; left:-60px;
    border-radius:50%; background:rgba(255,255,255,.09);
  }
  .hero::after {
    content: ''; position: absolute;
    width: 180px; height: 180px; bottom:-60px; right:-40px;
    border-radius:50%; background:rgba(255,255,255,.09);
  }
  .hero-tag {
    display: inline-block;
    background: rgba(255,255,255,.22);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    padding: 5px 18px;
    border-radius: 20px;
    margin-bottom: 14px;
    border: 1px solid rgba(255,255,255,.35);
    position: relative; z-index: 1;
  }
  .hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3.2rem);
    color: #fff;
    font-weight: 700;
    text-shadow: 0 2px 12px rgba(0,0,0,.15);
    position: relative; z-index: 1;
  }
  .hero p {
    margin-top: 8px;
    color: rgba(255,255,255,.82);
    font-size: 14.5px;
    font-weight: 500;
    position: relative; z-index: 1;
  }
  .hero-wave {
    position: absolute;
    bottom: -1px; left: 0; right: 0;
  }

  /* ══════════════════════════════════════════
     BREADCRUMB NAV
  ══════════════════════════════════════════ */
  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 22px;
    font-size: 13px;
    font-weight: 600;
    animation: fadeUp .5s ease .1s both;
  }
  .breadcrumb a {
    color: var(--primary);
    text-decoration: none;
    transition: opacity .2s;
  }
  .breadcrumb a:hover { opacity: .7; }
  .breadcrumb .sep { color: #ccc; }
  .breadcrumb .current { color: #888; }

  /* ══════════════════════════════════════════
     TOAST / ALERT
  ══════════════════════════════════════════ */
  .toast {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 22px;
    animation: fadeUp .4s ease both;
    border: 1.5px solid;
  }
  .toast.success { background:#f0fdf4; color:#166534; border-color:#bbf7d0; }
  .toast.error   { background:#fef2f2; color:#991b1b; border-color:#fecaca; }

  /* ══════════════════════════════════════════
     STATS ROW (list page only)
  ══════════════════════════════════════════ */
  .stats-row {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
    animation: fadeUp .5s ease .1s both;
  }
  .stat-chip {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--primary-soft);
    border: 1.5px solid var(--primary-mid);
    border-radius: 16px;
    padding: 14px 22px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--primary);
  }
  .stat-chip .ico { font-size: 22px; }
  .stat-chip .val { font-size: 24px; font-weight: 900; line-height: 1; }
  .stat-chip .lbl { font-size: 12px; font-weight: 600; color: var(--primary-light); }

  /* ══════════════════════════════════════════
     CARD
  ══════════════════════════════════════════ */
  .card {
    background: #fff;
    border: 1.5px solid var(--primary-mid);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 8px 40px var(--primary-glow), 0 2px 8px rgba(0,0,0,.05);
    animation: fadeUp .55s ease .15s both;
  }

  /* ── Toolbar (list) ── */
  .toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 26px;
    background: var(--primary-soft);
    border-bottom: 1.5px solid var(--primary-mid);
    flex-wrap: wrap;
    gap: 12px;
  }
  .toolbar-title {
    font-size: 14.5px;
    font-weight: 800;
    color: var(--primary);
  }

  /* ── Form header (tambah/edit) ── */
  .form-header {
    padding: 22px 28px 18px;
    background: var(--primary-soft);
    border-bottom: 1.5px solid var(--primary-mid);
  }
  .form-header h2 {
    font-size: 17px;
    font-weight: 800;
    color: var(--primary);
  }
  .form-header p {
    font-size: 13px;
    color: var(--primary-light);
    margin-top: 2px;
    font-weight: 600;
  }

  /* ══════════════════════════════════════════
     TABLE
  ══════════════════════════════════════════ */
  table { width: 100%; border-collapse: collapse; }
  thead th {
    padding: 13px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--primary);
    background: var(--primary-soft);
    border-bottom: 2px solid var(--primary-mid);
  }
  thead th:first-child { text-align: center; width: 56px; }
  thead th:last-child  { text-align: center; width: 112px; }

  tbody tr {
    border-bottom: 1px solid #f5f5f5;
    transition: background .18s;
    animation: rowIn .35s ease both;
  }
  tbody tr:hover { background: var(--primary-soft); }
  tbody td {
    padding: 15px 20px;
    font-size: 14.5px;
    vertical-align: middle;
    color: #333;
  }
  .td-no   { text-align:center; font-weight:800; color:var(--primary-light); font-size:13px; }
  .td-nama { font-weight:700; color:#111; }
  .td-aksi { text-align:center; }

  /* ── Genre badge ── */
  .genre-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
    border: 1.5px solid;
    white-space: nowrap;
  }
  .genre-pendidikan { background:#e3f2fd; color:#1565c0; border-color:#90caf9; }
  .genre-fiksi      { background:#f3e5f5; color:#7b1fa2; border-color:#ce93d8; }
  .genre-nonfiksi   { background:#e8f5e9; color:#2e7d32; border-color:#a5d6a7; }
  .genre-penelitian { background:#fff8e1; color:#f57f17; border-color:#ffe082; }
  .genre-sains      { background:#e0f7fa; color:#00695c; border-color:#80cbc4; }
  .genre-sejarah    { background:#fce4ec; color:#c62828; border-color:#ef9a9a; }
  .genre-default    { background:#f5f5f5; color:#616161; border-color:#e0e0e0; }

  /* ══════════════════════════════════════════
     ACTION BUTTONS
  ══════════════════════════════════════════ */
  .action-group { display:inline-flex; gap:8px; align-items:center; }

  .btn-action {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .22s ease;
    border: 1.5px solid;
  }
  .btn-action svg { width:15px; height:15px; }

  /* Tooltip */
  .btn-action::after {
    content: attr(data-tip);
    position: absolute;
    bottom: calc(100% + 7px); left: 50%;
    transform: translateX(-50%);
    background: #222; color: #fff;
    font-size: 11px; font-weight: 600;
    padding: 3px 9px; border-radius: 6px;
    white-space: nowrap;
    opacity: 0; pointer-events: none;
    transition: opacity .18s;
  }
  .btn-action:hover::after { opacity: 1; }

  .btn-edit  { background:#e3f2fd; border-color:#90caf9; color:#1565c0; }
  .btn-edit:hover  { background:#bbdefb; transform:translateY(-2px); box-shadow:0 4px 12px rgba(33,150,243,.2); }
  .btn-hapus { background:#fce4ec; border-color:#f48fb1; color:#c62828; }
  .btn-hapus:hover { background:#f8bbd0; transform:translateY(-2px); box-shadow:0 4px 12px rgba(244,67,54,.2); }

  /* ══════════════════════════════════════════
     SHARED BUTTON STYLES
  ══════════════════════════════════════════ */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    font-size: 14px;
    padding: 11px 24px;
    border-radius: 12px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .25s ease;
  }
  .btn svg { width:16px; height:16px; }

  .btn-primary {
    background: var(--primary);
    color: #fff;
    box-shadow: 0 4px 16px var(--primary-glow);
  }
  .btn-primary:hover {
    filter: brightness(1.08);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--primary-glow);
  }

  .btn-outline {
    background: #fff;
    color: var(--primary);
    border: 2px solid var(--primary);
  }
  .btn-outline:hover {
    background: var(--primary-soft);
    transform: translateY(-2px);
  }

  .btn-danger {
    background: #fce4ec;
    color: #c62828;
    border: 2px solid #f48fb1;
  }
  .btn-danger:hover {
    background: #f8bbd0;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(244,67,54,.15);
  }

  .btn-gray {
    background: #f5f5f5;
    color: #555;
    border: 2px solid #e0e0e0;
  }
  .btn-gray:hover { background:#ebebeb; transform:translateY(-2px); }

  /* ══════════════════════════════════════════
     FORM ELEMENTS
  ══════════════════════════════════════════ */
  .form-body { padding: 28px 28px 32px; }

  .form-group { margin-bottom: 22px; }

  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 800;
    color: #444;
    margin-bottom: 8px;
    letter-spacing: .3px;
  }

  .form-group label span.req { color: var(--primary); margin-left: 2px; }

  .form-control {
    width: 100%;
    padding: 13px 16px;
    font-family: 'Nunito', sans-serif;
    font-size: 14.5px;
    font-weight: 600;
    color: #222;
    background: #fafafa;
    border: 2px solid #e8e8e8;
    border-radius: 12px;
    outline: none;
    transition: border-color .22s, box-shadow .22s, background .22s;
    appearance: none;
  }
  .form-control:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 4px var(--primary-glow);
  }

  select.form-control {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 42px;
  }

  .form-hint {
    font-size: 12px;
    color: #aaa;
    font-weight: 600;
    margin-top: 6px;
  }

  .form-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    padding-top: 8px;
    flex-wrap: wrap;
  }

  /* ══════════════════════════════════════════
     KONFIRMASI HAPUS PANEL
  ══════════════════════════════════════════ */
  .confirm-box {
    padding: 36px 32px;
    text-align: center;
  }
  .confirm-icon {
    font-size: 56px;
    margin-bottom: 16px;
    display: block;
    animation: wiggle .6s ease both;
  }
  .confirm-box h2 {
    font-size: 22px;
    font-weight: 800;
    color: #c62828;
    margin-bottom: 10px;
  }
  .confirm-box p {
    font-size: 15px;
    color: #666;
    margin-bottom: 28px;
    line-height: 1.6;
  }
  .confirm-box strong { color: #222; }
  .confirm-actions {
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
  }

  /* ══════════════════════════════════════════
     BOTTOM ADD BUTTON (list)
  ══════════════════════════════════════════ */
  .footer-strip {
    display: flex;
    justify-content: center;
    margin-top: 24px;
    animation: fadeUp .55s ease .3s both;
  }

  /* ══════════════════════════════════════════
     EMPTY STATE
  ══════════════════════════════════════════ */
  .empty { text-align:center; padding:60px 20px; color:#bbb; }
  .empty-icon { font-size:52px; margin-bottom:12px; }
  .empty p { font-size:15px; font-weight:700; }

  /* ══════════════════════════════════════════
     ANIMATIONS
  ══════════════════════════════════════════ */
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
  @keyframes wiggle {
    0%,100% { transform:rotate(0); }
    25% { transform:rotate(-12deg); }
    75% { transform:rotate(12deg); }
  }

  tbody tr:nth-child(1)   { animation-delay:.12s; }
  tbody tr:nth-child(2)   { animation-delay:.18s; }
  tbody tr:nth-child(3)   { animation-delay:.24s; }
  tbody tr:nth-child(4)   { animation-delay:.30s; }
  tbody tr:nth-child(5)   { animation-delay:.36s; }
  tbody tr:nth-child(n+6) { animation-delay:.40s; }

  /* Responsive */
  @media (max-width: 640px) {
    .hero { padding: 40px 20px 46px; border-radius:0 0 24px 24px; }
    .theme-bar { top:12px; right:12px; padding:5px 10px 5px 8px; gap:6px; }
    .theme-dot { width:18px; height:18px; }
    .theme-label { display:none; }
    .toolbar { flex-direction:column; align-items:flex-start; }
    thead th:nth-child(3), tbody td:nth-child(3) { display:none; }
    .form-body { padding:20px 18px 24px; }
    .confirm-box { padding:28px 20px; }
  }
  </style>
</head>
<body>

<!-- ══ Theme Switcher ══ -->
<div class="theme-bar">
  <span class="theme-label">Tema</span>
  <span class="theme-dot" data-t="pink"   title="Pink"></span>
  <span class="theme-dot" data-t="blue"   title="Blue"></span>
  <span class="theme-dot" data-t="purple" title="Purple"></span>
  <span class="theme-dot" data-t="green"  title="Green"></span>
  <span class="theme-dot" data-t="coral"  title="Coral"></span>
</div>

<div class="page">

  <!-- ══ Hero ══ -->
  <div class="hero">
    <div class="hero-tag">📚 Perpustakaan Digital</div>
    <h1><?= $pageTitle ?></h1>
    <p>
      <?php if ($page === 'list')   echo 'Kelola seluruh koleksi buku perpustakaan'; ?>
      <?php if ($page === 'tambah') echo 'Isi formulir untuk menambahkan buku baru'; ?>
      <?php if ($page === 'edit')   echo 'Perbarui informasi buku yang dipilih'; ?>
      <?php if ($page === 'hapus')  echo 'Konfirmasi penghapusan buku'; ?>
    </p>
    <svg class="hero-wave" viewBox="0 0 1440 44" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0,22 C360,44 1080,0 1440,22 L1440,44 L0,44 Z" fill="#ffffff"/>
    </svg>
  </div>

  <!-- ══ Breadcrumb (bukan list) ══ -->
  <?php if ($page !== 'list'): ?>
  <div class="breadcrumb">
    <a href="index.php">🏠 Beranda</a>
    <span class="sep">›</span>
    <a href="index.php?page=list">Data Buku</a>
    <span class="sep">›</span>
    <span class="current"><?= $pageTitle ?></span>
  </div>
  <?php endif; ?>

  <!-- ══ Toast Alert ══ -->
  <?php if ($message): ?>
  <div class="toast <?= $msgType ?>">
    <?= $message ?>
  </div>
  <?php endif; ?>


  <?php /* ══════════════════════════════════════════
           PAGE: LIST
         ══════════════════════════════════════════ */ ?>
  <?php if ($page === 'list'): ?>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-chip">
      <span class="ico">📖</span>
      <div>
        <div class="val"><?= count($allData) ?></div>
        <div class="lbl">Total Buku</div>
      </div>
    </div>
    <div class="stat-chip">
      <span class="ico">🗂️</span>
      <div>
        <div class="val"><?= count(array_unique($genres)) ?></div>
        <div class="lbl">Jenis Buku</div>
      </div>
    </div>
  </div>

  <!-- Card Table -->
  <div class="card">
    <div class="toolbar">
      <span class="toolbar-title">📋 Daftar Koleksi Buku</span>
      <a href="index.php?page=tambah" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        Tambah Buku
      </a>
    </div>

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
        <?php if ($allData): ?>
          <?php $no = 1; foreach ($allData as $row):
                [$gClass, $gIcon] = getGenreInfo($row['jenis_buku']); ?>
          <tr>
            <td class="td-no"><?= $no++ ?></td>
            <td class="td-nama"><?= htmlspecialchars($row['nama_buku']) ?></td>
            <td>
              <span class="genre-badge <?= $gClass ?>">
                <?= $gIcon ?> <?= htmlspecialchars($row['jenis_buku']) ?>
              </span>
            </td>
            <td class="td-aksi">
              <div class="action-group">
                <a href="index.php?page=edit&id=<?= $row['id_buku'] ?>"
                   class="btn-action btn-edit" data-tip="Edit Buku">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </a>
                <a href="index.php?page=hapus&id=<?= $row['id_buku'] ?>&nama=<?= urlencode($row['nama_buku']) ?>"
                   class="btn-action btn-hapus" data-tip="Hapus Buku">
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
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4">
            <div class="empty">
              <div class="empty-icon">📭</div>
              <p>Belum ada data buku. Silakan tambah buku baru!</p>
            </div>
          </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Bottom button -->
  <div class="footer-strip">
    <a href="index.php?page=tambah" class="btn btn-outline">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      Tambah Buku Baru
    </a>
  </div>

  <?php endif; /* end list */ ?>


  <?php /* ══════════════════════════════════════════
           PAGE: TAMBAH
         ══════════════════════════════════════════ */ ?>
  <?php if ($page === 'tambah'): ?>

  <div class="card">
    <div class="form-header">
      <h2>✏️ Formulir Tambah Buku</h2>
      <p>Lengkapi informasi buku yang akan ditambahkan ke koleksi</p>
    </div>
    <div class="form-body">
      <form method="POST" action="index.php?page=tambah">

        <div class="form-group">
          <label>Nama Buku <span class="req">*</span></label>
          <input type="text" name="nama_buku" class="form-control"
                 placeholder="Masukkan judul buku..."
                 value="<?= htmlspecialchars($_POST['nama_buku'] ?? '') ?>"
                 required/>
          <div class="form-hint">Tulis judul lengkap buku sesuai sampul</div>
        </div>

        <div class="form-group">
          <label>Jenis Buku <span class="req">*</span></label>
          <select name="jenis_buku" class="form-control" required>
            <option value="" disabled selected>-- Pilih jenis buku --</option>
            <?php
            $jenisOptions = [
              'Pendidikan','Fiksi','Non Fiksi','Penelitian',
              'Sains','Sejarah','Teknologi','Agama','Ekonomi','Kesehatan'
            ];
            foreach ($jenisOptions as $opt):
              $sel = (($_POST['jenis_buku'] ?? '') === $opt) ? 'selected' : '';
            ?>
            <option value="<?= $opt ?>" <?= $sel ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
          <div class="form-hint">Pilih kategori yang paling sesuai</div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14z"/>
              <polyline points="17 21 17 13 7 13 7 21"/>
              <polyline points="7 3 7 8 15 8"/>
            </svg>
            Simpan Buku
          </button>
          <a href="index.php?page=list" class="btn btn-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Batal
          </a>
        </div>

      </form>
    </div>
  </div>

  <?php endif; /* end tambah */ ?>


  <?php /* ══════════════════════════════════════════
           PAGE: EDIT
         ══════════════════════════════════════════ */ ?>
  <?php if ($page === 'edit' && $editRow): ?>

  <div class="card">
    <div class="form-header">
      <h2>🔧 Formulir Edit Buku</h2>
      <p>Perbarui data buku — ID #<?= $editRow['id_buku'] ?></p>
    </div>
    <div class="form-body">
      <form method="POST" action="index.php?page=edit">
        <input type="hidden" name="id_buku" value="<?= $editRow['id_buku'] ?>"/>

        <div class="form-group">
          <label>Nama Buku <span class="req">*</span></label>
          <input type="text" name="nama_buku" class="form-control"
                 placeholder="Masukkan judul buku..."
                 value="<?= htmlspecialchars($_POST['nama_buku'] ?? $editRow['nama_buku']) ?>"
                 required/>
        </div>

        <div class="form-group">
          <label>Jenis Buku <span class="req">*</span></label>
          <select name="jenis_buku" class="form-control" required>
            <option value="" disabled>-- Pilih jenis buku --</option>
            <?php
            $jenisOptions = [
              'Pendidikan','Fiksi','Non Fiksi','Penelitian',
              'Sains','Sejarah','Teknologi','Agama','Ekonomi','Kesehatan'
            ];
            $currentJenis = $_POST['jenis_buku'] ?? $editRow['jenis_buku'];
            foreach ($jenisOptions as $opt):
              $sel = ($currentJenis === $opt) ? 'selected' : '';
            ?>
            <option value="<?= $opt ?>" <?= $sel ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14z"/>
              <polyline points="17 21 17 13 7 13 7 21"/>
              <polyline points="7 3 7 8 15 8"/>
            </svg>
            Update Buku
          </button>
          <a href="index.php?page=list" class="btn btn-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Batal
          </a>
        </div>

      </form>
    </div>
  </div>

  <?php endif; /* end edit */ ?>


  <?php /* ══════════════════════════════════════════
           PAGE: HAPUS (konfirmasi)
         ══════════════════════════════════════════ */ ?>
  <?php if ($page === 'hapus' && isset($_GET['id'])): ?>

  <?php $namaHapus = urldecode($_GET['nama'] ?? 'buku ini'); ?>

  <div class="card">
    <div class="confirm-box">
      <span class="confirm-icon">🗑️</span>
      <h2>Hapus Buku Ini?</h2>
      <p>
        Kamu akan menghapus buku<br/>
        <strong>"<?= htmlspecialchars($namaHapus) ?>"</strong><br/><br/>
        Tindakan ini <strong>tidak bisa dibatalkan</strong>.<br/>
        Pastikan kamu yakin sebelum melanjutkan.
      </p>
      <div class="confirm-actions">
        <a href="index.php?hapus=<?= (int)$_GET['id'] ?>" class="btn btn-danger">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
          </svg>
          Ya, Hapus Sekarang
        </a>
        <a href="index.php?page=list" class="btn btn-gray">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
          </svg>
          Batal, Kembali
        </a>
      </div>
    </div>
  </div>

  <?php endif; /* end hapus */ ?>

</div><!-- /page -->

<script>
/* ── Theme Switcher ── */
const dots = document.querySelectorAll('.theme-dot');
const html  = document.documentElement;

const saved = localStorage.getItem('lib-theme') || 'pink';
applyTheme(saved);

dots.forEach(d => d.addEventListener('click', () => {
  applyTheme(d.dataset.t);
  localStorage.setItem('lib-theme', d.dataset.t);
}));

function applyTheme(t) {
  html.setAttribute('data-theme', t);
  dots.forEach(d => d.classList.toggle('active', d.dataset.t === t));
}
</script>

</body>
</html>