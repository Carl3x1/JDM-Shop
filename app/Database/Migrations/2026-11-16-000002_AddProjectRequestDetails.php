<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProjectRequestDetails extends Migration
{
    public function up()
    {
        $this->forge->addColumn('proyectos_taller', [
            'detalle_solicitud' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('proyectos_taller', 'detalle_solicitud');
    }
}
