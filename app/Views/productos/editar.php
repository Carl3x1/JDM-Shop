<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Producto</h2>
            <a href="<?= base_url('productos') ?>" class="btn btn-outline-light">Volver</a>
        </div>

        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc($error) ?></div>
        <?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $message): ?>
                        <li><?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card bg-secondary text-white shadow">
            <div class="card-body">
                <form action="<?= base_url('productos/actualizar/' . $producto['id_producto']) ?>" method="post">
                    <?= csrf_field() ?>
                    <?= $this->include('productos/_form') ?>
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                    <a href="<?= base_url('productos') ?>" class="btn btn-outline-light">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
