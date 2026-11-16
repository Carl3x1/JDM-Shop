<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCatalogDescriptionsAndRemovePaymentMethod extends Migration
{
    public function up()
    {
        foreach (['productos', 'vehiculos_base', 'catalogo_motores', 'componentes_modificacion'] as $table) {
            $this->forge->addColumn($table, [
                'descripcion' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }

        $descriptions = [
            'productos' => [
                1 => 'Aceite sintético para motores de alto rendimiento. Ayuda a mantener la lubricación y protección durante el uso diario o exigente.',
                2 => 'Filtro de alto flujo que facilita la entrada de aire al motor y puede complementar una preparación de admisión.',
                3 => 'Bujías de iridio diseñadas para una chispa estable y una buena durabilidad en motores de alto rendimiento.',
                4 => 'Radiador de aluminio que mejora la disipación de calor y ayuda a mantener estable la temperatura del motor.',
                5 => 'Medidor de mezcla aire-combustible para supervisar la relación AFR durante la puesta a punto.',
                6 => 'Pastillas de freno para mejorar la respuesta de frenado en conducción deportiva.',
                7 => 'Kit auxiliar de enfriamiento para ayudar a controlar la temperatura en condiciones de mayor exigencia.',
                8 => 'Kit de embrague reforzado para soportar mejor el torque de una configuración modificada.',
            ],
            'vehiculos_base' => [
                1 => 'Plataforma deportiva Nissan de la generación R34, conocida por su tracción integral y su potencial para proyectos de alto rendimiento. Año de referencia: 1999.',
                2 => 'Cupé deportivo Toyota de la generación A80, una plataforma popular para preparaciones turbo y proyectos de calle o pista. Año de referencia: 1994.',
                3 => 'Deportivo Mazda de motor rotativo de la generación FD, con bajo peso y gran potencial de preparación. Año de referencia: 1997.',
                4 => 'Compacto deportivo Honda de la generación EK, ligero y orientado a una conducción ágil. Año de referencia: 1998.',
                5 => 'Sedán deportivo Subaru de la generación GD, con tracción integral y una plataforma reconocida en rally. Año de referencia: 2004.',
            ],
            'catalogo_motores' => [
                1 => 'Motor Nissan de seis cilindros en línea y doble turbo, usado en la familia Skyline GT-R. Destaca por su robustez y amplio potencial de preparación.',
                2 => 'Motor Toyota de seis cilindros en línea y doble turbo. Su arquitectura robusta lo hace popular en proyectos turbo de alto rendimiento.',
                3 => 'Motor Mazda rotativo biturbo de dos rotores, conocido por su diseño compacto y respuesta característica.',
                4 => 'Motor Honda B de cuatro cilindros atmosférico y altas revoluciones, utilizado en aplicaciones deportivas de la familia Civic Type R.',
                5 => 'Motor Subaru bóxer turbo de cuatro cilindros, diseñado para aplicaciones deportivas con tracción integral.',
            ],
            'componentes_modificacion' => [
                1 => 'Kit de turbocompresor para aumentar el flujo de aire y permitir una mayor potencia. Requiere calibración y componentes compatibles.',
                2 => 'Sistema de escape de mayor flujo que ayuda a evacuar gases del motor y complementa las mejoras de admisión y turbo.',
                3 => 'Intercooler frontal que reduce la temperatura del aire comprimido antes de que ingrese al motor.',
                4 => 'Inyectores de mayor capacidad para suministrar combustible en configuraciones con mayor demanda.',
                5 => 'Unidad electrónica programable para ajustar combustible, encendido y otros parámetros durante la puesta a punto.',
                6 => 'Filtro de alto flujo que mejora la respiración del sistema de admisión cuando se instala correctamente.',
                7 => 'Bomba de combustible de mayor caudal para sostener el suministro en motores modificados.',
                8 => 'Árboles de levas de alto rendimiento que modifican el flujo de admisión y escape según la configuración del motor.',
            ],
        ];
        foreach ($descriptions as $table => $rows) {
            $primaryKey = ['productos'=>'id_producto', 'vehiculos_base'=>'id_vehiculo', 'catalogo_motores'=>'id_motor', 'componentes_modificacion'=>'id_componente'][$table];
            foreach ($rows as $id => $description) {
                $this->db->table($table)->where($primaryKey, $id)->update(['descripcion' => $description]);
            }
        }

        $this->forge->dropColumn('proyectos_taller', 'metodo_pago');
    }

    public function down()
    {
        $this->forge->addColumn('proyectos_taller', [
            'metodo_pago' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);

        foreach (['productos', 'vehiculos_base', 'catalogo_motores', 'componentes_modificacion'] as $table) {
            $this->forge->dropColumn($table, 'descripcion');
        }
    }
}
