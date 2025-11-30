<?php
session_start();
require_once 'config.php';

// --- ROUTAGE ---
$page = $_GET['page'] ?? 'home';
$validPages = ['home', 'eleves', 'moniteurs', 'vehicules'];

if (!in_array($page, $validPages)) {
    $page = 'home';
}

// Charger actions avant HTML
if ($page === 'eleves') {
    require_once 'pages/eleves_actions.php';
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
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI";
            background: #f5f7fb;
            color: #222;
        }
        header {
            background: #1f2937;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            text-decoration: none;
            color: #e5e7eb;
            margin-left: 15px;
            padding: 6px 10px;
            border-radius: 4px;
        }
        nav a.active, nav a:hover {
            background: #374151;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            padding: 22px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        table {
            width:100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th,td {
            padding: 8px;
            border-bottom:1px solid #ddd;
        }
        footer {
            text-align:center;
            padding: 20px 0;
            color:#6b7280;
            margin-top:30px;
        }
    </style>
</head>

<body>

<header>
    <h1>Site de gestion - Auto-école</h1>
    <nav>
        <a href="index.php?page=home" class="<?= $page==='home'?'active':'' ?>">Accueil</a>
        <a href="index.php?page=eleves" class="<?= $page==='eleves'?'active':'' ?>">Élèves</a>
        <a href="index.php?page=moniteurs" class="<?= $page==='moniteurs'?'active':'' ?>">Moniteurs</a>
        <a href="index.php?page=vehicules" class="<?= $page==='vehicules'?'active':'' ?>">Véhicules</a>
    </nav>
</header>

<div class="container">

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="card" style="background:#ecfdf3;border-left:4px solid #16a34a;">
            <?= htmlspecialchars($_SESSION['flash']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>


    <?php
    // -------------------------
    // PAGE : ÉLÈVES
    // -------------------------
    if ($page === 'eleves') {
        require 'pages/eleves.php';
    }

// -------------------------
// PAGE : MONITEURS
// -------------------------
    elseif ($page === 'moniteurs') {
        require 'pages/moniteurs.php';
    }

// -------------------------
// PAGE : VÉHICULES
// -------------------------
    elseif ($page === 'vehicules') {
        require 'pages/vehicules.php';
    }

// -------------------------
// PAGE : ACCUEIL (dashboard)
// -------------------------
    else {

        // Récupération des stats
        $nbEleves = $pdo->query("SELECT COUNT(*) FROM eleves")->fetchColumn();
        $nbMoniteurs = $pdo->query("SELECT COUNT(*) FROM moniteurs")->fetchColumn();
        $nbVehicules = $pdo->query("SELECT COUNT(*) FROM vehicules")->fetchColumn();
        $totalHeures = $pdo->query("SELECT COALESCE(SUM(duree_min)/60,0) FROM lecons")->fetchColumn();

        $topEleves = $pdo->query("
        SELECT e.prenom, e.nom, COALESCE(SUM(l.duree_min)/60,0) AS heures
        FROM eleves e
        LEFT JOIN lecons l ON e.id = l.eleve_id
        GROUP BY e.id
        ORDER BY heures DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="card">
            <h2>Tableau de bord</h2>

            <div style="display:flex; gap:20px; flex-wrap:wrap;">

                <div class="card" style="flex:1;">
                    <h3>Élèves</h3>
                    <p><strong><?= $nbEleves ?></strong></p>
                </div>

                <div class="card" style="flex:1;">
                    <h3>Moniteurs</h3>
                    <p><strong><?= $nbMoniteurs ?></strong></p>
                </div>

                <div class="card" style="flex:1;">
                    <h3>Véhicules</h3>
                    <p><strong><?= $nbVehicules ?></strong></p>
                </div>

                <div class="card" style="flex:1;">
                    <h3>Heures planifiées</h3>
                    <p><strong><?= $totalHeures ?> h</strong></p>
                </div>
            </div>

            <h3 style="margin-top:30px;">Top 5 des élèves les plus avancés</h3>
            <table>
                <tr>
                    <th>Élève</th>
                    <th>Heures réalisées</th>
                </tr>

                <?php foreach ($topEleves as $el): ?>
                    <tr>
                        <td><?= htmlspecialchars($el['prenom'].' '.$el['nom']) ?></td>
                        <td><?= $el['heures'] ?> h</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <?php
    } // <-- FIN DU SWITCH
    ?>

</div>

<footer>
    &copy; <?= date('Y') ?> Auto-école Manager
</footer>

</body>
</html>