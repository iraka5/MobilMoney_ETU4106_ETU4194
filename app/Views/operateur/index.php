<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des opérateurs</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<h2>Liste des opérateurs</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Libellé</th>
    </tr>

    <?php foreach ($operateurs as $op): ?>

    <tr>
        <td><?= $op['id'] ?></td>
        <td><?= esc($op['libelle']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<table border="1">
    <thead>
        <tr>
            <th>Opération</th>
            <th>Montant Min</th>
            <th>Montant Max</th>
            <th>Frais</th>
            <th>Action</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($frais as $ligne): ?>
        <tr>
            <td><?= esc($ligne['type_operation']) ?></td>
            <td><?= esc($ligne['montant_min']) ?> Ar</td>
            <td><?= esc($ligne['montant_max']) ?> Ar</td>
            <td><?= esc($ligne['montant_frais']) ?> Ar</td>
            <td>
              <a href="<?= site_url('frais/modifier/' . $ligne['id_bareme']) ?>">Modifier</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Situation gain via les différents frais</h2>
<table border="1">
    <thead>
        <tr>
            <th>Opération</th>
            <th>Nombre de transactions</th>
            <th>Total des frais reçus</th>
        </tr>
    </thead>
    <tbody>
        <?php if (! empty($gain_frais)): ?>
            <?php foreach ($gain_frais as $gain): ?>
            <tr>
                <td><?= esc($gain['type_operation']) ?></td>
                <td><?= esc($gain['nb_transactions']) ?></td>
                <td><?= esc($gain['total_frais']) ?> Ar</td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Aucun frais enregistré pour le moment.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<h2>Situation gain via les commissions autres opérateurs</h2>
<table border="1">
    <thead>
        <tr>
            <th>Libellé</th>
            <th>Nombre de transactions</th>
            <th>Gain net</th>
        </tr>
    </thead>
    <tbody>
        <?php if (! empty($gain_autres)): ?>
            <?php foreach ($gain_autres as $gain): ?>
            <tr>
                <td><?= esc($gain['libelle']) ?></td>
                <td><?= esc($gain['nb_transactions']) ?></td>
                <td><?= esc($gain['gain_net']) ?> Ar</td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Aucune donnée enregistrée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<h2>Liste de tous les utilisateurs</h2>
<table border="1" style="width:100%; border-collapse: collapse; margin-bottom: 25px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>ID</th>
            <th>Email</th>
            <th>Numéro</th>
            <th>Opérateur</th>
            <th>Solde Actuel</th>
            <th>Date d'inscription</th>
        </tr>
    </thead>
    <tbody>
        <?php if (! empty($users)): ?>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= esc($u['numero']) ?></td>
                <td><?= esc($u['nom_operateur'] ?? 'Inconnu') ?></td>
                <td style="font-weight: bold;"><?= number_format($u['solde'], 2, '.', ' ') ?> Ar</td>
                <td><?= $u['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">Aucun utilisateur trouvé en base de données.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Commissions autres opérateurs</h2>
<table border="1">
    <thead>
        <tr>
            <th>Opération</th>
            <th>Libellé</th>
            <th>Commission</th>
        </tr>
    </thead>
    <tbody>
        <?php if (! empty($commissions)): ?>
            <?php foreach ($commissions as $c): ?>
            <tr>
                <td>Transfert</td> <!-- ou autre si tu ajoutes une colonne -->
                <td><?= esc($c['libelle']) ?></td>
                <td><?= esc($c['pourcentage']) ?> %</td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Aucune commission configurée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Situation des montants à envoyer à chaque opérateur</h2>
<table border="1">
    <thead>
        <tr>
            <th>Opérateur</th>
            <th>Nombre de transactions</th>
            <th>Montant total transféré</th>
            <th>Montant à envoyer (selon %)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (! empty($gain_operateurs)): ?>
            <?php foreach ($gain_operateurs as $g): ?>
            <tr>
                <td><?= esc($g['operateur']) ?></td>
                <td><?= esc($g['nb_transactions']) ?></td>
                <td><?= esc($g['montant_total']) ?> Ar</td>
                <td><?= esc($g['montant_a_envoyer']) ?> Ar</td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Aucun transfert vers autres opérateurs enregistré.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>