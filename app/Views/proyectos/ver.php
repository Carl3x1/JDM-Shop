<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Ficha Técnica <?= esc($proyecto['nombre_proyecto']) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0d0f;
            color: #f1f3f5;
            min-height: 100vh;
        }

        .sheet-card {
            background: #14171a;
            border: 1px solid #232830;
            border-radius: 10px;
        }

        .stat-box {
            background: #0d0f12;
            border: 1px solid #282e36;
            border-radius: 8px;
            padding: 1rem;
        }

        .dyno-badge {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>

    <main class="container py-4">
        <!-- Encabezado de Navegación y Acciones -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="<?= base_url('proyectos') ?>" class="text-secondary text-decoration-none small d-inline-flex align-items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Volver a Proyectos
                </a>
                <h1 class="h3 fw-bold mb-0 text-light d-flex align-items-center gap-2">
                    <?= esc($proyecto['nombre_proyecto']) ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 fs-6">
                        #PROY-<?= esc($proyecto['id_proyecto']) ?>
                    </span>
                </h1>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-info text-dark px-3 py-2 fs-6">
                    <?= esc($proyecto['fase_taller']) ?>
                </span>
                <?php if (session()->get('rol') === 'admin' || (session()->get('rol') === 'cliente' && $proyecto['estado_solicitud'] !== 'aceptada')): ?>
                <a href="<?= base_url('proyectos/editar/' . $proyecto['id_proyecto']) ?>" class="btn btn-primary d-inline-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil me-1" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                    </svg>
                    Modificar Configuración
                </a>
                <?php endif; ?>
            </div>
        </div>

        <?php
        $estadoSolicitud = $proyecto['estado_solicitud'] ?? 'aceptada';
        $etiquetasSolicitud = ['pendiente' => 'Pendiente de revisión', 'aceptada' => 'Aprobada', 'rechazada' => 'Requiere cambios'];
        ?>
        <section class="card sheet-card p-3 p-md-4 mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="small text-secondary text-uppercase fw-semibold">Solicitud de proyecto</div>
                    <div class="h5 mb-0 mt-1"><?= esc($etiquetasSolicitud[$estadoSolicitud] ?? $estadoSolicitud) ?></div>
                    <?php if ($estadoSolicitud === 'pendiente' && session()->get('rol') === 'cliente'): ?>
                        <p class="small text-secondary mb-0 mt-1">El taller revisará la información antes de comenzar el trabajo.</p>
                    <?php elseif ($estadoSolicitud === 'rechazada' && session()->get('rol') === 'cliente'): ?>
                        <p class="small text-secondary mb-0 mt-1">Puedes editarla y enviarla nuevamente para revisión.</p>
                    <?php endif; ?>
                </div>
                <?php if (session()->get('rol') === 'admin' && $estadoSolicitud === 'pendiente'): ?>
                    <div class="d-flex flex-wrap gap-2">
                        <form action="<?= base_url('proyectos/solicitud/' . $proyecto['id_proyecto']) ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="estado_solicitud" value="aceptada">
                            <button class="btn btn-sm btn-danger" type="submit">Aprobar solicitud</button>
                        </form>
                        <form action="<?= base_url('proyectos/solicitud/' . $proyecto['id_proyecto']) ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="estado_solicitud" value="rechazada">
                            <button class="btn btn-sm btn-outline-danger" type="submit">Rechazar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($estadoSolicitud === 'aceptada'): ?>
                <hr class="border-secondary my-3">
                <h2 class="h6 fw-bold mb-1">Pago</h2>
                <p class="small text-secondary mb-0">El método y el pago se coordinan directamente en el taller antes de iniciar el trabajo.</p>
            <?php endif; ?>
        </section>

        <?php if (! empty($proyecto['detalle_solicitud'])): ?>
            <section class="card sheet-card p-3 p-md-4 mb-4">
                <h2 class="h6 fw-bold mb-2">Objetivo y cambios solicitados</h2>
                <p class="mb-0 text-secondary" style="white-space: pre-line"><?= esc($proyecto['detalle_solicitud']) ?></p>
            </section>
        <?php endif; ?>

        <?php if (in_array(session()->get('rol'), ['admin', 'trabajador'], true)): ?>
            <?php $fasesTaller = ['Presupuesto Inicial', 'Desarme & Diagnóstico', 'Mecanizado & Forjado', 'Instalación de Piezas', 'Dyno Tuning & Electrónica', 'Pruebas de Calle / Pista', 'Finalizado & Entregado']; ?>
            <form class="d-flex align-items-center justify-content-end gap-2 flex-wrap mb-4" action="<?= base_url('proyectos/fase/' . $proyecto['id_proyecto']) ?>" method="post">
                <?= csrf_field() ?>
                <label for="fase_taller" class="form-label mb-0 small text-secondary">Actualizar fase</label>
                <select class="form-select form-select-sm bg-dark text-light border-secondary" id="fase_taller" name="fase_taller" style="width:auto">
                    <?php foreach ($fasesTaller as $fase): ?><option value="<?= esc($fase) ?>" <?= $proyecto['fase_taller'] === $fase ? 'selected' : '' ?>><?= esc($fase) ?></option><?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-danger" type="submit">Guardar fase</button>
            </form>
        <?php endif; ?>

        <?php if ($success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 bg-success-subtle text-success-emphasis py-2 px-3 small rounded-3 mb-4">
                <?= esc($success) ?>
            </div>
        <?php endif; ?>

        <!-- RESUMEN DE RENDIMIENTO DYNO (MÉTRICAS CLAVE) -->
        <section class="card sheet-card shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-2 mb-3">
                <h2 class="h5 fw-bold text-light mb-0 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cpu text-danger me-2" viewBox="0 0 16 16">
                        <path d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2A2.5 2.5 0 0 1 14 4.5h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14A2.5 2.5 0 0 1 11.5 14v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14A2.5 2.5 0 0 1 2 11.5H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2A2.5 2.5 0 0 1 4.5 2V.5A.5.5 0 0 1 5 0m-.5 3A1.5 1.5 0 0 0 3 4.5v7A1.5 1.5 0 0 0 4.5 13h7a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 11.5 3zM5 6.5A1.5 1.5 0 0 1 6.5 5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5zM6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                    </svg>
                    Estimación de Rendimiento Dyno (Tuning Stage)
                </h2>
                <span class="badge text-bg-danger text-uppercase dyno-badge">Cálculo en Banco de Pruebas</span>
            </div>

            <div class="row g-3">
                <!-- HP Final -->
                <div class="col-12 col-md-4">
                    <div class="stat-box text-center h-100">
                        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1">Potencia Final Estimada</span>
                        <p class="display-6 fw-bold text-danger mb-0">
                            <?= number_format((float) $metricas['hp_final'], 2) ?> <span class="fs-5">HP</span>
                        </p>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary" style="font-size: 0.72rem;">
                                Stock: <?= number_format((float) $metricas['hp_stock'], 2) ?> HP
                            </span>
                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25" style="font-size: 0.72rem;">
                                +<?= number_format((float) $metricas['ganancia_hp'], 2) ?> HP (+<?= $metricas['suma_pct_hp'] ?>%)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Torque Final -->
                <div class="col-12 col-md-4">
                    <div class="stat-box text-center h-100">
                        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1">Torque Final Estimado</span>
                        <p class="display-6 fw-bold text-warning mb-0">
                            <?= number_format((float) $metricas['torque_final'], 2) ?> <span class="fs-5">lb-ft</span>
                        </p>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary" style="font-size: 0.72rem;">
                                Stock: <?= number_format((float) $metricas['torque_stock'], 2) ?> lb-ft
                            </span>
                            <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25" style="font-size: 0.72rem;">
                                +<?= number_format((float) $metricas['ganancia_torque'], 2) ?> lb-ft (+<?= $metricas['suma_pct_torque'] ?>%)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Límite de Bloque & Margen -->
                <div class="col-12 col-md-4">
                    <div class="stat-box text-center h-100 d-flex flex-column justify-content-center">
                        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1">Límite de Bloque Stock</span>
                        <p class="h3 fw-bold <?= $metricas['alerta_bloque'] ? 'text-danger' : 'text-info' ?> mb-1">
                            <?= number_format((float) $metricas['limite_hp'], 0) ?> HP
                        </p>
                        <div class="progress bg-dark border border-secondary border-opacity-50 my-2" style="height: 10px;">
                            <div
                                class="progress-bar <?= $metricas['alerta_bloque'] ? 'bg-danger' : 'bg-info' ?>"
                                role="progressbar"
                                style="width: <?= min((float) $metricas['pct_limite_bloque'], 100) ?>%;"
                                aria-valuenow="<?= $metricas['pct_limite_bloque'] ?>"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <span class="small <?= $metricas['alerta_bloque'] ? 'text-danger fw-bold' : 'text-secondary' ?>">
                            <?= $metricas['alerta_bloque'] ? '⚠️ Alerta: Supera límite del bloque stock (' . $metricas['pct_limite_bloque'] . '%)' : 'Dentro de rango de fábrica (' . $metricas['pct_limite_bloque'] . '% del límite)' ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ESPECIFICACIONES DE VEHÍCULO, MOTOR Y CLIENTE -->
        <div class="row g-4 mb-4">
            <!-- Vehículo Base -->
            <div class="col-12 col-md-4">
                <section class="card sheet-card h-100 shadow-sm p-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger me-2" style="font-size: 0.65rem;">CHASIS</span>
                        <h3 class="h6 fw-bold text-light mb-0">Vehículo Base</h3>
                    </div>
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-secondary">Marca / Modelo:</dt>
                        <dd class="col-7 text-light fw-medium mb-1">
                            <?= esc($proyecto['vehiculo_marca'] ?? 'N/D') ?> <?= esc($proyecto['vehiculo_modelo'] ?? '') ?>
                        </dd>
                        <dt class="col-5 text-secondary">Año:</dt>
                        <dd class="col-7 text-light mb-1"><?= esc($proyecto['vehiculo_anio'] ?? 'N/D') ?></dd>
                        <dt class="col-5 text-secondary">Código Chasis:</dt>
                        <dd class="col-7 mb-0">
                            <span class="badge bg-dark border border-secondary text-light">
                                <?= esc($proyecto['vehiculo_chasis'] ?? 'N/D') ?>
                            </span>
                        </dd>
                    </dl>
                </section>
            </div>

            <!-- Motor Base -->
            <div class="col-12 col-md-4">
                <section class="card sheet-card h-100 shadow-sm p-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-warning text-dark me-2" style="font-size: 0.65rem;">ENGINE</span>
                        <h3 class="h6 fw-bold text-light mb-0">Motor Seleccionado</h3>
                    </div>
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-secondary">Código Motor:</dt>
                        <dd class="col-7 text-light fw-medium mb-1">
                            <span class="badge bg-dark border border-warning text-warning">
                                <?= esc($proyecto['codigo_motor'] ?? 'N/D') ?>
                            </span>
                        </dd>
                        <dt class="col-5 text-secondary">Potencia Stock:</dt>
                        <dd class="col-7 text-light mb-1"><?= number_format((float) ($proyecto['hp_stock'] ?? 0), 2) ?> HP</dd>
                        <dt class="col-5 text-secondary">Torque Stock:</dt>
                        <dd class="col-7 text-light mb-1"><?= number_format((float) ($proyecto['torque_stock'] ?? 0), 2) ?> lb-ft</dd>
                        <dt class="col-5 text-secondary">Límite Bloque:</dt>
                        <dd class="col-7 text-light mb-0"><?= number_format((float) ($proyecto['limite_hp_bloque_stock'] ?? 0), 2) ?> HP</dd>
                    </dl>
                </section>
            </div>

            <!-- Propietario / Cliente -->
            <div class="col-12 col-md-4">
                <section class="card sheet-card h-100 shadow-sm p-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-info text-dark me-2" style="font-size: 0.65rem;">CLIENTE</span>
                        <h3 class="h6 fw-bold text-light mb-0">Propietario</h3>
                    </div>
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-secondary">Nombre:</dt>
                        <dd class="col-7 text-light fw-medium mb-1"><?= esc($proyecto['usuario_nombre'] ?? 'N/D') ?></dd>
                        <?php if (session()->get('rol') !== 'trabajador'): ?>
                        <dt class="col-5 text-secondary">Email:</dt>
                        <dd class="col-7 text-light mb-1"><?= esc($proyecto['usuario_email'] ?? 'N/D') ?></dd>
                        <dt class="col-5 text-secondary">DPI:</dt>
                        <dd class="col-7 text-light mb-1"><?= esc($proyecto['usuario_dpi'] ?: 'Sin registrar') ?></dd>
                        <dt class="col-5 text-secondary">NIT:</dt>
                        <dd class="col-7 text-light mb-1"><?= esc($proyecto['usuario_nit'] ?: 'Sin registrar') ?></dd>
                        <dt class="col-5 text-secondary">Teléfono:</dt>
                        <dd class="col-7 text-light mb-1"><?= esc($proyecto['usuario_telefono'] ?: 'Sin registrar') ?></dd>
                        <dt class="col-5 text-secondary">Dirección:</dt>
                        <dd class="col-7 text-light mb-1"><?= esc($proyecto['usuario_direccion'] ?: 'Sin registrar') ?></dd>
                        <?php endif; ?>
                        <dt class="col-5 text-secondary">Rol:</dt>
                        <dd class="col-7 mb-0">
                            <span class="badge bg-secondary text-uppercase" style="font-size: 0.65rem;">
                                <?= esc($proyecto['usuario_rol'] ?? 'cliente') ?>
                            </span>
                        </dd>
                    </dl>
                </section>
            </div>
        </div>

        <!-- COMPONENTES INSTALADOS / COTIZADOR DETALLADO -->
        <section class="card sheet-card shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-2 mb-3">
                <h3 class="h5 fw-bold text-light mb-0">Componentes de Modificación Instalados</h3>
                <span class="badge bg-secondary"><?= count($componentes) ?> piezas en cotización</span>
            </div>

            <?php if (empty($componentes)): ?>
                <div class="text-center py-4 text-secondary">
                    <p class="mb-2">Este proyecto aún no tiene componentes de modificación asignados.</p>
                    <a href="<?= base_url('proyectos/editar/' . $proyecto['id_proyecto']) ?>" class="btn btn-sm btn-outline-light">
                        + Agregar Componentes
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase">
                                <th scope="col" style="width: 5%;">#</th>
                                <th scope="col" style="width: 20%;">Categoría</th>
                                <th scope="col" style="width: 35%;">Componente / Pieza</th>
                                <th scope="col" class="text-center" style="width: 12%;">Aporte HP</th>
                                <th scope="col" class="text-center" style="width: 12%;">Aporte Torque</th>
                                <th scope="col" class="text-end" style="width: 16%;">Precio (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; foreach ($componentes as $comp): ?>
                                <tr>
                                    <td class="text-secondary small"><?= $index++ ?></td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase" style="font-size: 0.68rem;">
                                            <?= esc($comp['categoria'] ?? 'General') ?>
                                        </span>
                                    </td>
                                    <td class="fw-medium text-light"><?= esc($comp['nombre']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25">
                                            +<?= number_format((float) ($comp['aporte_hp_pct'] ?? $comp['aporte_hp'] ?? 0), 2) ?>%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25">
                                            +<?= number_format((float) ($comp['aporte_torque_pct'] ?? $comp['aporte_torque'] ?? 0), 2) ?>%
                                        </span>
                                    </td>
                                    <td class="text-end font-monospace text-light">
                                        $<?= number_format((float) $comp['precio'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="border-top border-secondary fw-semibold">
                                <td colspan="3" class="text-uppercase small text-secondary">Totales Acumulados</td>
                                <td class="text-center text-danger">
                                    +<?= $metricas['suma_pct_hp'] ?>% HP
                                </td>
                                <td class="text-center text-warning">
                                    +<?= $metricas['suma_pct_torque'] ?>% TQ
                                </td>
                                <td class="text-end text-success fs-6 font-monospace">
                                    $<?= number_format((float) $metricas['subtotal_piezas'], 2) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- TARJETA TOTAL DE COTIZACIÓN -->
        <section class="card bg-black border border-secondary shadow-sm p-4">
            <div class="row align-items-center">
                <div class="col-12 col-md-7 mb-3 mb-md-0">
                    <span class="text-secondary small text-uppercase fw-semibold d-block">Resumen Económico de Taller</span>
                    <h3 class="h4 fw-bold text-light mb-1">Presupuesto Total de Modificación</h3>
                    <p class="text-secondary small mb-0">
                        Incluye todos los componentes de inducción, escape, gestión electrónica y accesorios seleccionados para el proyecto.
                    </p>
                </div>
                <div class="col-12 col-md-5 text-md-end">
                    <span class="text-secondary small text-uppercase d-block mb-1">Total Cotización</span>
                    <p class="display-5 fw-bold text-success font-monospace mb-0">
                        $<?= number_format((float) $proyecto['total_cotizacion'], 2) ?> <span class="fs-6 text-secondary">USD</span>
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
