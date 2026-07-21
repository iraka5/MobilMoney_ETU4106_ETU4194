<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des opérateurs</title>
    <!-- Bootstrap CDN CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Barre de navigation rapide -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Dashboard Administration</span>
              <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('/operateurs') ?>">Voir les Opérateur</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="<?= base_url('logout') ?>">Déconnexion</a></li>
      </ul>
    </div>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- SECTION 1 : OPERATEURS & FRAIS (Côte à côte sur grand écran) -->
        <div class="row g-4 mb-4">
            <!-- Liste des opérateurs -->
            <div class="col-12 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-primary">Liste des opérateurs</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Libellé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($operateurs as $op): ?>
                                    <tr>
                                        <td><strong><?= $op['id'] ?></strong></td>
                                        <td><span class="badge bg-secondary px-2 py-2"><?= esc($op['libelle']) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration des frais -->
            <div class="col-12 col-lg-8">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-primary">Configuration des Barèmes de Frais</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Opération</th>
                                        <th>Montant Min</th>
                                        <th>Montant Max</th>
                                        <th>Frais</th>
                                        <th class="text-center">Action</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($frais as $ligne): ?>
                                    <tr>
                                        <td><?= esc($ligne['type_operation']) ?></td>
                                        <td><?= esc($ligne['montant_min']) ?> Ar</td>
                                        <td><?= esc($ligne['montant_max']) ?> Ar</td>
                                        <td class="text-danger fw-bold"><?= esc($ligne['montant_frais']) ?> Ar</td>
                                        <td class="text-center">
                                            <a href="<?= site_url('frais/modifier/' . $ligne['id_bareme']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2 : LES GAINS -->
        <div class="row g-4 mb-4">
            <!-- Gain via frais -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-success">Situation gain via les frais</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Opération</th>
                                        <th>Transactions</th>
                                        <th>Total des frais</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (! empty($gain_frais)): ?>
                                        <?php foreach ($gain_frais as $gain): ?>
                                        <tr>
                                            <td><?= esc($gain['type_operation']) ?></td>
                                            <td><span class="badge bg-info text-dark"><?= esc($gain['nb_transactions']) ?></span></td>
                                            <td class="fw-bold text-success"><?= esc($gain['total_frais']) ?> Ar</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Aucun frais enregistré pour le moment.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gain via commissions -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-success">Situation gain via commissions</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libellé</th>
                                        <th>Transactions</th>
                                        <th>Gain net</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (! empty($gain_autres)): ?>
                                        <?php foreach ($gain_autres as $gain): ?>
                                        <tr>
                                            <td><?= esc($gain['libelle']) ?></td>
                                            <td><span class="badge bg-info text-dark"><?= esc($gain['nb_transactions']) ?></span></td>
                                            <td class="fw-bold text-success"><?= esc($gain['gain_net']) ?> Ar</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Aucune donnée enregistrée.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3 : COMMISSIONS & INTER-OPERATEURS -->
        <div class="row g-4 mb-4">
            <!-- Commissions configurations -->
            <div class="col-12 col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-warning text-dark">Commissions autres opérateurs</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Opération</th>
                                        <th>Libellé</th>
                                        <th>Commission</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (! empty($commissions)): ?>
                                        <?php foreach ($commissions as $c): ?>
                                        <tr>
                                            <td><span class="text-muted">Transfert</span></td>
                                            <td><?= esc($c['libelle']) ?></td>
                                            <td class="fw-bold text-primary"><?= esc($c['pourcentage']) ?> %</td>
                                            <td class="text-center">
                                                <a href="<?= site_url('commission/modifier/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucune commission configurée.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flux financiers vers opérateurs -->
            <div class="col-12 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-secondary">Montants à envoyer à chaque opérateur</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Opérateur</th>
                                        <th>Transactions</th>
                                        <th>Total Transféré</th>
                                        <th>À envoyer (selon %)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (! empty($gain_operateurs)): ?>
                                        <?php foreach ($gain_operateurs as $g): ?>
                                        <tr>
                                            <td><strong><?= esc($g['operateur']) ?></strong></td>
                                            <td><span class="badge bg-secondary"><?= esc($g['nb_transactions']) ?></span></td>
                                            <td><?= esc($g['montant_total']) ?> Ar</td>
                                            <td class="fw-bold text-primary"><?= esc($g['montant_a_envoyer']) ?> Ar</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucun transfert vers d'autres opérateurs.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4 : UTILISATEURS (Pleine largeur tout en bas) -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5 card-title border-bottom pb-2 mb-3 text-dark">Liste complète des utilisateurs</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
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
                                            <td><code><?= esc($u['numero']) ?></code></td>
                                            <td><span class="badge bg-secondary"><?= esc($u['nom_operateur'] ?? 'Inconnu') ?></span></td>
                                            <td class="fw-bold text-success"><?= number_format($u['solde'], 2, '.', ' ') ?> Ar</td>
                                            <td class="text-muted small"><?= $u['created_at'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">Aucun utilisateur trouvé en base de données.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>