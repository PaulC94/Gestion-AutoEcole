<?php
// pages/eleves.php

// Ajout d’un élève
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $nom    = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $tel    = trim($_POST['telephone'] ?? '');
    $adr    = trim($_POST['adresse'] ?? '');
    $type   = trim($_POST['type_permis'] ?? '');

    if ($nom !== '' && $prenom !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO eleves (nom, prenom, email, telephone, adresse, type_permis)
            VALUES (:nom, :prenom, :email, :tel, :adr, :type)
        ");
        $stmt->execute([
            ':nom'   => $nom,
            ':prenom'=> $prenom,
            ':email' => $email,
            ':tel'   => $tel,
            ':adr'   => $adr,
            ':type'  => $type,
        ]);
    }
    header('Location: index.php?page=eleves');
    exit;
}

// Suppression
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM eleves WHERE id = :id")->execute([':id' => $id]);
    header('Location: index.php?page=eleves');
    exit;
}

// Filtre
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM eleves
        WHERE nom LIKE :q OR prenom LIKE :q OR email LIKE :q
        ORDER BY nom, prenom
    ");
    $stmt->execute([':q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM eleves ORDER BY nom, prenom");
}
$eleves = $stmt->fetchAll();
?>

<div class="card">
    <h2>Gestion des élèves</h2>

    <h3>Ajout d’un élève</h3>
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
                <label>Type de permis</label>
                <input type="text" name="type_permis" placeholder="B, A, AAC…">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="telephone">
            </div>
            <div class="form-group">
                <label>Adresse</label>
                <input type="text" name="adresse">
            </div>
        </div>

        <button type="reset" class="btn btn-secondary">Annuler</button>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>

<div class="card">
    <h3>Liste des élèves</h3>

    <form method="get" class="filter-bar">
        <input type="hidden" name="page" value="eleves">
        <label for="q">Filtrer par :</label>
        <input type="text" id="q" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES) ?>">
        <button type="submit" class="btn btn-secondary">Filtrer</button>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Type permis</th>
            <th>Date inscription</th>
            <th class="text-right">Opérations</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($eleves) === 0): ?>
            <tr><td colspan="8">Aucun élève pour le moment.</td></tr>
        <?php else: ?>
            <?php foreach ($eleves as $e): ?>
                <tr>
                    <td><?= $e['id'] ?></td>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['email']) ?></td>
                    <td><?= htmlspecialchars($e['telephone']) ?></td>
                    <td><?= htmlspecialchars($e['type_permis']) ?></td>
                    <td><?= htmlspecialchars($e['date_inscription']) ?></td>
                    <td class="text-right">
                        <a class="btn btn-danger"
                           href="index.php?page=eleves&delete=<?= $e['id'] ?>"
                           onclick="return confirm('Supprimer cet élève ?');">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>