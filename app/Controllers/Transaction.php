<?php
namespace App\Controllers;
use App\Models\BaremeFraisModel;

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

        // Vérifier solde
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
        $numeroDestinataire = $this->request->getPost('numero_destinataire');
        $montantInitial = (float)$this->request->getPost('montant');
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') === '1';

        if ($montantInitial <= 0) {
            return redirect()->to('/dashboard')->with('error', 'Le montant doit être supérieur à 0 Ar');
        }

        $baremeModel = new BaremeFraisModel();

        $rowTransfert = $baremeModel->getFraisForAmount(3, $montantInitial);
        $fraisTransfert = 0.0;
        if ($rowTransfert) {
            $fraisTransfert = is_array($rowTransfert) ? (float)$rowTransfert['montant_frais'] : (float)$rowTransfert->montant_frais;
        } else {
            $fraisTransfert = 50.0; 
        }

        $fraisRetrait = 0.0;
        if ($inclureFraisRetrait) {
            $rowRetrait = $baremeModel->getFraisForAmount(2, $montantInitial);
            if ($rowRetrait) {
                $fraisRetrait = is_array($rowRetrait) ? (float)$rowRetrait['montant_frais'] : (float)$rowRetrait->montant_frais;
            } else {
                $fraisRetrait = 50.0;
            }
        }

        $totalFraisTouche = $fraisTransfert + $fraisRetrait; 
        $montantRecu = $montantInitial + $fraisRetrait;      
        $totalDebite = $montantInitial + $totalFraisTouche;  

        $soldeRow = $db->table('solde_user')->where('id_user', $userId)->get()->getRow();
        $soldeActuel = $soldeRow ? (float)$soldeRow->solde : 0.0;

        if ($soldeActuel >= $totalDebite) {
            $destinataire = $db->table('users')->where('numero', $numeroDestinataire)->get()->getRow();

            $db->transStart(); 

            $db->table('solde_user')
               ->set('solde', 'solde - ' . $totalDebite, false)
               ->where('id_user', $userId)
               ->update();

            if ($destinataire) {
                $db->table('solde_user')
                   ->set('solde', 'solde + ' . $montantRecu, false)
                   ->where('id_user', $destinataire->id)
                   ->update();
            }
            $statut = $destinataire ? 'Transfert' : 'Transfert (externe)';
            if ($inclureFraisRetrait) {
                $statut .= ' + Frais Retrait';
            }

            $db->table('transactions')->insert([
                'id_sender'         => $userId,
                'id_receiver'       => $destinataire ? $destinataire->id : null,
                'receiver_numero'   => $numeroDestinataire,
                'montant'           => $montantInitial, 
                'frais'             => $totalFraisTouche, 
                'statut'            => $statut,
                'id_type_operation' => 3
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to('/dashboard')->with('error', 'Erreur lors de la mise à jour des soldes en base de données.');
            }

            return redirect()->to('/dashboard')->with('success', 'Transfert effectué avec succès');
        } else {
            return redirect()->to('/dashboard')->with('error', 'Solde insuffisant. Il vous faut ' . $totalDebite . ' Ar au total.');
        }
    }

    public function CalculFrais() {
        $montant = $this->request->getPost('montant');
        $typeOperation = $this->request->getPost('type_operation');
        $idOperateurSource = $this->request->getPost('id_operateur_source');
        $idOperateurDest   = $this->request->getPost('id_operateur_dest');

        $baremeModel = new BaremeFraisModel();
        $row = $baremeModel->getFraisForAmount($typeOperation, $montant);
        $frais = $row ? (is_array($row) ? (float)$row['montant_frais'] : (float)$row->montant_frais) : 0.0;

        if ($idOperateurSource != $idOperateurDest) {
            $db = \Config\Database::connect();
            $commissionRow = $db->table('commissions')
                                ->where('libelle', 'Inter-opérateurs')
                                ->get()
                                ->getRow();
            if ($commissionRow) {
                $frais += ($montant * $commissionRow->pourcentage / 100);
            }
        }

        return json_encode(['frais' => $frais]);
    }
}