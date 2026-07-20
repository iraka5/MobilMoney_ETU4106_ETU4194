<?php
namespace App\Controllers;

class Login extends BaseController {
    public function index() {
        return view('login');
    }

    public function check() {

        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');


        return "Username: $username, Email: $email, Password: $password";
    }
}

