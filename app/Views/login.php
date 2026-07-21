<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                
                <!-- Carte Bootstrap -->
                <div class="card shadow p-4 bg-white rounded">
                    <h1 class="text-center h3 mb-4">Se connecter</h1>

                    <!-- Alerte d'erreur Bootstrap -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form id="loginForm" action="<?= base_url('login/check') ?>" method="POST" novalidate>
                        
                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="numero" class="form-label"><i class="bi bi-telephone-fill text-primary"></i> Numéro de téléphone</label>
                            <input type="text" id="numero" name="numero" class="form-control" placeholder="Ex: 0341234567" required>
                            <div id="numeroError" class="text-danger small mt-1"></div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-4">
                            <label for="mdp" class="form-label">Mot de passe</label>
                            <input type="password" id="mdp" name="mdp" class="form-control" required>
                        </div>

                        <!-- Actions (Bouton + Lien alignés proprement) -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                            <a href="<?= base_url('/operateurs') ?>" class="text-decoration-none small">Voir les Opérateurs</a>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Script Bootstrap (Optionnel ici mais recommandé si tu ajoutes des composants interactifs plus tard) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
            numeroInput.classList.add('is-invalid'); 
        } else {
            numeroError.textContent = "";
            numeroInput.classList.remove('is-invalid');
            numeroInput.classList.add('is-valid'); 
        }
    });
    </script>
</body>
</html>