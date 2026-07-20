<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['email', 'password', 'id_prefixe', 'numero', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getUserByNumeroAndEmail($numero, $email)
    {
        return $this->where('numero', $numero)
                    ->where('email', $email)
                    ->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserByNumero($numero)
    {
        return $this->where('numero', $numero)->first();
    }


    public function emailExists($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    public function numeroExists($numero)
    {
        return $this->where('numero', $numero)->countAllResults() > 0;
    }


    public function createUser($data)
    {
        return $this->insert($data);
    }


    public function getUserWithInfo($id)
    {
        return $this->select('users.*, prefixe.prefixe, operateurs.libelle as operateur, solde_user.solde')
                    ->join('prefixe', 'prefixe.id = users.id_prefixe', 'left')
                    ->join('operateurs', 'operateurs.id = prefixe.id_operateur', 'left')
                    ->join('solde_user', 'solde_user.id_user = users.id', 'left')
                    ->where('users.id', $id)
                    ->first();
    }

    public function getAccountDetails($userId)
    {
        return $this->getUserWithInfo($userId);
    }
}
