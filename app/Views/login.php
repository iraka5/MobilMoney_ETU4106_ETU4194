<?php
$session = session();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobilMoney - Connexion</title>
</head>
<body>
    <h1>💰 MobilMoney - Connexion</h1>

    <?php if ($session->has('success')): ?>
        <p style="color: green;"><?= $session->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if ($session->has('error')): ?>
        <p style="color: red;"><?= $session->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('auth/login') ?>">
        <?= csrf_field() ?>

        <label for="numero">Numéro de Téléphone:</label>
        <input type="text" id="numero" name="numero" placeholder="Ex: 0321234567" required>
        <small>Format: 0XX XXX XXX ou +261XX XXX XXX</small>
        <br><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
        <small>Min 8 caractères (majuscule, minuscule, chiffre)</small>
        <br><br>

        <button type="submit">Se Connecter</button>
    </form>

    <p><small>Nouveau? Le compte sera créé automatiquement au premier login.</small></p>
</body>
</html>
