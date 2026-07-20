<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MobileMoney</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-3">Bienvenue <?= esc($user['email']) ?></h1>
    <p><strong>Numéro :</strong> <?= esc($user['numero']) ?></p>
    <p><strong>Solde actuel :</strong> <?= esc($solde) ?> Ar</p>

    <!-- Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row mt-4">
        <!-- Dépôt -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Faire un dépôt</div>
                <div class="card-body">
                    <form action="<?= base_url('transaction/depot') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Montant du dépôt</label>
                            <input type="number" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Déposer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Retrait -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">Faire un retrait</div>
                <div class="card-body">
                    <form action="<?= base_url('transaction/retrait') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Montant du retrait</label>
                            <input type="number" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Retirer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- transfert -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">Faire un transfert</div>
                <div class="card-body">
                    <form action="<?= base_url('transaction/transfert') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Numéro du destinataire</label>
                            <input type="text" name="numero_destinataire" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant du transfert</label>
                            <input type="number" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Transférer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-primary text-white">Historique des transactions</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Frais</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['id_transaction']) ?></td>
                        <td><?= esc($t['statut']) ?></td>
                        <td><?= esc($t['montant']) ?></td>
                        <td><?= esc($t['frais']) ?></td>
                        <td><?= esc($t['date_transaction']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
