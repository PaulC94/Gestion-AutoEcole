<?php
// =========================
// TRAITEMENT : AJOUT ÉLÈVE
// =========================

// CRÉER AUTOMATIQUEMENT UN MONITEUR DIRECTEUR S'IL N'Y EN A PAS
$check = $pdo->query("SELECT COUNT(*) FROM moniteurs")->fetchColumn();

if ($check == 0) {
    $pdo->prepare("
        INSERT INTO moniteurs (nom, prenom, email, telephone, specialite, date_embauche)
        VALUES ('Directeur', 'Auto-école', 'directeur@autoecole.com', '0000000000', 'Permis B', CURDATE())
    ")->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {

    $nom    = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email  = trim($_POST['email']);
    $tel    = trim($_POST['telephone']);
    $adr    = trim($_POST['adresse']);
    $type   = trim($_POST['type_permis']);

    if ($nom !== '' && $prenom !== '') {

        // 1) Insert élève
        $stmt = $pdo->prepare("
            INSERT INTO eleves (nom, prenom, email, telephone, adresse, type_permis)
            VALUES (:nom, :prenom, :email, :tel, :adr, :type)
        ");
        $stmt->execute([
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':tel'    => $tel,
            ':adr'    => $adr,
            ':type'   => $type,
        ]);

        $eleveId = $pdo->lastInsertId();

        // 2) Récupérer les moniteurs
        $moniteurs = $pdo->query("SELECT id FROM moniteurs")->fetchAll(PDO::FETCH_COLUMN);

        // 3) Ajouter 20 heures automatiquement
        if (!empty($moniteurs)) {

            $stmtLecon = $pdo->prepare("
                INSERT INTO lecons (eleve_id, moniteur_id, date_heure, duree_min)
                VALUES (:eleve, :moniteur, :dt, :duree)
            ");

            $base = new DateTime('tomorrow 09:00');
            $duree = 60;

            for ($i = 0; $i < 20; $i++) {
                $date = clone $base;

                $offsetDay = intdiv($i, 2);
                $hourSlot  = $i % 2; // 0 = 9h, 1 = 10h

                $date->modify("+$offsetDay day");
                if ($hourSlot === 1) $date->modify("+1 hour");

                $moniteurId = $moniteurs[$i % count($moniteurs)];

                $stmtLecon->execute([
                    ':eleve'    => $eleveId,
                    ':moniteur' => $moniteurId,
                    ':dt'       => $date->format('Y-m-d H:i:s'),
                    ':duree'    => $duree,
                ]);
            }

            $_SESSION['flash'] = "Élève ajouté et 20h de conduite planifiées.";
        } else {
            $_SESSION['flash'] = "Élève ajouté, mais aucun moniteur n'est enregistré.";
        }
    } else {
        $_SESSION['flash'] = "Veuillez remplir au minimum nom + prénom.";
    }

    header("Location: index.php?page=eleves");
    exit;
}

// =========================
// SUPPRESSION ÉLÈVE
// =========================

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM eleves WHERE id = :id")->execute([':id' => $id]);
    $_SESSION['flash'] = "Élève supprimé.";
    header("Location: index.php?page=eleves");
    exit;
}