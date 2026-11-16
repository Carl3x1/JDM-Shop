<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Editar Proyecto <?= esc($proyecto['nombre_proyecto']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0d0f;
            color: #f1f3f5;
            min-height: 100vh;
        }
        .form-card {
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
                <a href="<?= base_url('proyectos/mostrar/' . $proyecto['id_proyecto']) ?>" class="text-secondary text-decoration-none small d-inline-flex align-items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Volver a Ficha Técnica
                </a>
                <h1 class="h3 fw-bold mb-0 text-light">Editar Proyecto &amp; Cotización</h1>
                <p class="text-secondary small mb-0">Actualiza las piezas instaladas, el rendimiento y la fase de taller</p>
            </div>
            <a href="<?= base_url('proyectos/mostrar/' . $proyecto['id_proyecto']) ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>

        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis py-2 px-3 small rounded-3 mb-3">
                <?= esc($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis py-2 px-3 small rounded-3 mb-3">
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $message): ?>
                        <li><?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card form-card shadow-sm p-4">
            <div class="card-body p-0">
                <form id="projectRequestForm" data-client-request="<?= ($rolActual ?? '') === 'cliente' ? 'true' : 'false' ?>" action="<?= base_url('proyectos/actualizar/' . $proyecto['id_proyecto']) ?>" method="post">
                    <?= csrf_field() ?>
                    <?= $this->include('proyectos/_form') ?>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-25">
                        <a href="<?= base_url('proyectos/mostrar/' . $proyecto['id_proyecto']) ?>" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <?= ($rolActual ?? '') === 'cliente' ? 'Reenviar solicitud al taller' : 'Guardar cambios' ?>
                        
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('projectRequestForm')?.addEventListener('submit', event => {
            if (event.currentTarget.dataset.clientRequest === 'true'
                && !window.confirm('¿Confirmas que los datos y cambios solicitados son correctos? La solicitud volverá a quedar pendiente de revisión por el taller.')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
