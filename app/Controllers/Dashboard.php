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

        $userId = session()->get('user_id');

$solde = $db->query("
    SELECT 
        COALESCE(SUM(
            CASE
                -- Dépôt : ajoute le montant net reçu (montant - frais)
                WHEN id_sender = $userId AND id_type_operation = 1
                THEN (montant - frais)

                -- Retrait : enlève le montant retiré + les frais appliqués
                WHEN id_sender = $userId AND id_type_operation = 2
                THEN -(montant + frais)

                -- Transfert envoyé (interne OU externe) : enlève le montant + les frais
                WHEN id_sender = $userId AND id_type_operation = 3
                THEN -(montant + frais)

                -- Transfert reçu (interne uniquement)
                WHEN id_receiver = $userId AND id_type_operation = 3
                THEN montant

                ELSE 0
            END
        ), 0) AS solde
    FROM transactions
")->getRow()->solde;


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

        $operateurs = $db->table('operateurs')->get()->getResultArray();

        $frais = $db->query("
            SELECT b.id AS id_bareme, t.libelle AS type_operation,
                   b.montant_min, b.montant_max, b.montant_frais
            FROM baremeFrais b
            JOIN type_operation t ON b.id_type_operation = t.id
        ")->getResultArray();


        $gain_frais = $db->query("
            SELECT t.libelle AS type_operation,
                   COUNT(tr.id_transaction) AS nb_transactions,
                   SUM(tr.frais) AS total_frais
            FROM transactions tr
            JOIN type_operation t ON tr.id_type_operation = t.id
            GROUP BY t.libelle
        ")->getResultArray();



        return view('dashboard', [
            'user' => $user,
            'solde' => $solde,
            'transactions' => $transactions,
            'operateurs' => $operateurs,
            'frais' => $frais,
            'gain_frais' => $gain_frais
        ]);
    }
}