<?php 

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table = 'baremeFrais'; 
    
    protected $primaryKey = 'id';

    protected $allowedFields = ['id_type_operation', 'montant_min', 'montant_max', 'montant_frais'];
}