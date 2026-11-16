<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Detalle de Componente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0b0d0f; color: #f1f3f5; min-height: 100vh; }
        .detail-card { background: #14171a; border: 1px solid #232830; border-radius: 10px; }
        .stat-box { background: #0d0f12; border: 1px solid #282e36; border-radius: 8px; padding: 1rem; }
    </style>
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>

    <main class="container py-4">
        <?php $esAdmin = (session()->get('rol') === 'admin'); ?>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="<?= base_url('componentes') ?>" class="text-secondary text-decoration-none small d-inline-flex align-items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Volver al Catálogo
                </a>
                <h1 class="h3 fw-bold mb-0 text-light d-flex align-items-center gap-2">
                    <?= esc($componente['nombre']) ?>
                    <span class="badge bg-info text-dark fs-6"><?= esc($componente['categoria']) ?></span>
                </h1>
            </div>
            <div class="d-flex gap-2">
                <?php if ($esAdmin): ?>
                    <a href="<?= base_url('componentes/actualizar/' . $componente['id_componente']) ?>" class="btn btn-primary d-inline-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil me-1" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                        </svg>
                        Editar Componente
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <!-- Datos principales -->
            <div class="col-12 col-md-7">
                <div class="card detail-card shadow-sm p-4 h-100">
                    <h2 class="h5 fw-bold text-light border-bottom border-secondary pb-2 mb-3">Especificaciones</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-secondary small text-uppercase">ID</dt>
                        <dd class="col-sm-7"><?= esc($componente['id_componente']) ?></dd>
                        <dt class="col-sm-5 text-secondary small text-uppercase">Categoría</dt>
                        <dd class="col-sm-7"><span class="badge bg-info text-dark"><?= esc($componente['categoria']) ?></span></dd>
                        <dt class="col-sm-5 text-secondary small text-uppercase">Nombre</dt>
                        <dd class="col-sm-7 fw-semibold"><?= esc($componente['nombre']) ?></dd>
                        <dt class="col-sm-5 text-secondary small text-uppercase">Stock disponible</dt>
                        <dd class="col-sm-7"><?= esc($componente['stock']) ?> unidades</dd>
                        <?php if (!empty($componente['descripcion'])): ?>
                        <dt class="col-sm-5 text-secondary small text-uppercase">Descripción</dt>
                        <dd class="col-sm-7"><?= esc($componente['descripcion']) ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <!-- Métricas de rendimiento y precio -->
            <div class="col-12 col-md-5">
                <div class="d-flex flex-column gap-3 h-100">
                    <div class="stat-box text-center">
                        <span class="text-danger text-uppercase fw-bold small d-block mb-1">Aporte HP</span>
                        <span class="display-6 fw-bold text-danger">+<?= esc($componente['aporte_hp_pct']) ?>%</span>
                    </div>
                    <div class="stat-box text-center">
                        <span class="text-warning text-uppercase fw-bold small d-block mb-1">Aporte Torque</span>
                        <span class="display-6 fw-bold text-warning">+<?= esc($componente['aporte_torque_pct']) ?>%</span>
                    </div>
                    <div class="stat-box text-center">
                        <span class="text-success text-uppercase fw-bold small d-block mb-1">Precio</span>
                        <span class="display-6 fw-bold text-success font-monospace">$<?= esc(number_format((float) $componente['precio'], 2)) ?></span>
                        <span class="text-secondary small">USD</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
