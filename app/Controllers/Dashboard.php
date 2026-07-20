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

<<<<<<< HEAD
                $transactions = $db->table('transactions t')
                           ->select('t.*, s.numero as sender_numero, s.email as sender_email, COALESCE(t.receiver_numero, r.numero) as receiver_numero, r.email as receiver_email')
                           ->join('users s', 't.id_sender = s.id', 'left')
                           ->join('users r', 't.id_receiver = r.id', 'left')
                                                     ->groupStart()
                                                         ->where('t.id_sender', session()->get('user_id'))
                                                         ->orWhere('t.id_receiver', session()->get('user_id'))
                                                     ->groupEnd()
                                                     ->orderBy('t.date_transaction', 'DESC')
                                                     ->get()
                                                     ->getResultArray();
=======
        // Transactions
        $transactions = $db->table('transactions')
                           ->where('id_sender', session()->get('user_id'))
                           ->orWhere('id_receiver', session()->get('user_id'))
                           ->orderBy('date_transaction', 'DESC')
                           ->get()
                           ->getResultArray();
>>>>>>> 48d02fc6a0359dfeb238af3d1bb0346a7ac6b91b

        // Liste des opérateurs
        $operateurs = $db->table('operateurs')->get()->getResultArray();

        // Barème des frais
        $frais = $db->query("
            SELECT b.id AS id_bareme, t.libelle AS type_operation,
                   b.montant_min, b.montant_max, b.montant_frais
            FROM baremeFrais b
            JOIN type_operation t ON b.id_type_operation = t.id
        ")->getResultArray();

        // Situation gain via les différents frais
        $gain_frais = $db->query("
            SELECT t.libelle AS type_operation,
                   COUNT(tr.id_transaction) AS nb_transactions,
                   SUM(tr.frais) AS total_frais
            FROM transactions tr
            JOIN type_operation t ON tr.id_type_operation = t.id
            GROUP BY t.libelle
        ")->getResultArray();

        // Commissions autres opérateurs (configuration)
        $commissions = $db->table('commissions')->get()->getResultArray();

        // Situation gain via commissions autres opérateurs
        $gain_autres = $db->query("
            SELECT 'Autres opérateurs' AS libelle,
                   COUNT(*) AS nb_transactions,
                   SUM(frais) AS total_frais,
                   SUM(montant * (c.pourcentage/100)) AS commission_reversee,
                   SUM(frais) - SUM(montant * (c.pourcentage/100)) AS gain_net
            FROM transaction_autre_operateur t
            JOIN commissions c ON c.libelle = 'Autres opérateurs'
        ")->getResultArray();

        // Situation des montants à envoyer à chaque opérateur
        $gain_operateurs = $db->query("
            SELECT o.libelle AS operateur,
                   COUNT(*) AS nb_transactions,
                   SUM(t.montant) AS montant_total,
                   SUM(t.montant * (c.pourcentage/100)) AS montant_a_envoyer
            FROM transaction_autre_operateur t
            JOIN users u ON u.id = t.id_user_dest
            JOIN prefixe p ON p.id = u.id_prefixe
            JOIN operateurs o ON o.id = p.id_operateur
            JOIN commissions c ON c.libelle = 'Autres opérateurs'
            GROUP BY o.libelle
        ")->getResultArray();

        return view('dashboard', [
            'user' => $user,
            'solde' => $solde,
            'transactions' => $transactions,
            'operateurs' => $operateurs,
            'frais' => $frais,
            'gain_frais' => $gain_frais,
            'commissions' => $commissions,
            'gain_autres' => $gain_autres,
            'gain_operateurs' => $gain_operateurs
        ]);
    }
}
