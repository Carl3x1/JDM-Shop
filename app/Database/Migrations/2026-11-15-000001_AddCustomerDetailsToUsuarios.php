<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerDetailsToUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'dpi' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nit' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'telefono' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'direccion' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', ['dpi', 'nit', 'telefono', 'direccion']);
    }
}
