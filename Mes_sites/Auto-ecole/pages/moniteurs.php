<?php
// pages/moniteurs.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $nom    = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $tel    = trim($_POST['telephone'] ?? '');
    $spec   = trim($_POST['specialite'] ?? '');
    $date   = $_POST['date_embauche'] ?: null;

    if ($nom !== '' && $prenom !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO moniteurs (nom, prenom, email, telephone, specialite, date_embauche)
            VALUES (:nom, :prenom, :email, :tel, :spec, :date_embauche)
        ");
        $stmt->execute([
            ':nom'           => $nom,
            ':prenom'        => $prenom,
            ':email'         => $email,
            ':tel'           => $tel,
            ':spec'          => $spec,
            ':date_embauche' => $date,
        ]);
    }
    header('Location: index.php?page=moniteurs');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM moniteurs WHERE id = :id")->execute([':id' => $id]);
    header('Location: index.php?page=moniteurs');
    exit;
}

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM moniteurs
        WHERE nom LIKE :q OR prenom LIKE :q OR email LIKE :q
        ORDER BY nom, prenom
    ");
    $stmt->execute([':q' => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM moniteurs ORDER BY nom, prenom");
}
$moniteurs = $stmt->fetchAll();
?>

<div class="card">
    <h2>Gestion des moniteurs</h2>

    <h3>Ajout d’un moniteur</h3>
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
                <label>Spécialité</label>
                <input type="text" name="specialite" placeholder="Permis B, A, remorque…">
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
                <label>Date d’embauche</label>
                <input type="date" name="date_embauche">
            </div>
        </div>

        <button type="reset" class="btn btn-secondary">Annuler</button>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>

<div class="card">
    <h3>Liste des moniteurs</h3>

    <form method="get" class="filter-bar">
        <input type="hidden" name="page" value="moniteurs">
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
            <th>Spécialité</th>
            <th>Date d’embauche</th>
            <th class="text-right">Opérations</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($moniteurs) === 0): ?>
            <tr><td colspan="8">Aucun moniteur pour le moment.</td></tr>
        <?php else: ?>
            <?php foreach ($moniteurs as $m): ?>
                <tr>
                    <td><?= $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['nom']) ?></td>
                    <td><?= htmlspecialchars($m['prenom']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td><?= htmlspecialchars($m['telephone']) ?></td>
                    <td><?= htmlspecialchars($m['specialite']) ?></td>
                    <td><?= htmlspecialchars($m['date_embauche']) ?></td>
                    <td class="text-right">
                        <a class="btn btn-danger"
                           href="index.php?page=moniteurs&delete=<?= $m['id'] ?>"
                           onclick="return confirm('Supprimer ce moniteur ?');">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>