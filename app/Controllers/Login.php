<?php
namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController {
    public function index() {
        return  view('login');
    }

    public function check() {
        $usermodel = new UserModel();

        $numero = $this->request->getPost('numero');
        $email  = $this->request->getPost('email');
        $mdp    = $this->request->getPost('password');

        // Vérifier si l’utilisateur existe déjà
        $user = $usermodel->where('numero', $numero)
                          ->where('email', $email)
                          ->first();

        if ($user) {
            // Connexion
            if (password_verify($mdp, $user['password'])) {
                session()->set('user_id', $user['id']);
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Mot de passe incorrect');
            }
        } else {
            
            $usermodel->insert([
                'numero'     => $numero,
                'email'      => $email,
                'password'   => password_hash($mdp, PASSWORD_DEFAULT),
                'id_prefixe' => 1,
            ]);

            session()->set('user_id', $usermodel->getInsertID());
            return redirect()->to('/dashboard')->with('success', 'Compte créé avec succès');
        }
    }
}
