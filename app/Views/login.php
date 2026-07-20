<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1>Se connecter</h1>
            <form id="loginForm" action="<?= base_url('login/check') ?>" method="POST">
                <label for="numero" class="form-label">Numéro de téléphone</label>
                <input type="text" id="numero" name="numero" class="form-control" required>
                <div id="numeroError" class="text-danger"></div>

                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>

                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" class="form-control" required>

                <div class="login-actions">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                    <a href="<?= base_url('/operateurs') ?>" class="small-link">Voir les Opérateurs</a>
                </div>
            </form>
        </div>
    </div>
        <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const numeroInput = document.getElementById('numero');
            const numeroError = document.getElementById('numeroError');
            const numero = numeroInput.value.trim();

            const prefixes = ['032', '033', '034', '038'];

            let isValid = false;
            for (let prefix of prefixes) {
                if (numero.startsWith(prefix) && numero.length === 10) {
                    isValid = true;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault();
                numeroError.textContent = "Numéro invalide. Exemple : 0321234567";
            } else {
                numeroError.textContent = "";
            }
        });
        </script>

</body>
</html>