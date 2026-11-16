<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'categoria',
        'precio',
        'stock',
        'aporte_hp',
        'aporte_torque',
    ];

    protected $useTimestamps = false;
}
