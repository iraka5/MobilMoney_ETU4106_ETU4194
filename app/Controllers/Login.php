<?php
namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController {
    public function index() {
        return  view('login');
    }


    private function getPrefixFromNumero(string $numero): string
    {
        return substr(trim($numero), 0, 3);
    }

}
