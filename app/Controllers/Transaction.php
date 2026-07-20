<?php
namespace App\Controllers;

class Transaction extends BaseController {
    public function depot() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $montant = $this->request->getPost('montant');

        // Ajouter le montant au solde
        $db->table('solde_user')
           ->set('solde', 'solde + ' . (float)$montant, false)
           ->where('id_user', $userId)
           ->update();

        // Enregistrer la transaction
        $db->table('transactions')->insert([
            'id_sender' => $userId,
            'id_receiver' => $userId,
            'montant' => $montant,
            'frais' => 0,
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
        if ($soldeRow && $soldeRow->solde >= $montant) {
            $db->table('solde_user')
               ->set('solde', 'solde - ' . (float)$montant, false)
               ->where('id_user', $userId)
               ->update();

            $db->table('transactions')->insert([
                'id_sender' => $userId,
                'id_receiver' => $userId,
                'montant' => $montant,
                'frais' => 0,
                'statut' => 'Retrait',
                'id_type_operation' => 2
            ]);

            return redirect()->to('/dashboard')->with('success', 'Retrait effectué');
        } else {
            return redirect()->to('/dashboard')->with('error', 'Solde insuffisant');
        }
    }
}
