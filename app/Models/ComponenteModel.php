<?php

namespace App\Models;

use CodeIgniter\Model;

class ComponenteModel extends Model
{
    protected $table = 'componentes_modificacion';
    protected $primaryKey = 'id_componente';
    protected $allowedFields = [
        'categoria',
        'nombre',
        'descripcion',
        'aporte_hp_pct',
        'aporte_torque_pct',
        'precio',
        'stock',
    ];

    protected $useTimestamps = false;
}
