<?php

namespace App\Models;

use CodeIgniter\Model;

class ProyectoComponenteModel extends Model
{
    protected $table         = 'proyecto_componentes';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id_proyecto',
        'id_componente',
        'fecha_asignacion',
    ];

    protected $useTimestamps = false;
}

