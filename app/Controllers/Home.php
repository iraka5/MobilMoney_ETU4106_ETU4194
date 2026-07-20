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
        
        $data['operateurs'] = $operateurModel->findAll();
        $data['frais']      = $fraisModel->findAll();
        
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