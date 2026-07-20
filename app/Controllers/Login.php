<?php
namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController {
    
    public function index() {
        if (session()->get('loggedIn')) {
            return redirect()->to('dashboard');
        }
        return view('login');
    }

    private function getPrefixFromNumero(string $numero): string
    {
        $prefixe = substr(trim($numero), 0, 3);
        return ltrim($prefixe, '0');
    }

    public function check()
    {
        $db = \Config\Database::connect();

        $numero = trim($this->request->getPost('numero'));
        $email  = trim($this->request->getPost('email'));
        $mdp    = trim($this->request->getPost('mdp'));

        if (empty($numero) || empty($email) || empty($mdp)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs.');
        }

        $prefixe = $this->getPrefixFromNumero($numero);
        $prefixeRow = $db->table('prefixe')
                         ->where('prefixe', $prefixe)
                         ->get()
                         ->getRow();

        if (!$prefixeRow) {
            return redirect()->back()->withInput()->with('error', 'Opérateur non supporté pour ce numéro.');
        }

        $user = $db->table('users')
                   ->where('email', $email)
                   ->get()
                   ->getRow();

        if ($user) {

            if ($user->numero !== $numero) {
                return redirect()->back()->withInput()->with('error', 'Cet email est déjà associé à un autre numéro.');
            }

            if (!password_verify($mdp, $user->password) && $user->password !== $mdp) {
                return redirect()->back()->withInput()->with('error', 'Mot de passe incorrect.');
            }

        } else {

            $hash = password_hash($mdp, PASSWORD_DEFAULT);

            $db->table('users')->insert([
                'email'      => $email,
                'password'   => $hash,
                'numero'     => $numero,
                'id_prefixe' => $prefixeRow->id,
            ]);

            $user = $db->table('users')
                       ->where('email', $email)
                       ->get()
                       ->getRow();

            $db->table('solde_user')->insert([
                'id_user' => $user->id,
                'solde'   => 0,
            ]);
        }

        session()->set([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'numero'     => $user->numero,
            'isLoggedIn' => true,
            'loggedIn'   => true,
        ]);

        return redirect()->to('dashboard');
    }
}