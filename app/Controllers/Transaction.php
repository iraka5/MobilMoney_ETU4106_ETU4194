<?php
namespace App\Controllers;
use App\Models\BaremeFraisModel;
use App\Models\CommissionModel;

class Transaction extends BaseController {
    
    public function depot() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $montant = $this->request->getPost('montant');
        
        $baremeModel = new BaremeFraisModel();
        $row = $baremeModel->getFraisForAmount(1, $montant);
        $frais = $row ? (is_array($row) ? (float)$row['montant_frais'] : (float)$row->montant_frais) : 0.0;

        $delta = (float)$montant - $frais;
        $db->table('solde_user')
           ->set('solde', 'solde + ' . $delta, false)
           ->where('id_user', $userId)
           ->update();

        

        $db->table('transactions')->insert([
            'id_sender' => $userId,
            'id_receiver' => $userId,
            'montant' => $montant,
            'frais' => $frais,
            'statut' => 'Depot',
            'id_type_operation' => 1
        ]);

        return redirect()->to('/dashboard')->with('success', 'Dépôt effectué');
    }

    public function retrait() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $montant = $this->request->getPost('montant');

        $soldeRow = $db->table('solde_user')->where('id_user', $userId)->get()->getRow();
        // Calculer frais depuis le barème
        $baremeModel = new BaremeFraisModel();
        $row = $baremeModel->getFraisForAmount(2, $montant);
        $frais = $row ? (is_array($row) ? (float)$row['montant_frais'] : (float)$row->montant_frais) : 0.0;

        if ($soldeRow && $soldeRow->solde >= ($montant + $frais)) {
            $db->table('solde_user')
               ->set('solde', 'solde - ' . ((float)$montant + $frais), false)
               ->where('id_user', $userId)
               ->update();

            $db->table('transactions')->insert([
                'id_sender' => $userId,
                'id_receiver' => $userId,
                'montant' => $montant,
                'frais' => $frais,
                'statut' => 'Retrait',
                'id_type_operation' => 2
            ]);

            return redirect()->to('/dashboard')->with('success', 'Retrait effectué');
        } else {
            return redirect()->to('/dashboard')->with('error', 'Solde insuffisant');
        }
    }

    public function transfert() {
    $db = \Config\Database::connect();
    $userId = session()->get('user_id');
    
    // Récupération de la chaîne brute de numéros et nettoyage
    $numerosBruts = $this->request->getPost('numero_destinataire');
    $montantTotalSaisi = (float)$this->request->getPost('montant');
    $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') === '1';

    // Découper les numéros par virgule, espace ou point-virgule
    $tabNumeros = preg_split('/[\s,;]+/', trim($numerosBruts));
    $tabNumeros = array_filter($tabNumeros); // Supprimer les entrées vides
    $nbDestinataires = count($tabNumeros);

    if ($nbDestinataires <= 0) {
        return redirect()->back()->with('error', 'Veuillez saisir au moins un numéro de destinataire.');
    }
    if ($montantTotalSaisi <= 0) {
        return redirect()->back()->with('error', 'Le montant doit être supérieur à 0 Ar.');
    }

    // Calcul du montant individuel par personne
    $montantIndividuel = $montantTotalSaisi / $nbDestinataires;

    $baremeModel = new BaremeFraisModel();
    $commissionModel = new CommissionModel();
    $db->transStart(); // Début de la transaction globale sécurisée

    $totalFraisToucheGlobal = 0.0;
    $totalDebiteGlobal = 0.0;
    $actionsAEffectuer = [];

    // Étape 1 : Pré-calculer et valider pour TOUS les destinataires avant de débiter quoi que ce soit
    foreach ($tabNumeros as $numeroDest) {
        $numeroDest = trim($numeroDest);

        // Calcul frais de transfert
        $rowTransfert = $baremeModel->getFraisForAmount(3, $montantIndividuel);
        $fraisTransfert = $rowTransfert ? (is_array($rowTransfert) ? (float)$rowTransfert['montant_frais'] : (float)$rowTransfert->montant_frais) : 50.0;

        // Calcul frais de retrait optionnels
        $fraisRetrait = 0.0;
        if ($inclureFraisRetrait) {
            $rowRetrait = $baremeModel->getFraisForAmount(2, $montantIndividuel);
            $fraisRetrait = $rowRetrait ? (is_array($rowRetrait) ? (float)$rowRetrait['montant_frais'] : (float)$rowRetrait->montant_frais) : 50.0;
        }

        // Recherche si le destinataire est interne (client de notre opérateur)
        $destinataire = $db->table('users')->where('numero', $numeroDest)->get()->getRow();

        // Si le destinataire n'existe pas chez nous, on regarde si son préfixe
        // correspond à un autre opérateur connu, et on applique la commission
        // de cet opérateur en plus des frais de transfert.
        $operateurDest = null;
        $commissionMontant = 0.0;
        if (! $destinataire) {
            $prefixeDest = ltrim(substr($numeroDest, 0, 3), '0');
            $prefixeRow  = $db->table('prefixe')->where('prefixe', $prefixeDest)->get()->getRow();

            if (! $prefixeRow) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', "Le numéro $numeroDest ne correspond à aucun opérateur connu.");
            }

            $operateurDest = $prefixeRow->id_operateur;
            $pourcentageCommission = $commissionModel->getPourcentagePourOperateur($operateurDest);
            $commissionMontant = $montantIndividuel * ($pourcentageCommission / 100);
        }

        $fraisDestinataire = $fraisTransfert + $fraisRetrait + $commissionMontant;
        $montantRecu = $montantIndividuel + $fraisRetrait;
        $totalDebiteDestinataire = $montantIndividuel + $fraisDestinataire;

        $totalFraisToucheGlobal += $fraisDestinataire;
        $totalDebiteGlobal += $totalDebiteDestinataire;

        // On stocke les données pour l'exécution finale
        $actionsAEffectuer[] = [
            'destinataire_obj'   => $destinataire,
            'numero'             => $numeroDest,
            'montant_recu'       => $montantRecu,
            'frais_touche'       => $fraisTransfert + $fraisRetrait,
            'commission_touchee' => $commissionMontant,
            'id_operateur_dest'  => $operateurDest,
            'montant_de_base'    => $montantIndividuel
        ];
    }

    $soldeRow = $db->table('solde_user')->where('id_user', $userId)->get()->getRow();
    $soldeActuel = $soldeRow ? (float)$soldeRow->solde : 0.0;

    if ($soldeActuel < $totalDebiteGlobal) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Solde insuffisant pour effectuer tous les envois. Requis global : ' . $totalDebiteGlobal . ' Ar.');
    }

  
    $db->table('solde_user')
       ->set('solde', 'solde - ' . $totalDebiteGlobal, false)
       ->where('id_user', $userId)
       ->update();

    foreach ($actionsAEffectuer as $action) {
        $dest = $action['destinataire_obj'];
        
        if ($dest) {
            $db->table('solde_user')
               ->set('solde', 'solde + ' . $action['montant_recu'], false)
               ->where('id_user', $dest->id)
               ->update();
        } else {
            $db->table('transaction_autre_operateur')->insert([
                'id_user_source'    => $userId,
                'numero_dest'       => $action['numero'],
                'id_operateur_dest' => $action['id_operateur_dest'],
                'montant'           => $action['montant_de_base'],
                'frais'             => $action['frais_touche'],
                'commission'        => $action['commission_touchee'],
                'date_cree'         => date('Y-m-d H:i:s')
            ]);
        }

        // NB : la commission due à l'autre opérateur n'est PAS comptée ici.
        // C'est un montant à leur reverser, pas un gain pour notre opérateur ;
        // elle reste uniquement enregistrée dans transaction_autre_operateur.commission.
        $statut = $dest ? 'Transfert' : 'Transfert (externe)';
        if ($nbDestinataires > 1) $statut .= ' (Multiple ' . $nbDestinataires . 'x)';
        if ($inclureFraisRetrait)  $statut .= ' + Frais Retrait';

        $db->table('transactions')->insert([
            'id_sender'         => $userId,
            'id_receiver'       => $dest ? $dest->id : null,
            'receiver_numero'   => $action['numero'],
            'montant'           => $action['montant_de_base'],
            'frais'             => $action['frais_touche'],
            'statut'            => $statut,
            'id_type_operation' => 3
        ]);
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Erreur critique de transaction en base de données.');
    }

    return redirect()->to('dashboard')->with('success', 'Envois multiples effectués avec succès !');
}

    public function CalculFrais() {
        $montant = $this->request->getPost('montant');
        $typeOperation = $this->request->getPost('type_operation');
        $idOperateurSource = $this->request->getPost('id_operateur_source');
        $idOperateurDest   = $this->request->getPost('id_operateur_dest');

        $baremeModel = new BaremeFraisModel();
        $row = $baremeModel->getFraisForAmount($typeOperation, $montant);
        $frais = $row ? (is_array($row) ? (float)$row['montant_frais'] : (float)$row->montant_frais) : 0.0;

        if ($idOperateurSource && $idOperateurDest && $idOperateurSource != $idOperateurDest) {
            $commissionModel = new CommissionModel();
            $pourcentage = $commissionModel->getPourcentagePourOperateur((int)$idOperateurDest);
            $frais += ($montant * $pourcentage / 100);
        }

        return json_encode(['frais' => $frais]);
    }

