<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Shop - Detalle de Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detalle del Producto</h2>
            <a href="<?= base_url('productos') ?>" class="btn btn-outline-light">Volver</a>
        </div>

        <div class="card bg-secondary text-white shadow">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">ID</dt>
                    <dd class="col-sm-8"><?= esc($producto['id_producto']) ?></dd>
                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8"><?= esc($producto['nombre']) ?></dd>
                    <dt class="col-sm-4">Categoría</dt>
                    <dd class="col-sm-8"><?= esc($producto['categoria']) ?></dd>
                    <dt class="col-sm-4">Precio</dt>
                    <dd class="col-sm-8">$<?= esc(number_format((float) $producto['precio'], 2)) ?></dd>
                    <dt class="col-sm-4">Stock</dt>
                    <dd class="col-sm-8"><?= esc($producto['stock']) ?></dd>
                    <dt class="col-sm-4">Aporte HP</dt>
                    <dd class="col-sm-8"><?= esc($producto['aporte_hp']) ?></dd>
                    <dt class="col-sm-4">Aporte Torque</dt>
                    <dd class="col-sm-8"><?= esc($producto['aporte_torque']) ?></dd>
                    <?php if (! empty($producto['descripcion'])): ?><dt class="col-sm-4">Descripción</dt><dd class="col-sm-8" style="white-space:pre-line"><?= esc($producto['descripcion']) ?></dd><?php endif; ?>
                </dl>
                <a href="<?= base_url('productos/actualizar/' . $producto['id_producto']) ?>" class="btn btn-primary mt-3">Editar</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
