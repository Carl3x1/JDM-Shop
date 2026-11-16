<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Catálogo de Motores</title>
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
                <span class="text-danger text-uppercase fw-semibold small">Catálogo de Tren Motriz</span>
                <h1 class="h3 fw-bold mb-0 text-light">Motores JDM</h1>
            </div>
            <?php if ($esAdmin): ?>
                <a href="<?= base_url('motores/crear') ?>" class="btn btn-danger fw-semibold d-inline-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                    + Nuevo Motor
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
                                <th scope="col" style="width: 25%;">Código Motor</th>
                                <th scope="col" style="width: 20%;">HP Stock</th>
                                <th scope="col" style="width: 20%;">Torque Stock</th>
                                <th scope="col" style="width: 15%;">Límite Bloque</th>
                                <th scope="col" class="text-end" style="width: 12%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($motores) && is_array($motores)): ?>
                                <?php foreach ($motores as $motor): ?>
                                    <tr>
                                        <td class="text-secondary small"><?= esc($motor['id_motor']) ?></td>
                                        <td>
                                            <span class="badge bg-dark border border-warning text-warning fs-6">
                                                <?= esc($motor['codigo_motor']) ?>
                                            </span>
                                        </td>
                                        <td><?= number_format((float) $motor['hp_stock'], 2) ?> HP</td>
                                        <td><?= number_format((float) $motor['torque_stock'], 2) ?> lb-ft</td>
                                        <td>
                                            <span class="text-info"><?= number_format((float) $motor['limite_hp_bloque_stock'], 0) ?> HP</span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= base_url('motores/mostrar/' . $motor['id_motor']) ?>" class="btn btn-sm btn-outline-info">
                                                Ver
                                            </a>
                                            <?php if ($esAdmin): ?>
                                                <a href="<?= base_url('motores/actualizar/' . $motor['id_motor']) ?>" class="btn btn-sm btn-outline-primary">
                                                    Editar
                                                </a>
                                                <form action="<?= base_url('motores/eliminar/' . $motor['id_motor']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este motor?');">
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
                                    <td colspan="6" class="text-center py-4 text-secondary">No hay motores registrados en el catálogo.</td>
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