public function transfertMultiple() 
{
    $tabNumeros = $this->request->getPost('numeros_destinataires');
    $montantGlobal = (float)$this->request->getPost('montant_global');
    $inclureFrais = $this->request->getPost('inclure_frais_retrait');

    if (empty($tabNumeros) || !is_array($tabNumeros)) {
        return redirect()->back()->with('error', 'Veuillez ajouter au moins un numéro de destinataire.');
    }

    $nbDestinataires = count($tabNumeros);
    
    $montantParPersonne = $montantGlobal / $nbDestinataires;

    $listeNumerosPropres = implode(' ', $tabNumeros);
    $_POST['numero_destinataire'] = $listeNumerosPropres;
    $_POST['montant'] = $montantGlobal;
    $_POST['inclure_frais_retrait'] = $inclureFrais;

    $this->request->setGlobal('post', [
        'numero_destinataire'   => $listeNumerosPropres,
        'montant'               => $montantGlobal,
        'inclure_frais_retrait' => $inclureFrais
    ]);

    return $this->transfert();
}

    public function epargne() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $taux = $this->request->getPost('epargne');


        $db->table('epargne')->insert([
            'id_user' => $userId,
            'pourcentage' => $taux,
        ]);

        return redirect()->to('/dashboard')->with('success', 'epargne enregistré');
    }



public function promotion(){
    $db= \config\database::Connect();
    }
}