<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Frais</title>
</head>
<body>

<h2>Modifier le Frais du palier</h2>

<?php if (isset($validation)): ?>
    <div style="color:red;"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<?= form_open('frais/mettreAJour/' . $frais['id']) ?>
    
    <p>
        <label>Type d'opération :</label>
        <input type="text" value="<?= esc($type_operation) ?>" disabled> 
    </p>

    <p>
        <label for="montant_min">Montant Minimum :</label>
        <input type="number" step="0.01" name="montant_min" id="montant_min" value="<?= old('montant_min', $frais['montant_min']) ?>">
    </p>

    <p>
        <label for="montant_max">Montant Maximum :</label>
        <input type="number" step="0.01" name="montant_max" id="montant_max" value="<?= old('montant_max', $frais['montant_max']) ?>">
    </p>

    <p>
        <label for="montant_frais">Montant du Frais :</label>
        <input type="number" step="0.01" name="montant_frais" id="montant_frais" value="<?= old('montant_frais', $frais['montant_frais']) ?>">
    </p>

    <button type="submit">Enregistrer les modifications</button>
    <a href="<?= site_url('operateurs') ?>">Annuler</a>

<?= form_close() ?>

</body>
</html>