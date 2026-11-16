<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProjectRequestsAndPaymentPreference extends Migration
{
    public function up()
    {
        $this->forge->addColumn('proyectos_taller', [
            'estado_solicitud' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'aceptada',
            ],
            'metodo_pago' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('proyectos_taller', ['estado_solicitud', 'metodo_pago']);
    }
}
