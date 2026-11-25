<?php
require_once __DIR__ . '/../middleware/auth.php';
requireLogin();
require_once __DIR__ . '/csrf.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'Cleaning Stock' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-bg: #1a1f2e;
            --sidebar-hover: #2d3448;
            --sidebar-active: #0d6efd;
            --topbar-height: 56px;
        }

        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .25s ease;
        }

        #sidebar .brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        #sidebar .brand span {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .3px;
        }

        #sidebar .nav-section {
            padding: 18px 12px 4px;
            font-size: .68rem;
            color: rgba(255,255,255,.35);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        #sidebar .nav-link {
            color: rgba(255,255,255,.65);
            border-radius: 8px;
            padding: 9px 14px;
            margin: 2px 8px;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all .15s;
        }

        #sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        #sidebar .nav-link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        /* ── Main area ── */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        #topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 900;
            gap: 12px;
        }

        #topbar .page-title {
            font-weight: 600;
            font-size: .95rem;
            color: #212529;
            flex: 1;
        }

        #topbar .user-badge {
            background: #f0f2f5;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: .8rem;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #topbar .role-badge {
            font-size: .68rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* ── Content ── */
        #content {
            padding: 28px 28px;
            flex: 1;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>