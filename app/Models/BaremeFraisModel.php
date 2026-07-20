<?php 

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table = 'baremeFrais'; 
    
    protected $primaryKey = 'id';

    protected $allowedFields = ['id_type_operation', 'montant_min', 'montant_max', 'montant_frais'];

    /**
     * Retourne l'enregistrement de barème correspondant au type d'opération
     * et au montant fourni (montant_min <= montant <= montant_max).
     * Retourne null si aucun barème trouvé.
     */
    public function getFraisForAmount($idTypeOperation, $montant)
    {
        return $this->where('id_type_operation', $idTypeOperation)
                    ->where('montant_min <=', (float)$montant)
                    ->where('montant_max >=', (float)$montant)
                    ->first();
    }
}