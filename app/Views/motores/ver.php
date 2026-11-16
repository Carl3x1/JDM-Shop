<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Detalle de Motor <?= esc($motor['codigo_motor']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0d0f;
            color: #f1f3f5;
            min-height: 100vh;
        }
        .detail-card {
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
                <a href="<?= base_url('motores') ?>" class="text-secondary text-decoration-none small d-inline-flex align-items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Volver a Motores
                </a>
                <h1 class="h3 fw-bold mb-0 text-light"><?= esc($motor['codigo_motor']) ?></h1>
            </div>
            <div>
                <?php if ($esAdmin): ?>
                    <a href="<?= base_url('motores/actualizar/' . $motor['id_motor']) ?>" class="btn btn-primary">
                        Editar Motor
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card detail-card shadow-sm p-4">
            <div class="card-body p-0">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-secondary text-uppercase small">ID Motor</dt>
                    <dd class="col-sm-9 text-light"><?= esc($motor['id_motor']) ?></dd>

                    <dt class="col-sm-3 text-secondary text-uppercase small">Código Motor</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-dark border border-warning text-warning fs-6">
                            <?= esc($motor['codigo_motor']) ?>
                        </span>
                    </dd>

                    <dt class="col-sm-3 text-secondary text-uppercase small">Potencia Stock</dt>
                    <dd class="col-sm-9 text-light fw-bold"><?= number_format((float) $motor['hp_stock'], 2) ?> HP</dd>

                    <dt class="col-sm-3 text-secondary text-uppercase small">Torque Stock</dt>
                    <dd class="col-sm-9 text-light"><?= number_format((float) $motor['torque_stock'], 2) ?> lb-ft</dd>

                    <dt class="col-sm-3 text-secondary text-uppercase small">Límite Bloque Stock</dt>
                    <dd class="col-sm-9 text-info fw-bold"><?= number_format((float) $motor['limite_hp_bloque_stock'], 2) ?> HP</dd>
                    <?php if (! empty($motor['descripcion'])): ?>
                    <dt class="col-sm-3 text-secondary text-uppercase small">Descripción</dt>
                    <dd class="col-sm-9" style="white-space:pre-line"><?= esc($motor['descripcion']) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
