<?php
namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController {
    
    public function index() {
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
        return redirect()->back()->with('error', 'Veuillez remplir tous les champs.');
    }

    $prefixe = ltrim(substr($numero, 0, 3), '0');

    $prefixeRow = $db->table('prefixe')
                     ->where('prefixe', $prefixe)
                     ->get()
                     ->getRow();

    if (!$prefixeRow) {
        return redirect()->back()->with('error', 'Préfixe invalide.');
    }

$user = $db->table('users')
           ->where('email', $email)
           ->where('numero', $numero)
           ->get()
           ->getRow();

dd([
    'email_saisi'      => $email,
    'numero_saisi'     => $numero,
    'utilisateur'      => $user,
    'mot_de_passe'     => $mdp,
    'password_verify'  => $user ? password_verify($mdp, $user->password) : 'Utilisateur introuvable'
]);

    if ($user) {
        if (!password_verify($mdp, $user->password)) {
            return redirect()->back()->with('error', 'Mot de passe incorrect.');
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
                   ->where('numero', $numero)
                   ->get()
                   ->getRow();

        $db->table('solde_user')->insert([
            'id_user' => $user->id,
            'solde'   => 0,
        ]);
    }

    session()->set([
        'user_id'  => $user->id,
        'email'    => $user->email,
        'numero'   => $user->numero,
        'loggedIn' => true,
    ]);

    return redirect()->to('/dashboard');
}
}