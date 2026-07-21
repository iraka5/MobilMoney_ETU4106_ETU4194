<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="#">MobileMoney</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('/operateurs') ?>">Voir les Opérateur</a></li>

        <li class="nav-item"><a class="nav-link text-danger" href="<?= base_url('logout') ?>">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">

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
<div class="card shadow-sm border-0 h-100 mb-4">
    <div class="card-header bg-dark text-white fw-bold">Envoi Multiple (Montant Divisé)</div>
    <div class="card-body">
        <form action="<?= base_url('transaction/transfertMultiple') ?>" method="POST" id="formMultiple">
            
            <!-- Zone d'ajout de numéro -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Ajouter des destinataires</label>
                <div class="input-group mb-2">
                    <input type="text" id="inputNumero" class="form-control" placeholder="Ex: 0341234567">
                    <button class="btn btn-outline-secondary" type="button" id="btnAjouterNumero">Ajouter</button>
                </div>
                <div id="multipleNumeroError" class="text-danger small mb-2"></div>
                
                <!-- Liste visuelle des numéros ajoutés -->
                <div id="listeBadgesNumeros" class="d-flex flex-wrap gap-2 my-2">
                    <!-- Les numéros ajoutés s'afficheront ici sous forme de badges -->
                </div>

                <!-- Conteneur secret pour les inputs envoyés au serveur -->
                <div id="inputsMasquesConteneur"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Montant global à diviser</label>
                <div class="input-group">
                    <input type="number" name="montant_global" class="form-control" placeholder="0.00" min="1" required>
                    <span class="input-group-text">Ar</span>
                </div>
                <div class="form-text text-muted">Ce montant sera divisé équitablement entre chaque numéro ajouté.</div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="inclure_frais_retrait" value="1" class="form-check-input" id="checkRetraitMultiple">
                <label class="form-check-label small" for="checkRetraitMultiple">Prendre en charge les frais de retrait pour tous</label>
            </div>

            <button type="submit" class="btn btn-dark w-100 fw-semibold">Diviser et Transférer</button>
        </form>
    </div>
</div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white fw-bold">Configurer mon epargne</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <form action="<?= base_url('transaction/epargne') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Pourcentage de mon epargne</label>
                            <div class="input-group">
                                <input type="number" name="epargne" class="form-control" placeholder="0.00" required>
                                <input type= "hidden" name=" id" values="">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">configurer</button>
                    </form>
                </div>
            </div>
        </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputNumero = document.getElementById('inputNumero');
    const btnAjouter = document.getElementById('btnAjouterNumero');
    const listeBadges = document.getElementById('listeBadgesNumeros');
    const conteneurInputs = document.getElementById('inputsMasquesConteneur');
    const errorDiv = document.getElementById('multipleNumeroError');
    const formMultiple = document.getElementById('formMultiple');

    // Tableau pour stocker les numéros uniques insérés
    let listeNumeros = [];

    function ajouterUnNumero() {
        const numero = inputNumero.value.trim();
        const prefixes = ['032', '033', '034', '038'];
        
        // Validation du format du numéro
        let isValid = false;
        for (let prefix of prefixes) {
            if (numero.startsWith(prefix) && numero.length === 10) {
                isValid = true;
                break;
            }
        }

        if (!isValid) {
            errorDiv.textContent = "Numéro invalide (doit faire 10 chiffres et commencer par 032, 033, 034 ou 038).";
            return;
        }

        if (listeNumeros.includes(numero)) {
            errorDiv.textContent = "Ce numéro a déjà été ajouté à la liste.";
            return;
        }

        errorDiv.textContent = "";
        listeNumeros.push(numero);

        // Mettre à jour l'interface graphique et les inputs
        renderList();
        inputNumero.value = "";
        inputNumero.focus();
    }

    function renderList() {
        listeBadges.innerHTML = "";
        conteneurInputs.innerHTML = "";

        listeNumeros.forEach((num, index) => {
            const badge = document.createElement('span');
            badge.className = "badge bg-secondary d-inline-flex align-items-center gap-2 p-2 fs-6";
            badge.innerHTML = `${num} <button type="button" class="btn-close btn-close-white small" style="font-size: 0.65rem;" data-index="${index}"></button>`;
            listeBadges.appendChild(badge);

            const hiddenInput = document.createElement('input');
            hiddenInput.type = "hidden";
            hiddenInput.name = "numeros_destinataires[]"; 
            hiddenInput.value = num;
            conteneurInputs.appendChild(hiddenInput);
        });
    }

    btnAjouter.addEventListener('click', ajouterUnNumero);

    inputNumero.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            ajouterUnNumero();
        }
    });
    listeBadges.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-close')) {
            const index = e.target.getAttribute('data-index');
            listeNumeros.splice(index, 1);
            renderList();
        }
    });
    formMultiple.addEventListener('submit', function(e) {
        if (listeNumeros.length === 0) {
            e.preventDefault();
            errorDiv.textContent = "Veuillez ajouter au moins un numéro de téléphone avant de valider.";
        }
    });
});
</script>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">Historique des transactions</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">I</th>
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