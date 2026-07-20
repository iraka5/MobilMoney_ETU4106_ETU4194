<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="loginForm" action="<?= base_url('login/check') ?>" method="POST">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de téléphone</label>
                <input type="text" id="numero" name="numero" class="form-control" required>
                <div id="numeroError" class="text-danger mt-1"></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>


        <a href="<?= base_url('/operateurs') ?>" class="btn btn-primary">
    <i class="bi bi-list-ul"></i> Voir les Opérateurs
</a>
        <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const numeroInput = document.getElementById('numero');
            const numeroError = document.getElementById('numeroError');
            const numero = numeroInput.value.trim();

            // Préfixes valides (exemple Madagascar : 032, 033, 034, 038)
            const prefixes = ['032', '033', '034', '038'];

            let isValid = false;
            for (let prefix of prefixes) {
                if (numero.startsWith(prefix) && numero.length === 10) {
                    isValid = true;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault(); // Bloque l’envoi du formulaire
                numeroError.textContent = "Numéro invalide. Exemple : 0321234567";
            } else {
                numeroError.textContent = "";
            }
        });
        </script>

</body>
</html>