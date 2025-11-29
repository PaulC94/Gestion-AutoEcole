<?php
// index.php
session_start();
require_once 'config.php';

$page = $_GET['page'] ?? 'home';
$validPages = ['home', 'eleves', 'moniteurs', 'vehicules'];

if (!in_array($page, $validPages, true)) {
    $page = 'home';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Auto-école Manager</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f7fb;
            color: #222;
        }
        header {
            background: #1f2937;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header h1 {
            margin: 0;
            font-size: 22px;
        }
        nav a {
            color: #e5e7eb;
            margin-left: 15px;
            text-decoration: none;
            font-size: 15px;
            padding: 6px 10px;
            border-radius: 4px;
        }
        nav a:hover, nav a.active {
            background: #374151;
        }
        .container {
            max-width: 1100px;
            margin: 25px auto 40px;
            padding: 0 20px;
        }
        h2 {
            margin-top: 0;
            color: #111827;
        }
        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px 22px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            margin-bottom: 25px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1 1 180px;
            display: flex;
            flex-direction: column;
            font-size: 14px;
        }
        label {
            margin-bottom: 4px;
            color: #374151;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            padding: 7px 8px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
        }
        .btn {
            border: none;
            border-radius: 4px;
            padding: 7px 14px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }
        .btn-danger {
            background: #ef4444;
            color: #fff;
        }
        .btn + .btn {
            margin-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }
        th, td {
            padding: 8px 9px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) td {
            background: #f9fafb;
        }
        tr:hover td {
            background: #eef2ff;
        }
        .text-right {
            text-align: right;
        }
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 12px;
        }
        footer {
            text-align: center;
            padding: 15px 0 20px;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<header>
    <h1>Site de gestion - Auto-école</h1>
    <nav>
        <a href="index.php?page=home"      class="<?= $page === 'home' ? 'active' : '' ?>">Accueil</a>
        <a href="index.php?page=eleves"    class="<?= $page === 'eleves' ? 'active' : '' ?>">Élèves</a>
        <a href="index.php?page=moniteurs" class="<?= $page === 'moniteurs' ? 'active' : '' ?>">Moniteurs</a>
        <a href="index.php?page=vehicules" class="<?= $page === 'vehicules' ? 'active' : '' ?>">Véhicules</a>
    </nav>
</header>

<div class="container">
    <?php
    switch ($page) {
        case 'eleves':
            require 'pages/eleves.php';
            break;
        case 'moniteurs':
            require 'pages/moniteurs.php';
            break;
        case 'vehicules':
            require 'pages/vehicules.php';
            break;
        default:
            ?>
            <div class="card">
                <h2>Bienvenue sur le gestionnaire d’auto-école</h2>
                <p>
                    Utilisez le menu en haut pour gérer :
                </p>
                <ul>
                    <li><strong>Les élèves</strong> : inscription, liste, recherche.</li>
                    <li><strong>Les moniteurs</strong> : suivi des formateurs.</li>
                    <li><strong>Les véhicules</strong> : voitures et motos utilisées.</li>
                </ul>
                <span class="badge">Version simple - CRUD</span>
            </div>
            <?php
            break;
    }
    ?>
</div>

<footer>
    &copy; <?= date('Y') ?> Auto-école Manager
</footer>

</body>
</html>