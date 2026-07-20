<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Dashboard extends BaseController {
    public function index() {
        if (! session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $usermodel = new UserModel();
        $user = $usermodel->find(session()->get('user_id'));

        $db = \Config\Database::connect();

        // Solde utilisateur
        $soldeRow = $db->table('solde_user')
                       ->where('id_user', session()->get('user_id'))
                       ->get()
                       ->getRow();
        $solde = $soldeRow ? $soldeRow->solde : 0;

        // Transactions
        $transactions = $db->table('transactions')
                           ->where('id_sender', session()->get('user_id'))
                           ->orWhere('id_receiver', session()->get('user_id'))
                           ->orderBy('date_transaction', 'DESC')
                           ->get()
                           ->getResultArray();

        // Commissions autres opérateurs (configuration)
        $commissions = $db->table('commissions')->get()->getResultArray();

        // Situation gain via autres opérateurs
        $gain_autres = $db->query("
            SELECT 'Autres opérateurs' AS libelle,
                   COUNT(*) AS nb_transactions,
                   SUM(frais) AS total_frais,
                   SUM(montant * (c.pourcentage/100)) AS commission_reversee,
                   SUM(frais) - SUM(montant * (c.pourcentage/100)) AS gain_net
            FROM transaction_autre_operateur t
            JOIN commissions c ON c.libelle = 'Autres opérateurs'
        ")->getResultArray();

        return view('dashboard', [
            'user' => $user,
            'solde' => $solde,
            'transactions' => $transactions,
            'commissions' => $commissions,
            'gain_autres' => $gain_autres
        ]);
    }
}
