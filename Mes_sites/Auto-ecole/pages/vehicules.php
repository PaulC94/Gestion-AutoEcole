<?php
// pages/vehicules.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $immat = trim($_POST['immatriculation'] ?? '');
    $modele = trim($_POST['modele'] ?? '');
    $annee  = (int) ($_POST['annee'] ?? 0);
    $cat    = trim($_POST['categorie'] ?? '');

    if ($immat !== '' && $modele !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO vehicules (immatriculation, modele, annee, categorie)
            VALUES (:immat, :modele, :annee, :cat)
        ");
        $stmt->execute([
            ':immat' => $immat,
            ':modele'=> $modele,
            ':annee' => $annee ?: null,
            ':cat'   => $cat,
        ]);
    }
    header('Location: index.php?page=vehicules');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM vehicules WHERE id = :id")->execute([':id' => $id]);
    header('Location: index.php?page=vehicules');
    exit;
}

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM vehicules
        WHERE immatriculation LIKE :q OR modele LIKE :q OR categorie LIKE :q
        ORDER BY modele
    ");
    $stmt->execute([':q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM vehicules ORDER BY modele");
}
$vehicules = $stmt->fetchAll();
?>

<div class="card">
    <h2>Gestion des véhicules</h2>

    <h3>Ajout d’un véhicule</h3>
    <form method="post">
        <input type="hidden" name="action" value="add">

        <div class="form-row">
            <div class="form-group">
                <label>Immatriculation</label>
                <input type="text" name="immatriculation" required>
            </div>
            <div class="form-group">
                <label>Modèle</label>
                <input type="text" name="modele" required>
            </div>
            <div class="form-group">
                <label>Année</label>
                <input type="number" name="annee" min="1980" max="2100">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Catégorie</label>
                <input type="text" name="categorie" placeholder="Citadine, moto, SUV…">
            </div>
        </div>

        <button type="reset" class="btn btn-secondary">Annuler</button>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>

<div class="card">
    <h3>Liste des véhicules</h3>

    <form method="get" class="filter-bar">
        <input type="hidden" name="page" value="vehicules">
        <label for="q">Filtrer par :</label>
        <input type="text" id="q" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES) ?>">
        <button type="submit" class="btn btn-secondary">Filtrer</button>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Immatriculation</th>
            <th>Modèle</th>
            <th>Année</th>
            <th>Catégorie</th>
            <th class="text-right">Opérations</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($vehicules) === 0): ?>
            <tr><td colspan="6">Aucun véhicule pour le moment.</td></tr>
        <?php else: ?>
            <?php foreach ($vehicules as $v): ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= htmlspecialchars($v['immatriculation']) ?></td>
                    <td><?= htmlspecialchars($v['modele']) ?></td>
                    <td><?= htmlspecialchars($v['annee']) ?></td>
                    <td><?= htmlspecialchars($v['categorie']) ?></td>
                    <td class="text-right">
                        <a class="btn btn-danger"
                           href="index.php?page=vehicules&delete=<?= $v['id'] ?>"
                           onclick="return confirm('Supprimer ce véhicule ?');">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>