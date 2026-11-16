<?php

namespace App\Controllers;

use App\Models\ComponenteModel;
use App\Models\MotorModel;
use App\Models\ProyectoModel;
use App\Models\UsuarioModel;
use App\Models\VehiculoModel;

class Proyectos extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $model     = new ProyectoModel();
        $rol       = session()->get('rol') ?? 'cliente';
        $idUsuario = session()->get('id_usuario');

        // Si es cliente, filtrar únicamente sus propios proyectos
        if ($rol === 'cliente') {
            $proyectos = $model->select('proyectos_taller.*, 
                    usuarios.nombre AS usuario_nombre, 
                    usuarios.email AS usuario_email,
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
                ->where('proyectos_taller.id_usuario', $idUsuario)
                ->orderBy('proyectos_taller.id_proyecto', 'DESC')
                ->findAll();
        } else {
            $proyectos = $model->getProyectosConDetalle($rol !== 'trabajador', $rol === 'trabajador' ? 'aceptada' : null);
        }

        return view('proyectos/index', [
            'proyectos' => $proyectos,
            'esAdmin'   => ($rol === 'admin'),
        ]);
    }

    public function crear()
    {
        if (! $this->puedeConfigurarProyectos()) {
            return redirect()->to('/proyectos')->with('error', 'Solo clientes y administradores pueden crear configuraciones.');
        }

        $data = array_merge($this->catalogos(), [
            'componentes_seleccionados' => old('componentes', []),
            'proyecto'                  => null,
            'rolActual'                 => session()->get('rol'),
        ]);

        return view('proyectos/crear', $data);
    }

    public function guardar()
    {
        if (! $this->puedeConfigurarProyectos()) {
            return redirect()->to('/proyectos')->with('error', 'No tienes permisos para crear configuraciones.');
        }

        $idMotor        = (int) $this->request->getPost('id_motor');
        $componentesIds = array_filter(array_map('intval', (array) ($this->request->getPost('componentes') ?? [])));

        // Si es cliente, fijar forzosamente su propio id_usuario
        $rol       = session()->get('rol') ?? 'cliente';
        $idUsuario = ($rol === 'admin')
            ? (int) $this->request->getPost('id_usuario')
            : (int) session()->get('id_usuario');

        // Cálculo de taller y cotizador en backend
        $metricas = $this->calcularMetricas($idMotor, $componentesIds);

        $data = [
            'nombre_proyecto'  => trim($this->request->getPost('nombre_proyecto') ?? ''),
            'detalle_solicitud' => trim($this->request->getPost('detalle_solicitud') ?? ''),
            'id_usuario'       => $idUsuario,
            'id_vehiculo'      => $this->request->getPost('id_vehiculo'),
            'id_motor'         => $idMotor,
            'fase_taller'      => $rol === 'cliente' ? 'Presupuesto Inicial' : trim($this->request->getPost('fase_taller') ?? 'Presupuesto Inicial'),
            'total_cotizacion' => $metricas['total_cotizacion'],
            'hp_estimado'      => $metricas['hp_estimado'],
            'torque_estimado'  => $metricas['torque_estimado'],
            'estado_solicitud' => $rol === 'cliente' ? 'pendiente' : 'aceptada',
        ];

        if (! $this->validateData($data, $this->proyectoRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new ProyectoModel();
        $db    = \Config\Database::connect();
        $db->transStart();
        $idProyecto = $model->insert($data);

        if ($idProyecto === false) {
            $db->transComplete();
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar el proyecto en el taller.');
        }

        // Guardar componentes en tabla pivote
        $model->syncComponentes($idProyecto, $componentesIds);
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'No se pudo guardar el proyecto y sus componentes.');
        }

        $mensaje = $rol === 'cliente'
            ? 'Solicitud enviada. Quedará pendiente hasta que administración la revise.'
            : 'Proyecto de taller creado correctamente.';
        return redirect()->to('/proyectos/mostrar/' . $idProyecto)->with('success', $mensaje);
    }

    public function mostrar($id = null)
    {
        $model    = new ProyectoModel();
        $proyecto = $model->getProyectoCompleto($id, session()->get('rol') !== 'trabajador');

        if ($proyecto === null) {
            return redirect()->to('/proyectos')->with('error', 'Proyecto no encontrado.');
        }

        if (session()->get('rol') === 'cliente'
            && (int) $proyecto['id_usuario'] !== (int) session()->get('id_usuario')) {
            return redirect()->to('/proyectos')
                ->with('error', 'Acceso denegado: Solo puedes ver tus propios proyectos.');
        }

        if (session()->get('rol') === 'trabajador' && $proyecto['estado_solicitud'] !== 'aceptada') {
            return redirect()->to('/proyectos')->with('error', 'Las solicitudes pendientes no están disponibles para trabajadores.');
        }

        $componentes = $model->getComponentes($id);

        // Desglose de cálculos para la ficha técnica
        $hpStock     = (float) ($proyecto['hp_stock'] ?? 0);
        $torqueStock = (float) ($proyecto['torque_stock'] ?? 0);
        $limiteHp    = (float) ($proyecto['limite_hp_bloque_stock'] ?? 0);

        $sumaPctHp     = 0.0;
        $sumaPctTorque = 0.0;
        $subtotalPiezas = 0.0;

        foreach ($componentes as $comp) {
            $subtotalPiezas += (float) ($comp['precio'] ?? 0);
            $sumaPctHp      += (float) ($comp['aporte_hp_pct'] ?? $comp['aporte_hp'] ?? 0);
            $sumaPctTorque  += (float) ($comp['aporte_torque_pct'] ?? $comp['aporte_torque'] ?? 0);
        }

        $hpFinal     = $hpStock * (1 + ($sumaPctHp / 100));
        $torqueFinal = $torqueStock * (1 + ($sumaPctTorque / 100));
        $gananciaHp  = $hpFinal - $hpStock;
        $gananciaTq  = $torqueFinal - $torqueStock;

        // Porcentaje respecto al límite del bloque
        $pctLimiteBloque = $limiteHp > 0 ? round(($hpFinal / $limiteHp) * 100, 1) : 0;
        $alertaBloque    = $limiteHp > 0 && $hpFinal > $limiteHp;

        return view('proyectos/ver', [
            'proyecto'        => $proyecto,
            'componentes'     => $componentes,
            'metricas'        => [
                'hp_stock'          => $hpStock,
                'torque_stock'      => $torqueStock,
                'limite_hp'         => $limiteHp,
                'suma_pct_hp'       => round($sumaPctHp, 2),
                'suma_pct_torque'   => round($sumaPctTorque, 2),
                'hp_final'          => round($hpFinal, 2),
                'torque_final'      => round($torqueFinal, 2),
                'ganancia_hp'       => round($gananciaHp, 2),
                'ganancia_torque'   => round($gananciaTq, 2),
                'pct_limite_bloque' => $pctLimiteBloque,
                'alerta_bloque'     => $alertaBloque,
                'subtotal_piezas'   => $subtotalPiezas,
            ],
        ]);
    }

    public function editar($id = null)
    {
        if (! $this->puedeConfigurarProyectos()) {
            return redirect()->to('/proyectos')->with('error', 'Los trabajadores solo pueden actualizar la fase del proyecto.');
        }

        $model    = new ProyectoModel();
        $proyecto = $model->find($id);

        if ($proyecto === null) {
            return redirect()->to('/proyectos')->with('error', 'Proyecto no encontrado.');
        }

        $rol       = session()->get('rol') ?? 'cliente';
        $idUsuario = session()->get('id_usuario');

        if ($rol === 'cliente' && (int) $proyecto['id_usuario'] !== (int) $idUsuario) {
            return redirect()->to('/proyectos')->with('error', 'Acceso denegado: Solo puedes editar tus propios proyectos.');
        }

        if ($rol === 'cliente' && $proyecto['estado_solicitud'] === 'aceptada') {
            return redirect()->to('/proyectos/mostrar/' . $id)->with('error', 'La solicitud ya está aprobada y no se puede modificar. Contacta al taller si necesitas un cambio.');
        }

        $componentesSeleccionados = old('componentes', $model->getComponentesIds($id));

        return view('proyectos/editar', array_merge(
            [
                'proyecto'                  => $proyecto,
                'componentes_seleccionados' => $componentesSeleccionados,
                'rolActual'                 => $rol,
            ],
            $this->catalogos()
        ));
    }

    public function actualizar($id = null)
    {
        if (! $this->puedeConfigurarProyectos()) {
            return redirect()->to('/proyectos')->with('error', 'No tienes permisos para modificar esta configuración.');
        }

        $model = new ProyectoModel();
        $proyectoExistente = $model->find($id);

        if ($proyectoExistente === null) {
            return redirect()->to('/proyectos')->with('error', 'Proyecto no encontrado.');
        }

        $rol       = session()->get('rol') ?? 'cliente';
        $idUsuario = session()->get('id_usuario');

        if ($rol === 'cliente' && (int) $proyectoExistente['id_usuario'] !== (int) $idUsuario) {
            return redirect()->to('/proyectos')->with('error', 'Acceso denegado: Solo puedes actualizar tus propios proyectos.');
        }

        if ($rol === 'cliente' && $proyectoExistente['estado_solicitud'] === 'aceptada') {
            return redirect()->to('/proyectos/mostrar/' . $id)->with('error', 'La solicitud ya está aprobada y no se puede modificar. Contacta al taller si necesitas un cambio.');
        }

        $idUsuarioAsignado = ($rol === 'admin')
            ? (int) $this->request->getPost('id_usuario')
            : (int) $proyectoExistente['id_usuario'];

        $idMotor        = (int) $this->request->getPost('id_motor');
        $componentesIds = array_filter(array_map('intval', (array) ($this->request->getPost('componentes') ?? [])));

        // Recalcular métricas de rendimiento y total
        $metricas = $this->calcularMetricas($idMotor, $componentesIds);

        $data = [
            'nombre_proyecto'  => trim($this->request->getPost('nombre_proyecto') ?? ''),
            'detalle_solicitud' => trim($this->request->getPost('detalle_solicitud') ?? ''),
            'id_usuario'       => $idUsuarioAsignado,
            'id_vehiculo'      => $this->request->getPost('id_vehiculo'),
            'id_motor'         => $idMotor,
            'fase_taller'      => trim($this->request->getPost('fase_taller') ?? 'Planificación'),
            'total_cotizacion' => $metricas['total_cotizacion'],
            'hp_estimado'      => $metricas['hp_estimado'],
            'torque_estimado'  => $metricas['torque_estimado'],
        ];

        if ($rol === 'cliente') {
            $data['estado_solicitud'] = 'pendiente';
            $data['fase_taller'] = 'Presupuesto Inicial';
        }

        if (! $this->validateData($data, $this->proyectoRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $actualizado = $model->update($id, $data);

        if (! $actualizado) {
            $db->transComplete();
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el proyecto.');
        }

        // Sincronizar componentes asignados
        $model->syncComponentes($id, $componentesIds);
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el proyecto y sus componentes.');
        }

        return redirect()->to('/proyectos/mostrar/' . $id)
            ->with('success', 'Proyecto y cotización actualizados correctamente.');
    }

    public function eliminar($id = null)
    {
        if (session()->get('rol') !== 'admin') {
            return redirect()->to('/proyectos')->with('error', 'Solo un administrador puede eliminar proyectos.');
        }

        $model = new ProyectoModel();
        $proyecto = $model->find($id);

        if ($proyecto === null) {
            return redirect()->to('/proyectos')->with('error', 'Proyecto no encontrado.');
        }

        $rol       = session()->get('rol') ?? 'cliente';
        $idUsuario = session()->get('id_usuario');

        if ($rol === 'cliente' && (int) $proyecto['id_usuario'] !== (int) $idUsuario) {
            return redirect()->to('/proyectos')->with('error', 'Acceso denegado: Solo puedes eliminar tus propios proyectos.');
        }

        // Limpiar pivot
        $model->syncComponentes($id, []);

        if (! $model->delete($id)) {
            return redirect()->to('/proyectos')->with('error', 'No se pudo eliminar el proyecto.');
        }

        return redirect()->to('/proyectos')->with('success', 'Proyecto eliminado del taller.');
    }

    public function actualizarFase($id = null)
    {
        $project = new ProyectoModel();
        $proyecto = $project->find($id);
        if (! $proyecto) {
            return redirect()->to('/proyectos')->with('error', 'No se encontró el proyecto.');
        }

        if ($proyecto['estado_solicitud'] !== 'aceptada') {
            return redirect()->to('/proyectos')->with('error', 'Solo se pueden actualizar las fases de proyectos aprobados.');
        }

        $phases = [
            'Presupuesto Inicial',
            'Desarme & Diagnóstico',
            'Mecanizado & Forjado',
            'Instalación de Piezas',
            'Dyno Tuning & Electrónica',
            'Pruebas de Calle / Pista',
            'Finalizado & Entregado',
        ];
        $phase = (string) $this->request->getPost('fase_taller');
        if (! in_array($phase, $phases, true)) {
            return redirect()->back()->with('error', 'La fase seleccionada no es válida.');
        }

        $project->update($id, ['fase_taller' => $phase]);
        return redirect()->to('/proyectos/mostrar/' . $id)->with('success', 'La fase del proyecto se actualizó.');
    }

    public function revisarSolicitud($id = null)
    {
        $estado = (string) $this->request->getPost('estado_solicitud');
        if (! in_array($estado, ['aceptada', 'rechazada'], true)) {
            return redirect()->back()->with('error', 'La decisión de solicitud no es válida.');
        }

        $model = new ProyectoModel();
        $proyecto = $model->find($id);
        if (! $proyecto) {
            return redirect()->to('/proyectos')->with('error', 'No se encontró el proyecto.');
        }
        if ($proyecto['estado_solicitud'] !== 'pendiente') {
            return redirect()->to('/proyectos/mostrar/' . $id)->with('error', 'Solo se pueden revisar solicitudes pendientes.');
        }

        $data = ['estado_solicitud' => $estado];
        $model->update($id, $data);

        $mensaje = $estado === 'aceptada'
            ? 'Solicitud aprobada. El pago se coordinará directamente en el taller antes de comenzar el trabajo.'
            : 'Solicitud rechazada.';
        return redirect()->to('/proyectos/mostrar/' . $id)->with('success', $mensaje);
    }

    private function puedeConfigurarProyectos(): bool
    {
        return in_array(session()->get('rol'), ['admin', 'cliente'], true);
    }

    /**
     * Motor de cálculo de rendimiento y cotización.
     *
     * @param int   $idMotor
     * @param array $componentesIds
     * @return array
     */
    private function calcularMetricas(int $idMotor, array $componentesIds): array
    {
        $motor = (new MotorModel())->find($idMotor);

        $hpStock     = (float) ($motor['hp_stock'] ?? 0);
        $torqueStock = (float) ($motor['torque_stock'] ?? 0);

        $totalCotizacion = 0.0;
        $sumaPctHp       = 0.0;
        $sumaPctTorque   = 0.0;

        if (! empty($componentesIds)) {
            $componentes = (new ComponenteModel())
                ->whereIn('id_componente', $componentesIds)
                ->findAll();

            foreach ($componentes as $comp) {
                $totalCotizacion += (float) ($comp['precio'] ?? 0);
                $sumaPctHp       += (float) ($comp['aporte_hp_pct'] ?? $comp['aporte_hp'] ?? 0);
                $sumaPctTorque   += (float) ($comp['aporte_torque_pct'] ?? $comp['aporte_torque'] ?? 0);
            }
        }

        // Cálculo de rendimiento: HP base * (1 + sum(%)/100)
        $hpFinal     = $hpStock * (1 + ($sumaPctHp / 100));
        $torqueFinal = $torqueStock * (1 + ($sumaPctTorque / 100));

        return [
            'total_cotizacion' => round($totalCotizacion, 2),
            'hp_estimado'      => round($hpFinal, 2),
            'torque_estimado'  => round($torqueFinal, 2),
            'suma_pct_hp'      => round($sumaPctHp, 2),
            'suma_pct_torque'  => round($sumaPctTorque, 2),
            'hp_stock'         => $hpStock,
            'torque_stock'     => $torqueStock,
        ];
    }

    /**
     * Catálogos para formularios de creación y edición.
     *
     * @return array
     */
    private function catalogos(): array
    {
        $usuariosQuery = (new UsuarioModel())
            ->select('id_usuario, nombre, email')
            ->where('rol', 'cliente')
            ->orderBy('nombre', 'ASC');

        if (session()->get('rol') === 'cliente') {
            $usuariosQuery->where('id_usuario', session()->get('id_usuario'));
        }

        return [
            'usuarios' => $usuariosQuery->findAll(),
            'vehiculos' => (new VehiculoModel())
                ->select('id_vehiculo, marca, modelo, anio, codigo_chasis')
                ->orderBy('marca', 'ASC')
                ->orderBy('modelo', 'ASC')
                ->findAll(),
            'motores' => (new MotorModel())
                ->select('id_motor, codigo_motor, hp_stock, torque_stock, limite_hp_bloque_stock')
                ->orderBy('codigo_motor', 'ASC')
                ->findAll(),
            'componentes' => (new ComponenteModel())
                ->select('id_componente, categoria, nombre, aporte_hp_pct, aporte_torque_pct, precio, stock')
                ->orderBy('categoria', 'ASC')
                ->orderBy('nombre', 'ASC')
                ->findAll(),
        ];
    }

    /**
     * Reglas de validación para el proyecto.
     *
     * @return array
     */
    private function proyectoRules(): array
    {
        return [
            'nombre_proyecto'  => 'required|max_length[150]',
            'detalle_solicitud' => 'permit_empty|max_length[2000]',
            'id_usuario'       => 'required|integer|is_not_unique[usuarios.id_usuario]',
            'id_vehiculo'      => 'required|integer|is_not_unique[vehiculos_base.id_vehiculo]',
            'id_motor'         => 'required|integer|is_not_unique[catalogo_motores.id_motor]',
            'fase_taller'      => 'required|max_length[50]',
            'total_cotizacion' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'hp_estimado'      => 'permit_empty|decimal|greater_than_equal_to[0]',
            'torque_estimado'  => 'permit_empty|decimal|greater_than_equal_to[0]',
        ];
    }
}
