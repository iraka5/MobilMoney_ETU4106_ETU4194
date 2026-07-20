<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table = 'vue_bareme_operations';
    protected $primaryKey = 'id_bareme';
    protected $allowedFields = []; 
}