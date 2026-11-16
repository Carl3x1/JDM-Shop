<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Vehículos Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0d0f;
            color: #f1f3f5;
            min-height: 100vh;
        }
        .catalog-card {
            background: #14171a;
            border: 1px solid #232830;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>

    <main class="container py-4">
        <?php $esAdmin = (session()->get('rol') === 'admin'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <span class="text-danger text-uppercase fw-semibold small">Catálogo de Plataformas</span>
                <h1 class="h3 fw-bold mb-0 text-light">Vehículos Base</h1>
            </div>
            <?php if ($esAdmin): ?>
                <a href="<?= base_url('vehiculos/crear') ?>" class="btn btn-danger fw-semibold d-inline-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                    + Nuevo Vehículo
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

        <div class="card catalog-card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase">
                                <th scope="col" style="width: 8%;">ID</th>
                                <th scope="col" style="width: 25%;">Marca</th>
                                <th scope="col" style="width: 25%;">Modelo</th>
                                <th scope="col" style="width: 15%;">Año</th>
                                <th scope="col" style="width: 15%;">Código Chasis</th>
                                <th scope="col" class="text-end" style="width: 12%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($vehiculos) && is_array($vehiculos)): ?>
                                <?php foreach ($vehiculos as $vehiculo): ?>
                                    <tr>
                                        <td class="text-secondary small"><?= esc($vehiculo['id_vehiculo']) ?></td>
                                        <td class="fw-semibold text-light"><?= esc($vehiculo['marca']) ?></td>
                                        <td><?= esc($vehiculo['modelo']) ?></td>
                                        <td><?= esc($vehiculo['anio']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= esc($vehiculo['codigo_chasis']) ?></span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= base_url('vehiculos/mostrar/' . $vehiculo['id_vehiculo']) ?>" class="btn btn-sm btn-outline-info">
                                                Ver
                                            </a>
                                            <?php if ($esAdmin): ?>
                                                <a href="<?= base_url('vehiculos/actualizar/' . $vehiculo['id_vehiculo']) ?>" class="btn btn-sm btn-outline-primary">
                                                    Editar
                                                </a>
                                                <form action="<?= base_url('vehiculos/eliminar/' . $vehiculo['id_vehiculo']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este vehículo?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-secondary">No hay vehículos registrados en el catálogo.</td>
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
