<?php
/**
 * index.php — Halaman Utama Registrasi Pembayaran UKT Mahasiswa
 * 
 * Menampilkan data mahasiswa dalam SATU tabel terpadu dengan sidebar navigasi
 * untuk memfilter berdasarkan jenis pembiayaan: Mandiri, Bidikmisi, dan Prestasi.
 * Menggunakan OOP (Polimorfisme) untuk menghitung tagihan semester.
 */

// ============================================================
// 1. INCLUDE SEMUA CLASS
// ============================================================
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikMisi.php';
require_once 'MahasiswaPrestasi.php';

// ============================================================
// 2. KONEKSI DATABASE MENGGUNAKAN PDO
// ============================================================
$host   = 'localhost';
$dbname = 'db_uas_pbo_trpl1a_almassalsabilafidiarti';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='padding:2rem;text-align:center;color:#DC9B9B;font-family:sans-serif;'>
            <h2>⚠ Koneksi Database Gagal</h2>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}

// ============================================================
// 3. QUERY & MAPPING KE OBJEK (POLIMORFISME)
// ============================================================
$stmt = $pdo->query("SELECT * FROM tabel_mahasiswa ORDER BY nama_mahasiswa ASC");
$rows = $stmt->fetchAll();

// Array untuk menampung semua objek mahasiswa beserta data mentahnya
$allStudents = [];

foreach ($rows as $row) {
    $obj = null;
    switch ($row['jenis_pembiayaan']) {
        case 'mandiri':
            $obj = new MahasiswaMandiri(
                $row['id_mahasiswa'],
                $row['nama_mahasiswa'],
                $row['nim'],
                $row['semester'],
                $row['tarif_ukt_nominal'],
                $row['golongan_ukt'],
                $row['nama_wali']
            );
            break;
        case 'bidikmisi':
            $obj = new MahasiswaBidikMisi(
                $row['id_mahasiswa'],
                $row['nama_mahasiswa'],
                $row['nim'],
                $row['semester'],
                $row['tarif_ukt_nominal'],
                $row['nomor_kip_kuliah'],
                $row['dana_saku_subsidi']
            );
            break;
        case 'prestasi':
            $obj = new MahasiswaPrestasi(
                $row['id_mahasiswa'],
                $row['nama_mahasiswa'],
                $row['nim'],
                $row['semester'],
                $row['tarif_ukt_nominal'],
                $row['nama_instansi_beasiswa'],
                $row['minimal_ipk_syarat']
            );
            break;
    }
    if ($obj) {
        $allStudents[] = [
            'raw' => $row,
            'obj' => $obj,
            'tagihan' => $obj->hitungTagihanSemester(),
        ];
    }
}

