<?php

namespace App\Controllers;

use App\Models\MotorModel;
use App\Models\ProductoModel;
use App\Models\VehiculoModel;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboard', [
            'totales' => [
                'productos' => (new ProductoModel())->countAllResults(),
                'vehiculos' => (new VehiculoModel())->countAllResults(),
                'motores'   => (new MotorModel())->countAllResults(),
            ],
        ]);
    }
}
