<?php
// Script simple pour recalculer les soldes utilisateurs à partir des transactions.
// Usage: php recompute_solde.php

$dbPath = __DIR__ . '/writable/database/mobilemoney.db';
if (! file_exists($dbPath)) {
    echo "Base de données introuvable: $dbPath\n";
    exit(1);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les utilisateurs
    $users = $pdo->query('SELECT id FROM users')->fetchAll(PDO::FETCH_COLUMN);

    foreach ($users as $uid) {
        $stmt = $pdo->prepare(
            "SELECT
                COALESCE(SUM(
                    CASE
                        WHEN id_type_operation = 1 THEN montant - frais
                        WHEN id_type_operation = 2 THEN -(montant + frais)
                        WHEN id_type_operation = 3 AND id_sender = :uid THEN -(montant + frais)
                        WHEN id_type_operation = 3 AND id_receiver = :uid THEN montant
                        ELSE 0
                    END
                ), 0) AS balance
            FROM transactions
            WHERE id_sender = :uid OR id_receiver = :uid"
        );
        $stmt->execute([':uid' => $uid]);
        $balance = $stmt->fetchColumn();

        // Insérer ou mettre à jour la table solde_user
        $exists = $pdo->prepare('SELECT 1 FROM solde_user WHERE id_user = :uid');
        $exists->execute([':uid' => $uid]);
        if ($exists->fetch()) {
            $upd = $pdo->prepare('UPDATE solde_user SET solde = :bal, last_updated = CURRENT_TIMESTAMP WHERE id_user = :uid');
            $upd->execute([':bal' => $balance, ':uid' => $uid]);
        } else {
            $ins = $pdo->prepare('INSERT INTO solde_user (id_user, solde) VALUES (:uid, :bal)');
            $ins->execute([':uid' => $uid, ':bal' => $balance]);
        }

        echo "User $uid -> solde = $balance\n";
    }

    echo "Recalcul terminé.\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
