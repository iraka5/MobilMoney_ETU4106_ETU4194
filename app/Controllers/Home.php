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

    $data['gain_frais'] = $db->table('transactions t')
                             ->select('t.id_type_operation, o.libelle as type_operation, COUNT(*) as nb_transactions, SUM(t.frais) as total_frais')
                             ->join('type_operation o', 't.id_type_operation = o.id', 'left')
                             ->groupBy('t.id_type_operation')
                             ->get()
                             ->getResultArray();  

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
}