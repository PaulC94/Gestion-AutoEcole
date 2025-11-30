<?php

// Recherche
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE nom LIKE :q OR prenom LIKE :q ORDER BY nom");
    $stmt->execute([':q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM eleves ORDER BY nom");
}

$eleves = $stmt->fetchAll();

// Heures planifiées
$hoursQuery = $pdo->query("SELECT eleve_id, SUM(duree_min) AS mins FROM lecons GROUP BY eleve_id");
$hours = [];

foreach ($hoursQuery->fetchAll() as $r) {
    $hours[$r['eleve_id']] = $r['mins'] / 60;
}
?>

<div class="card">
    <h2>Ajouter un élève</h2>

    <form method="post">
        <input type="hidden" name="action" value="add">

        <div class="form-row">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="telephone">
            </div>

            <div class="form-group">
                <label>Adresse</label>
                <input type="text" name="adresse">
            </div>

            <div class="form-group">
                <label>Type permis</label>
                <input type="text" name="type_permis">
            </div>
        </div>

        <button type="reset" class="btn btn-secondary">Annuler</button>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<div class="card">
    <h2>Liste des élèves</h2>

    <form method="get" class="filter-bar">
        <input type="hidden" name="page" value="eleves">
        <input type="text" name="q" placeholder="Rechercher…" value="<?= htmlspecialchars($q) ?>">
        <button class="btn btn-secondary">Filtrer</button>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Heures planifiées</th>
            <th class="text-right">Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($eleves as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['prenom']) ?></td>
                <td><?= htmlspecialchars($e['email']) ?></td>
                <td><?= $hours[$e['id']] ?? 0 ?> h</td>
                <td class="text-right">
                    <a href="index.php?page=eleves&delete=<?= $e['id'] ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Supprimer cet élève ?');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>