<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="#">MobileMoney</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="<?= base_url('logout') ?>">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">

    <!-- En-tête Profil & Solde -->
    <div class="card p-4 mb-4 border-0 shadow-sm bg-white rounded">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="h3 mb-1">Bienvenue, <span class="text-primary fw-semibold"><?= esc($user['email']) ?></span></h1>
                <p class="text-muted mb-0"><strong>Numéro de compte :</strong> <code><?= esc($user['numero']) ?></code></p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <span class="text-muted d-block small uppercase tracking-wider">Solde Actuel</span>
                <span class="h2 fw-bold text-success"><?= esc($solde) ?> <small class="fs-6 text-muted">Ar</small></span>
            </div>
        </div>
    </div>

    <!-- Messages Flash de Notifications -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white fw-bold">Faire un dépôt</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <form action="<?= base_url('transaction/depot') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Montant du dépôt</label>
                            <div class="input-group">
                                <input type="number" name="montant" class="form-control" placeholder="0.00" required>
                                <span class="input-group-text">Ar</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Déposer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger text-white fw-bold">Faire un retrait</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <form action="<?= base_url('transaction/retrait') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Montant du retrait</label>
                            <div class="input-group">
                                <input type="number" name="montant" class="form-control" placeholder="0.00" required>
                                <span class="input-group-text">Ar</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Retirer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transfert -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-warning text-dark fw-bold">Faire un transfert</div>
                <div class="card-body">
                    <form action="<?= base_url('transaction/transfert') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Numéro du destinataire</label>
                            <input type="text" name="numero_destinataire" class="form-control" placeholder="Ex: 0341234567" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant du transfert</label>
                            <div class="input-group">
                                <input type="number" name="montant" class="form-control" placeholder="0.00" required>
                                <span class="input-group-text">Ar</span>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="inclure_frais_retrait" value="1" class="form-check-input" id="checkRetrait">
                            <label class="form-check-label small" for="checkRetrait">Prendre en charge les frais de retrait</label>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 text-dark fw-semibold">Transférer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Historique des transactions -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">Historique des transactions</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Type / Statut</th>
                            <th>Destinataire</th>
                            <th>Montant</th>
                            <th>Frais</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td class="ps-3"><strong><?= esc($t['id_transaction']) ?></strong></td>
                            <td>
                                <span class="badge <?= $t['statut'] == 'Success' || $t['statut'] == 'Dépôt' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?> px-2 py-1">
                                    <?= esc($t['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (! empty($t['receiver_numero']) && $t['id_type_operation'] == 3): ?>
                                    <code><?= esc($t['receiver_numero']) ?></code>
                                    <?php if (! empty($t['receiver_email'])): ?>
                                        <span class="text-muted small d-block">&lt;<?= esc($t['receiver_email']) ?>&gt;</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= esc($t['montant']) ?> Ar</td>
                            <td class="text-danger"><?= esc($t['frais']) ?> Ar</td>
                            <td class="text-muted small"><?= esc($t['date_transaction']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>