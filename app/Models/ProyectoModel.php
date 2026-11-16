<?php

namespace App\Models;

use CodeIgniter\Model;

class ProyectoModel extends Model
{
    protected $table      = 'proyectos_taller';
    protected $primaryKey = 'id_proyecto';
    protected $allowedFields = [
        'nombre_proyecto',
        'detalle_solicitud',
        'id_usuario',
        'id_vehiculo',
        'id_motor',
        'fase_taller',
        'total_cotizacion',
        'hp_estimado',
        'torque_estimado',
        'estado_solicitud',
    ];

    protected $useTimestamps = false;

    /**
     * Obtiene el listado de todos los proyectos con datos relacionados
     * (cliente, vehículo base y motor).
     *
     * @return array
     */
    public function getProyectosConDetalle(bool $incluirContacto = true, ?string $estadoSolicitud = null): array
    {
        $camposCliente = 'usuarios.nombre AS usuario_nombre';
        if ($incluirContacto) {
            $camposCliente .= ', usuarios.email AS usuario_email';
        }

        $query = $this->select('proyectos_taller.*, '
                . $camposCliente . ',
                vehiculos_base.marca AS vehiculo_marca, 
                vehiculos_base.modelo AS vehiculo_modelo, 
                vehiculos_base.anio AS vehiculo_anio, 
                vehiculos_base.codigo_chasis AS vehiculo_chasis,
                catalogo_motores.codigo_motor, 
                catalogo_motores.hp_stock, 
                catalogo_motores.torque_stock, 
                catalogo_motores.limite_hp_bloque_stock')
            ->join('usuarios', 'usuarios.id_usuario = proyectos_taller.id_usuario', 'left')
            ->join('vehiculos_base', 'vehiculos_base.id_vehiculo = proyectos_taller.id_vehiculo', 'left')
            ->join('catalogo_motores', 'catalogo_motores.id_motor = proyectos_taller.id_motor', 'left')
            ->orderBy('proyectos_taller.id_proyecto', 'DESC');

        if ($estadoSolicitud !== null) {
            $query->where('proyectos_taller.estado_solicitud', $estadoSolicitud);
        }

        return $query->findAll();
    }

    /**
     * Obtiene los datos completos de un proyecto individual con sus relaciones.
     *
     * @param int|string $id
     * @return array|null
     */
    public function getProyectoCompleto($id, bool $incluirDatosPrivados = true): ?array
    {
        $camposCliente = 'usuarios.nombre AS usuario_nombre, usuarios.rol AS usuario_rol';
        if ($incluirDatosPrivados) {
            $camposCliente .= ', usuarios.email AS usuario_email, usuarios.dpi AS usuario_dpi, usuarios.nit AS usuario_nit, usuarios.telefono AS usuario_telefono, usuarios.direccion AS usuario_direccion';
        }

        return $this->select('proyectos_taller.*, '
                . $camposCliente . ',
                vehiculos_base.marca AS vehiculo_marca, 
                vehiculos_base.modelo AS vehiculo_modelo, 
                vehiculos_base.anio AS vehiculo_anio, 
                vehiculos_base.codigo_chasis AS vehiculo_chasis,
                catalogo_motores.codigo_motor, 
                catalogo_motores.hp_stock, 
                catalogo_motores.torque_stock, 
                catalogo_motores.limite_hp_bloque_stock')
            ->join('usuarios', 'usuarios.id_usuario = proyectos_taller.id_usuario', 'left')
            ->join('vehiculos_base', 'vehiculos_base.id_vehiculo = proyectos_taller.id_vehiculo', 'left')
            ->join('catalogo_motores', 'catalogo_motores.id_motor = proyectos_taller.id_motor', 'left')
            ->where('proyectos_taller.id_proyecto', $id)
            ->first();
    }

    /**
     * Obtiene los componentes de modificación asignados a un proyecto específico.
     *
     * @param int|string $id_proyecto
     * @return array
     */
    public function getComponentes($id_proyecto): array
    {
        $db = \Config\Database::connect();

        return $db->table('proyecto_componentes')
            ->select('componentes_modificacion.*, proyecto_componentes.fecha_asignacion')
            ->join('componentes_modificacion', 'componentes_modificacion.id_componente = proyecto_componentes.id_componente')
            ->where('proyecto_componentes.id_proyecto', $id_proyecto)
            ->orderBy('componentes_modificacion.categoria', 'ASC')
            ->orderBy('componentes_modificacion.nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Devuelve una lista de IDs de componentes asociados al proyecto.
     *
     * @param int|string $id_proyecto
     * @return array
     */
    public function getComponentesIds($id_proyecto): array
    {
        $db = \Config\Database::connect();

        $rows = $db->table('proyecto_componentes')
            ->select('id_componente')
            ->where('id_proyecto', $id_proyecto)
            ->get()
            ->getResultArray();

        return array_map('intval', array_column($rows, 'id_componente'));
    }

    /**
     * Sincroniza la tabla pivote de componentes para un proyecto dado.
     *
     * @param int|string $id_proyecto
     * @param array $componentesIds
     * @return void
     */
    public function syncComponentes($id_proyecto, array $componentesIds): void
    {
        $db = \Config\Database::connect();

        $db->table('proyecto_componentes')
            ->where('id_proyecto', $id_proyecto)
            ->delete();

        if (! empty($componentesIds)) {
            $batch = [];
            foreach ($componentesIds as $idComp) {
                $idCompInt = (int) $idComp;
                if ($idCompInt > 0) {
                    $batch[] = [
                        'id_proyecto'   => (int) $id_proyecto,
                        'id_componente' => $idCompInt,
                    ];
                }
            }

            if (! empty($batch)) {
                $db->table('proyecto_componentes')->insertBatch($batch);
            }
        }
    }
}
