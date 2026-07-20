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
        $soldeRow = $db->table('solde_user')
                       ->where('id_user', session()->get('user_id'))
                       ->get()
                       ->getRow();
        $solde = $soldeRow ? $soldeRow->solde : 0;

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

        return view('dashboard', [
            'user' => $user,
            'solde' => $solde,
            'transactions' => $transactions
        ]);
    }
}
