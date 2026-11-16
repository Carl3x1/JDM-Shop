<?php

namespace App\Models;

use CodeIgniter\Model;

class VehiculoModel extends Model
{
    protected $table = 'vehiculos_base';
    protected $primaryKey = 'id_vehiculo';
    protected $allowedFields = [
        'marca',
        'modelo',
        'descripcion',
        'anio',
        'codigo_chasis',
    ];

    protected $useTimestamps = false;
}
