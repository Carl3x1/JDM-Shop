<?php

namespace App\Models;

use CodeIgniter\Model;

class MotorModel extends Model
{
    protected $table = 'catalogo_motores';
    protected $primaryKey = 'id_motor';
    protected $allowedFields = [
        'codigo_motor',
        'descripcion',
        'hp_stock',
        'torque_stock',
        'limite_hp_bloque_stock',
    ];

    protected $useTimestamps = false;
}