// Hitung statistik
$counts = ['all' => count($allStudents), 'mandiri' => 0, 'bidikmisi' => 0, 'prestasi' => 0];
$totals = ['all' => 0, 'mandiri' => 0, 'bidikmisi' => 0, 'prestasi' => 0];
foreach ($allStudents as $s) {
    $type = $s['raw']['jenis_pembiayaan'];
    $counts[$type]++;
    $totals[$type] += $s['tagihan'];
    $totals['all'] += $s['tagihan'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Registrasi Pembayaran UKT Mahasiswa — UAS PBO TRPL-1A">
    <title>Registrasi Pembayaran UKT Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ===== RESET & VARIABLES ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --clr-mint:    #C0E1D2;
            --clr-sage:    #E5EEE4;
            --clr-cream:   #F6F4E8;
            --clr-rose:    #DC9B9B;
            --clr-dark:    #2D3436;
            --clr-text:    #3D4F5F;
            --clr-subtle:  #7B8D9A;
            --clr-white:   #FFFFFF;
            --clr-border:  #E2E8F0;
            --clr-bg:      #F0F2F5;

            --sidebar-w:   270px;
            --radius:      12px;
            --radius-sm:   8px;
            --shadow:      0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            --shadow-lg:   0 4px 24px rgba(0,0,0,0.08);
            --transition:  0.25s cubic-bezier(.4,0,.2,1);
            --font:        'Inter', system-ui, -apple-system, sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font);
            background: var(--clr-bg);
            color: var(--clr-text);
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--clr-dark);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform var(--transition);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand h2 {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--clr-white);
        }
        .sidebar-brand .logo-box {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--clr-mint), var(--clr-sage));
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .sidebar-brand .sub {
            font-size: 0.68rem;
            color: rgba(255,255,255,0.35);
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 0.3rem;
            padding-left: 2.65rem;
        }

        .sidebar-label {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.25);
        }

        .sidebar-nav { flex: 1; padding: 0 0.65rem; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            width: 100%;
            padding: 0.65rem 0.75rem;
            margin-bottom: 1px;
            border: none;
            border-radius: var(--radius-sm);
            background: transparent;
            color: rgba(255,255,255,0.55);
            font-family: var(--font);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-align: left;
            position: relative;
        }
        .nav-item svg { width: 19px; height: 19px; flex-shrink: 0; opacity: 0.6; transition: var(--transition); }
        .nav-item:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85); }
        .nav-item:hover svg { opacity: 0.9; }

        .nav-item.active {
            background: rgba(192,225,210,0.12);
            color: var(--clr-mint);
        }
        .nav-item.active svg { opacity: 1; color: var(--clr-mint); }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -0.65rem;
            top: 50%; transform: translateY(-50%);
            width: 3px; height: 55%;
            border-radius: 0 3px 3px 0;
            background: var(--clr-mint);
        }

        .nav-item .badge {
            margin-left: auto;
            padding: 0.1rem 0.5rem;
            border-radius: 50px;
            background: rgba(255,255,255,0.08);
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 26px;
            text-align: center;
        }
        .nav-item.active .badge { background: rgba(192,225,210,0.2); }

        /* Color dots for categories */
        .nav-item .color-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; gap: 0.6rem;
        }
        .sidebar-footer .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--clr-rose), var(--clr-mint));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .sidebar-footer .uname { font-size: 0.8rem; font-weight: 600; color: #fff; }
        .sidebar-footer .urole { font-size: 0.66rem; color: rgba(255,255,255,0.4); }

        /* ===== MOBILE OVERLAY & HEADER ===== */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        .mobile-bar {
            display: none;
            position: fixed; top: 0; left: 0; right: 0;
            height: 56px;
            background: var(--clr-dark);
            z-index: 998;
            align-items: center;
            padding: 0 1rem;
            gap: 0.75rem;
        }
        .burger {
            width: 38px; height: 38px;
            border: none; background: rgba(255,255,255,0.08);
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 4px;
        }
        .burger span { display: block; width: 18px; height: 2px; background: #fff; border-radius: 1px; }
        .mobile-bar .m-title { font-size: 0.95rem; font-weight: 700; color: #fff; }

        /* ===== MAIN CONTENT ===== */
        .main { margin-left: var(--sidebar-w); flex: 1; min-height: 100vh; }

        .topbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(240,242,245,0.88);
            backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--clr-border);
            padding: 0.85rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-left h1 {
            font-size: 1.2rem; font-weight: 800; color: var(--clr-dark);
            display: flex; align-items: center; gap: 0.45rem;
        }
        .topbar-left .crumb {
            font-size: 0.74rem; color: var(--clr-subtle); font-weight: 500; margin-top: 0.1rem;
        }
        .topbar-right {
            display: flex; align-items: center; gap: 0.75rem;
        }
        .search-box {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border: 1px solid var(--clr-border);
            border-radius: 50px;
            background: var(--clr-white);
            transition: var(--transition);
        }
        .search-box:focus-within { border-color: var(--clr-mint); box-shadow: 0 0 0 3px rgba(192,225,210,0.3); }
        .search-box svg { width: 16px; height: 16px; color: var(--clr-subtle); flex-shrink: 0; }
        .search-box input {
            border: none; outline: none; background: transparent;
            font-family: var(--font); font-size: 0.82rem; color: var(--clr-dark);
            width: 180px;
        }
        .search-box input::placeholder { color: var(--clr-subtle); }

        .content { padding: 1.75rem 2rem; }

        /* ===== STAT CARDS ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .stat-card {
            background: var(--clr-white);
            border-radius: var(--radius);
            padding: 1.1rem 1.25rem;
            box-shadow: var(--shadow);
            border-top: 3px solid var(--accent, var(--clr-mint));
            transition: var(--transition);
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .stat-card .s-label {
            font-size: 0.72rem; font-weight: 600; color: var(--clr-subtle);
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .stat-card .s-value {
            font-size: 1.5rem; font-weight: 800; color: var(--clr-dark); margin: 0.15rem 0;
        }
        .stat-card .s-sub {
            font-size: 0.72rem; color: var(--clr-subtle); font-weight: 500;
        }

        /* ===== TABLE CONTAINER ===== */
        .table-wrap {
            background: var(--clr-white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .table-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--clr-border);
        }
        .table-header h2 {
            font-size: 1rem; font-weight: 700; color: var(--clr-dark);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .table-header .row-count {
            font-size: 0.75rem; font-weight: 600; color: var(--clr-subtle);
            background: var(--clr-bg); padding: 0.2rem 0.6rem; border-radius: 50px;
        }

        .table-scroll { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.84rem;
        }
        thead {
            background: var(--clr-cream);
            position: sticky; top: 0; z-index: 2;
        }
        thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--clr-subtle);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--clr-border);
            white-space: nowrap;
        }
        thead th:first-child { padding-left: 1.5rem; }

        tbody tr {
            transition: background var(--transition);
        }
        tbody tr:hover { background: rgba(192,225,210,0.08); }
        tbody tr:not(:last-child) td { border-bottom: 1px solid var(--clr-border); }

        tbody td {
            padding: 0.7rem 1rem;
            color: var(--clr-text);
            vertical-align: middle;
        }
        tbody td:first-child { padding-left: 1.5rem; }

        /* Row Number */
        .row-num {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--clr-subtle);
            width: 40px;
        }

        /* Student name cell */
        .cell-name {
            display: flex; align-items: center; gap: 0.65rem;
        }
        .cell-name .initials {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 700;
            color: var(--clr-dark);
            flex-shrink: 0;
        }
        .cell-name .name-text {
            font-weight: 600; color: var(--clr-dark); white-space: nowrap;
        }

        /* Type badge */
        .type-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .type-badge.mandiri   { background: rgba(192,225,210,0.3); color: #3a7d5c; }
        .type-badge.bidikmisi { background: rgba(220,155,155,0.25); color: #9e4f4f; }
        .type-badge.prestasi  { background: rgba(229,238,228,0.5); color: #4a6e49; }

        /* Tagihan cell */
        .cell-tagihan {
            font-weight: 700; color: var(--clr-dark); white-space: nowrap;
        }
        .cell-tagihan.free {
            color: #2d6a4f;
            font-weight: 800;
        }

        /* Null / empty value */
        .null-val { color: var(--clr-subtle); opacity: 0.5; font-style: italic; font-size: 0.78rem; }

        /* No results */
        .no-results {
            display: none;
            text-align: center;
            padding: 3rem 1rem;
            color: var(--clr-subtle);
        }
        .no-results.show { display: block; }
        .no-results .nr-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .no-results p { font-size: 0.9rem; }

        /* ===== FOOTER ===== */
        .page-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            margin-top: 2rem;
            border-top: 1px solid var(--clr-border);
            font-size: 0.75rem;
            color: var(--clr-subtle);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeUp 0.4s ease-out both; }
        tbody tr { animation: fadeUp 0.3s ease-out both; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .search-box input { width: 140px; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .mobile-bar { display: flex; }
            .main { margin-left: 0; padding-top: 56px; }
            .topbar { display: none; }
            .content { padding: 1rem; }
            .stats { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .stat-card .s-value { font-size: 1.2rem; }
            .table-header { padding: 0.85rem 1rem; }
            thead th, tbody td { padding: 0.6rem 0.7rem; font-size: 0.78rem; }
            thead th:first-child, tbody td:first-child { padding-left: 0.85rem; }
        }
        @media (max-width: 480px) {
            .content { padding: 0.75rem; }
            .stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- MOBILE OVERLAY -->
<div class="sidebar-overlay" id="overlay"></div>

<!-- MOBILE HEADER -->
<div class="mobile-bar">
    <button class="burger" id="burgerBtn" aria-label="Toggle Menu">
        <span></span><span></span><span></span>
    </button>
    <span class="m-title">💰 Registrasi UKT</span>
</div>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h2><span class="logo-box">💰</span>Sistem UKT</h2>
        <p class="sub">TRPL-1A &bull; UAS PBO</p>
    </div>

    <div class="sidebar-label">Filter Pembiayaan</div>
    <nav class="sidebar-nav">
        <button class="nav-item active" data-filter="all" id="nav-all">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Semua Mahasiswa</span>
            <span class="badge"><?= $counts['all'] ?></span>
        </button>
        <button class="nav-item" data-filter="mandiri" id="nav-mandiri">
            <span class="color-dot" style="background:var(--clr-mint)"></span>
            <span>Mahasiswa Mandiri</span>
            <span class="badge"><?= $counts['mandiri'] ?></span>
        </button>
        <button class="nav-item" data-filter="bidikmisi" id="nav-bidikmisi">
            <span class="color-dot" style="background:var(--clr-rose)"></span>
            <span>Mahasiswa Bidikmisi</span>
            <span class="badge"><?= $counts['bidikmisi'] ?></span>
        </button>
        <button class="nav-item" data-filter="prestasi" id="nav-prestasi">
            <span class="color-dot" style="background:var(--clr-sage)"></span>
            <span>Mahasiswa Prestasi</span>
            <span class="badge"><?= $counts['prestasi'] ?></span>
        </button>
    </nav>

    <div class="sidebar-footer">
        <div class="avatar">AF</div>
        <div>
            <div class="uname">Almas Salsabila F.</div>
            <div class="urole">Admin &bull; TRPL-1A</div>
        </div>
    </div>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<main class="main">
    <!-- Top Bar -->
    <div class="topbar">
        <div class="topbar-left">
            <h1><span id="tb-icon">📊</span> <span id="tb-title">Semua Mahasiswa</span></h1>
            <div class="crumb">Registrasi Pembayaran UKT &rsaquo; <span id="tb-crumb">Dashboard</span></div>
        </div>
        <div class="topbar-right">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchInput" placeholder="Cari nama atau NIM...">
            </div>
        </div>
    </div>

    <div class="content">
        <!-- STAT CARDS -->
        <div class="stats animate-in">
            <div class="stat-card" style="--accent:var(--clr-mint);">
                <div class="s-label">Total Mahasiswa</div>
                <div class="s-value" id="stat-total"><?= $counts['all'] ?></div>
                <div class="s-sub">Rp <?= number_format($totals['all'], 0, ',', '.') ?></div>
            </div>
            <div class="stat-card" style="--accent:var(--clr-mint);">
                <div class="s-label">Mandiri</div>
                <div class="s-value"><?= $counts['mandiri'] ?></div>
                <div class="s-sub">Rp <?= number_format($totals['mandiri'], 0, ',', '.') ?></div>
            </div>
            <div class="stat-card" style="--accent:var(--clr-rose);">
                <div class="s-label">Bidikmisi</div>
                <div class="s-value"><?= $counts['bidikmisi'] ?></div>
                <div class="s-sub">Rp <?= number_format($totals['bidikmisi'], 0, ',', '.') ?></div>
            </div>
            <div class="stat-card" style="--accent:var(--clr-sage);">
                <div class="s-label">Prestasi</div>
                <div class="s-value"><?= $counts['prestasi'] ?></div>
                <div class="s-sub">Rp <?= number_format($totals['prestasi'], 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- SINGLE UNIFIED TABLE -->
        <div class="table-wrap animate-in" style="animation-delay:0.1s">
            <div class="table-header">
                <h2>
                    📋 Data Mahasiswa
                    <span class="row-count" id="rowCount"><?= $counts['all'] ?> data</span>
                </h2>
            </div>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Semester</th>
                            <th>Jenis Pembiayaan</th>
                            <th>Golongan UKT</th>
                            <th>Nama Wali</th>
                            <th>No. KIP Kuliah</th>
                            <th>Dana Saku Subsidi</th>
                            <th>Instansi Beasiswa</th>
                            <th>Min. IPK Syarat</th>
                            <th>Tagihan Semester</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                        $no = 1;
                        foreach ($allStudents as $s):
                            $r = $s['raw'];
                            $tagihan = $s['tagihan'];
                            $tagihanFmt = number_format($tagihan, 0, ',', '.');
                            $type = $r['jenis_pembiayaan'];

                            // Initials for avatar
                            $words = explode(' ', $r['nama_mahasiswa']);
                            $initials = strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));

                            // Avatar background color
                            $avatarBg = match($type) {
                                'mandiri'   => 'rgba(192,225,210,0.45)',
                                'bidikmisi' => 'rgba(220,155,155,0.3)',
                                'prestasi'  => 'rgba(229,238,228,0.5)',
                                default     => 'rgba(200,200,200,0.3)',
                            };

                            // Type label
                            $typeLabel = match($type) {
                                'mandiri'   => 'Mandiri',
                                'bidikmisi' => 'Bidikmisi',
                                'prestasi'  => 'Prestasi',
                                default     => '-',
                            };
                        ?>
                        <tr data-type="<?= htmlspecialchars($type) ?>" style="animation-delay:<?= ($no * 25) ?>ms">
                            <td class="row-num"><?= $no ?></td>
                            <td>
                                <div class="cell-name">
                                    <div class="initials" style="background:<?= $avatarBg ?>"><?= $initials ?></div>
                                    <span class="name-text"><?= htmlspecialchars($r['nama_mahasiswa']) ?></span>
                                </div>
                            </td>
                            <td><strong><?= htmlspecialchars($r['nim']) ?></strong></td>
                            <td><?= (int)$r['semester'] ?></td>
                            <td><span class="type-badge <?= $type ?>"><?= $typeLabel ?></span></td>
                            <td><?= $r['golongan_ukt'] !== null ? (int)$r['golongan_ukt'] : '<span class="null-val">N/A</span>' ?></td>
                            <td><?= $r['nama_wali'] !== null ? htmlspecialchars($r['nama_wali']) : '<span class="null-val">N/A</span>' ?></td>
                            <td><?= $r['nomor_kip_kuliah'] !== null ? htmlspecialchars($r['nomor_kip_kuliah']) : '<span class="null-val">N/A</span>' ?></td>
                            <td><?= $r['dana_saku_subsidi'] !== null ? 'Rp ' . number_format($r['dana_saku_subsidi'], 0, ',', '.') : '<span class="null-val">N/A</span>' ?></td>
                            <td><?= $r['nama_instansi_beasiswa'] !== null ? htmlspecialchars($r['nama_instansi_beasiswa']) : '<span class="null-val">N/A</span>' ?></td>
                            <td><?= $r['minimal_ipk_syarat'] !== null ? number_format($r['minimal_ipk_syarat'], 2) : '<span class="null-val">N/A</span>' ?></td>
                            <td class="cell-tagihan <?= $tagihan == 0 ? 'free' : '' ?>">
                                <?= $tagihan == 0 ? 'GRATIS' : 'Rp ' . $tagihanFmt ?>
                            </td>
                        </tr>
                        <?php $no++; endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- No results message -->
            <div class="no-results" id="noResults">
                <div class="nr-icon">🔍</div>
                <p>Tidak ada data yang cocok dengan pencarian Anda.</p>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="page-footer">
            <p>UAS Pemrograman Berorientasi Objek — TRPL-1A &copy; <?= date('Y') ?></p>
            <p style="margin-top:0.2rem;">Dibuat oleh: <strong>Almas Salsabila Fidiarti</strong></p>
        </footer>
    </div>
</main>

<!-- ===== JAVASCRIPT ===== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('overlay');
    const burger    = document.getElementById('burgerBtn');
    const navItems  = document.querySelectorAll('.nav-item');
    const tableBody = document.getElementById('tableBody');
    const rows      = tableBody.querySelectorAll('tr');
    const rowCount  = document.getElementById('rowCount');
    const noResults = document.getElementById('noResults');
    const searchIn  = document.getElementById('searchInput');
    const tbTitle   = document.getElementById('tb-title');
    const tbIcon    = document.getElementById('tb-icon');
    const tbCrumb   = document.getElementById('tb-crumb');

    let currentFilter = 'all';

    const meta = {
        all:        { title: 'Semua Mahasiswa',     icon: '📊', crumb: 'Dashboard' },
        mandiri:    { title: 'Mahasiswa Mandiri',   icon: '🎓', crumb: 'Mandiri' },
        bidikmisi:  { title: 'Mahasiswa Bidikmisi', icon: '📋', crumb: 'Bidikmisi' },
        prestasi:   { title: 'Mahasiswa Prestasi',  icon: '🏆', crumb: 'Prestasi' },
    };

    // --- Sidebar mobile toggle ---
    function openSidebar()  { sidebar.classList.add('open'); overlay.classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; }
    burger.addEventListener('click', openSidebar);
    overlay.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

    // --- Filter & render ---
    function applyFilters() {
        const search = searchIn.value.trim().toLowerCase();
        let visible = 0;
        let num = 1;

        rows.forEach(row => {
            const type = row.dataset.type;
            const text = row.textContent.toLowerCase();

            const matchFilter = (currentFilter === 'all' || type === currentFilter);
            const matchSearch = (!search || text.includes(search));

            if (matchFilter && matchSearch) {
                row.style.display = '';
                // Update row number
                row.querySelector('.row-num').textContent = num++;
                // Re-trigger animation
                row.style.animation = 'none';
                row.offsetHeight;
                row.style.animation = '';
                row.style.animationDelay = (visible * 20) + 'ms';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        rowCount.textContent = visible + ' data';
        noResults.classList.toggle('show', visible === 0);
    }

    // --- Nav click ---
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            currentFilter = item.dataset.filter;

            navItems.forEach(n => { n.classList.remove('active'); n.setAttribute('aria-current', 'false'); });
            item.classList.add('active');
            item.setAttribute('aria-current', 'page');

            const m = meta[currentFilter];
            if (m) {
                tbTitle.textContent = m.title;
                tbIcon.textContent  = m.icon;
                tbCrumb.textContent = m.crumb;
            }

            applyFilters();
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    // --- Search ---
    searchIn.addEventListener('input', applyFilters);
});
</script>

</body>
</html>
