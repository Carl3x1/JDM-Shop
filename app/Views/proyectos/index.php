<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Proyectos de Taller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0d0f;
            color: #f1f3f5;
            min-height: 100vh;
        }
        .proyectos-card {
            background: #14171a;
            border: 1px solid #232830;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>

    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <span class="text-danger text-uppercase fw-semibold small">Gestión de Taller &amp; Modificaciones</span>
                <h1 class="h3 fw-bold mb-0 text-light">Proyectos de Taller</h1>
            </div>
            <?php if (session()->get('rol') !== 'trabajador'): ?>
            <a href="<?= base_url('proyectos/crear') ?>" class="btn btn-danger fw-semibold d-inline-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                </svg>
                Nuevo Proyecto &amp; Cotización
            </a>
            <?php endif; ?>
        </div>

        <?php if ($success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 bg-success-subtle text-success-emphasis py-2 px-3 small rounded-3 mb-3">
                <?= esc($success) ?>
            </div>
        <?php endif; ?>
        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis py-2 px-3 small rounded-3 mb-3">
                <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <div class="card proyectos-card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase">
                                <th scope="col" style="width: 5%;">#</th>
                                <th scope="col" style="width: 22%;">Proyecto</th>
                                <th scope="col" style="width: 16%;">Cliente</th>
                                <th scope="col" style="width: 18%;">Vehículo / Motor</th>
                                <th scope="col" style="width: 14%;">Potencia Est.</th>
                                <th scope="col" style="width: 10%;">Fase</th>
                                <th scope="col" style="width: 16%;">Solicitud</th>
                                <th scope="col" style="width: 15%;">Total Cotizado</th>
                                <th scope="col" class="text-end" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($proyectos) && is_array($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <tr>
                                        <td class="text-secondary small"><?= esc($proyecto['id_proyecto']) ?></td>
                                        <td>
                                            <a href="<?= base_url('proyectos/mostrar/' . $proyecto['id_proyecto']) ?>" class="fw-bold text-light text-decoration-none">
                                                <?= esc($proyecto['nombre_proyecto']) ?>
                                            </a>
                                        </td>
                                        <td class="small">
                                            <span class="d-block text-light"><?= esc($proyecto['usuario_nombre'] ?? 'N/D') ?></span>
                                            <?php if (session()->get('rol') !== 'trabajador'): ?><span class="text-secondary" style="font-size: 0.72rem;"><?= esc($proyecto['usuario_email'] ?? '') ?></span><?php endif; ?>
                                        </td>
                                        <td class="small">
                                            <span class="d-block text-light">
                                                <?= esc($proyecto['vehiculo_marca'] ?? '') ?> <?= esc($proyecto['vehiculo_modelo'] ?? '') ?>
                                                <?php if (! empty($proyecto['vehiculo_chasis'])): ?>
                                                    <span class="badge bg-secondary" style="font-size: 0.65rem;"><?= esc($proyecto['vehiculo_chasis']) ?></span>
                                                <?php endif; ?>
                                            </span>
                                            <span class="badge bg-dark border border-warning text-warning" style="font-size: 0.68rem;">
                                                <?= esc($proyecto['codigo_motor'] ?? 'N/D') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger d-block small">
                                                <?= number_format((float) ($proyecto['hp_estimado'] ?? 0), 1) ?> HP
                                            </span>
                                            <span class="text-warning small" style="font-size: 0.72rem;">
                                                <?= number_format((float) ($proyecto['torque_estimado'] ?? 0), 1) ?> lb-ft
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark" style="font-size: 0.72rem;">
                                                <?= esc($proyecto['fase_taller']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoSolicitud = $proyecto['estado_solicitud'] ?? 'aceptada';
                                            $estadosEtiqueta = ['pendiente' => 'Pendiente', 'aceptada' => 'Aprobada', 'rechazada' => 'Requiere cambios'];
                                            ?>
                                            <span class="badge <?= $estadoSolicitud === 'pendiente' ? 'text-bg-warning' : ($estadoSolicitud === 'aceptada' ? 'text-bg-success' : 'text-bg-danger') ?>">
                                                <?= esc($estadosEtiqueta[$estadoSolicitud] ?? $estadoSolicitud) ?>
                                            </span>
                                            <?php if (session()->get('rol') === 'admin' && $estadoSolicitud === 'pendiente'): ?>
                                                <div class="d-flex gap-1 mt-2">
                                                    <form action="<?= base_url('proyectos/solicitud/' . $proyecto['id_proyecto']) ?>" method="post">
                                                        <?= csrf_field() ?><input type="hidden" name="estado_solicitud" value="aceptada">
                                                        <button class="btn btn-sm btn-danger" type="submit">Aprobar</button>
                                                    </form>
                                                    <form action="<?= base_url('proyectos/solicitud/' . $proyecto['id_proyecto']) ?>" method="post">
                                                        <?= csrf_field() ?><input type="hidden" name="estado_solicitud" value="rechazada">
                                                        <button class="btn btn-sm btn-outline-danger" type="submit">Rechazar</button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-monospace text-success fw-bold">
                                            $<?= number_format((float) $proyecto['total_cotizacion'], 2) ?>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= base_url('proyectos/mostrar/' . $proyecto['id_proyecto']) ?>" class="btn btn-sm btn-outline-info" title="Ver Ficha Técnica">
                                                Ver
                                            </a>
                                            <?php if (session()->get('rol') === 'admin' || (session()->get('rol') === 'cliente' && $estadoSolicitud !== 'aceptada')): ?>
                                            <a href="<?= base_url('proyectos/editar/' . $proyecto['id_proyecto']) ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                Editar
                                            </a>
                                            <?php if (session()->get('rol') === 'admin'): ?>
                                            <form action="<?= base_url('proyectos/eliminar/' . $proyecto['id_proyecto']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este proyecto del taller?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    &times;
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-secondary">
                                        No hay proyectos registrados en el taller. Haz clic en "Nuevo Proyecto &amp; Cotización" para comenzar.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
