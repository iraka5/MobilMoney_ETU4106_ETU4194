<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use CodeIgniter\Controller;
use App\Models\OperateurModel;
use App\Models\FraisModel;

class Home extends Controller
{
    public function index()
    {
        return "Connexion réussie";
    }

   public function listerTout()
{
    $operateurModel = new OperateurModel();
    $fraisModel     = new FraisModel();
    $db             = \Config\Database::connect();
    

    $usersList = $db->table('users')->select('id')->get()->getResultArray();
    
    foreach ($usersList as $u) {
        $uid = $u['id'];
        
        $query = $db->query("
            SELECT COALESCE(SUM(
                CASE
                    WHEN id_type_operation = 1 THEN (montant - frais)
                    WHEN id_type_operation = 2 THEN -(montant + frais)
                    WHEN id_type_operation = 3 AND id_sender = ? THEN -(montant + frais)
                    WHEN id_type_operation = 3 AND id_receiver = ? THEN 
                        (CASE WHEN statut LIKE '%+ Frais Retrait%' THEN (montant + 50.0) ELSE montant END)
                    ELSE 0
                END
            ), 0.0) AS balance
            FROM transactions
            WHERE id_sender = ? OR id_receiver = ?
        ", [$uid, $uid, $uid, $uid]);
        
        $balance = (float)$query->getRow()->balance;
        
        $exists = $db->table('solde_user')->where('id_user', $uid)->countAllResults();
        
        if ($exists > 0) {
            $db->table('solde_user')
               ->set('solde', $balance)
               ->set('last_updated', 'CURRENT_TIMESTAMP', false)
               ->where('id_user', $uid)
               ->update();
        } else {
            $db->table('solde_user')->insert([
                'id_user' => $uid,
                'solde'   => $balance
            ]);
        }
    }

    $data['operateurs'] = $operateurModel->findAll();
    $data['frais']      = $fraisModel->findAll();
    
    $data['users']      = $db->table('users u')
                             ->select('u.id, u.email, u.numero, o.libelle as nom_operateur, COALESCE(s.solde, 0.0) as solde, u.created_at')
                             ->join('prefixe p', 'p.id = u.id_prefixe', 'left')
                             ->join('operateurs o', 'o.id = p.id_operateur', 'left')
                             ->join('solde_user s', 's.id_user = u.id', 'left')
                             ->get()
                             ->getResultArray();

    // Gains par type d'opération, en séparant les transferts vers notre
    // opérateur des transferts vers les autres opérateurs (V2).
    $data['gain_frais'] = $db->query("
        SELECT
            CASE
                WHEN t.id_type_operation = 3 AND t.id_receiver IS NOT NULL THEN 'Transfert (notre opérateur)'
                WHEN t.id_type_operation = 3 AND t.id_receiver IS NULL     THEN 'Transfert (autres opérateurs)'
                ELSE o.libelle
            END AS type_operation,
            COUNT(*) AS nb_transactions,
            SUM(t.frais) AS total_frais
        FROM transactions t
        JOIN type_operation o ON t.id_type_operation = o.id
        GROUP BY type_operation
    ")->getResultArray();

    // Gain net (nos propres frais) sur les seuls transferts vers d'autres opérateurs
    $data['gain_autres'] = $db->query("
        SELECT
            'Autres opérateurs' AS libelle,
            COUNT(*) AS nb_transactions,
            COALESCE(SUM(frais), 0.0) AS gain_net
        FROM transaction_autre_operateur
    ")->getResultArray();

    // Commissions configurées par opérateur (modifiables)
    $data['commissions'] = $db->table('commissions c')
                              ->select('c.id, o.libelle, c.pourcentage')
                              ->join('operateurs o', 'o.id = c.id_operateur')
                              ->get()
                              ->getResultArray();

    // Montants à reverser à chaque opérateur (somme des commissions collectées pour leur compte)
    $data['gain_operateurs'] = $db->query("
        SELECT
            o.libelle AS operateur,
            COUNT(*) AS nb_transactions,
            SUM(t.montant) AS montant_total,
            SUM(t.commission) AS montant_a_envoyer
        FROM transaction_autre_operateur t
        JOIN operateurs o ON o.id = t.id_operateur_dest
        GROUP BY o.libelle
    ")->getResultArray();

    return view('operateur/index', $data);
}

    public function modifierFrais($id)
    {
        helper('form');
        $baremeModel = new BaremeFraisModel();
        $fraisModel  = new FraisModel();

        $data['frais'] = $baremeModel->find($id);

        if (!$data['frais']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Frais introuvable.");
        }

        $infoVue = $fraisModel->find($id);
        $data['type_operation'] = $infoVue ? $infoVue['type_operation'] : 'Inconnu';

        return view('operateur/modifier_frais', $data);
    }

    public function mettreAJourFrais($id)
    {
        helper('form');
        $baremeModel = new BaremeFraisModel();
        $baremeModel->update($id, [
            'montant_min'   => $this->request->getPost('montant_min'),
            'montant_max'   => $this->request->getPost('montant_max'),
            'montant_frais' => $this->request->getPost('montant_frais'),
        ]);

        return redirect()->to('/operateurs')->with('success', 'Frais modifié avec succès.');
    }

    public function modifierCommission($id)
    {
        helper('form');
        $db = \Config\Database::connect();

        $data['commission'] = $db->table('commissions c')
                                 ->select('c.id, c.pourcentage, o.libelle')
                                 ->join('operateurs o', 'o.id = c.id_operateur')
                                 ->where('c.id', $id)
                                 ->get()
                                 ->getRowArray();

        if (! $data['commission']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Commission introuvable.');
        }

        return view('operateur/modifier_commission', $data);
    }

    public function mettreAJourCommission($id)
    {
        $db = \Config\Database::connect();
        $db->table('commissions')
           ->where('id', $id)
           ->update(['pourcentage' => $this->request->getPost('pourcentage')]);

        return redirect()->to('/operateurs')->with('success', 'Commission modifiée avec succès.');
    }
}