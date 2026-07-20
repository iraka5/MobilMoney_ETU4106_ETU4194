<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la commission</title>
    <!-- Bootstrap CDN CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Centre le formulaire verticalement et horizontalement -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                
                <!-- Carte principale -->
                <div class="card shadow border-0 p-4 bg-white rounded">
                    
                    <h2 class="h4 card-title border-bottom pb-2 mb-4 text-dark">
                        Modifier la commission
                    </h2>
                    
                    <div class="mb-3">
                        <span class="badge bg-secondary p-2 fs-6 mb-2">
                            <?= esc($commission['libelle']) ?>
                        </span>
                    </div>

                    <?= form_open('commission/mettreAJour/' . $commission['id'], ['class' => 'needs-validation']) ?>

                        <!-- Champ Opérateur (Désactivé) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Opérateur</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($commission['libelle']) ?>" disabled>
                        </div>

                        <!-- Champ Pourcentage -->
                        <div class="mb-4">
                            <label for="pourcentage" class="form-label fw-semibold">Commission (%)</label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       name="pourcentage" 
                                       id="pourcentage" 
                                       class="form-control" 
                                       value="<?= old('pourcentage', $commission['pourcentage']) ?>" 
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <!-- Actions : Boutons Enregistrer et Annuler -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-grow-1">
                                Enregistrer les modifications
                            </button>
                            <a href="<?= site_url('operateurs') ?>" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>

                    <?= form_close() ?>

                </div>
                
            </div>
        </div>
    </div>

    <!-- Script Bootstrap Bundle (inclus JS pour d'éventuels composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>