<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table = 'commissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_operateur', 'pourcentage'];


    public function getPourcentagePourOperateur(int $idOperateur): float
    {
        $row = $this->where('id_operateur', $idOperateur)->first();
        return $row ? (float) $row['pourcentage'] : 0.0;
    }
}
