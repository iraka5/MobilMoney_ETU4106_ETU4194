<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Frais</title>
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
                        Modifier le Frais du palier
                    </h2>

                    <!-- Liste des erreurs Bootstrap -->
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger small" role="alert">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php endif; ?>

                    <?= form_open('frais/mettreAJour/' . $frais['id'], ['class' => 'needs-validation']) ?>

                        <!-- Type d'opération (Désactivé) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Type d'opération</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($type_operation) ?>" disabled>
                        </div>

                        <!-- Montant Minimum -->
                        <div class="mb-3">
                            <label for="montant_min" class="form-label fw-semibold">Montant Minimum</label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       name="montant_min" 
                                       id="montant_min" 
                                       class="form-control" 
                                       value="<?= old('montant_min', $frais['montant_min']) ?>" 
                                       required>
                                <span class="input-group-text">Ar</span>
                            </div>
                        </div>

                        <!-- Montant Maximum -->
                        <div class="mb-3">
                            <label for="montant_max" class="form-label fw-semibold">Montant Maximum</label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       name="montant_max" 
                                       id="montant_max" 
                                       class="form-control" 
                                       value="<?= old('montant_max', $frais['montant_max']) ?>" 
                                       required>
                                <span class="input-group-text">Ar</span>
                            </div>
                        </div>

                        <!-- Montant du Frais -->
                        <div class="mb-4">
                            <label for="montant_frais" class="form-label fw-semibold">Montant du Frais</label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       name="montant_frais" 
                                       id="montant_frais" 
                                       class="form-control" 
                                       value="<?= old('montant_frais', $frais['montant_frais']) ?>" 
                                       required>
                                <span class="input-group-text">Ar</span>
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

    <!-- Script Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>