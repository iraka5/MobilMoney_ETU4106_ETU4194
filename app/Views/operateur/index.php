<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des opérateurs</title>
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
</body>
</html>