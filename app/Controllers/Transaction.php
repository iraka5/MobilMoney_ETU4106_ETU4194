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
        $montant = $this->request->getPost('montant');

        $soldeRow = $db->table('solde_user')->where('id_user', $userId)->get()->getRow();
        $baremeModel = new BaremeFraisModel();
        $row = $baremeModel->getFraisForAmount(3, $montant);
        $frais = $row ? (is_array($row) ? (float)$row['montant_frais'] : (float)$row->montant_frais) : 0.0;

        if ($soldeRow && $soldeRow->solde >= ($montant + $frais)) {
            $destinataire = $db->table('users')->where('numero', $numeroDestinataire)->get()->getRow();
            if ($destinataire) {
                $db->table('solde_user')
                   ->set('solde', 'solde - ' . ((float)$montant + $frais), false)
                   ->where('id_user', $userId)
                   ->update();

                $db->table('solde_user')
                   ->set('solde', 'solde + ' . (float)$montant, false)
                   ->where('id_user', $destinataire->id)
                   ->update();

                $db->table('transactions')->insert([
                    'id_sender' => $userId,
                    'id_receiver' => $destinataire->id,
                    'montant' => $montant,
                    'frais' => $frais,
                    'statut' => 'Transfert',
                    'id_type_operation' => 3
                ]);

                return redirect()->to('/dashboard')->with('success', 'Transfert effectué');
            } else {
                return redirect()->to('/dashboard')->with('error', 'Destinataire introuvable');
            }
        } else {
            return redirect()->to('/dashboard')->with('error', 'Solde insuffisant');
        }
    }

}
