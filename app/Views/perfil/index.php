<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi cuenta | JDM Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>
    <main class="container py-5" style="max-width:900px">
        <div class="mb-4">
            <span class="text-uppercase small fw-semibold" style="color:#bd1734">Cuenta JDM</span>
            <h1 class="h2 fw-bold mt-1"><?= session()->get('rol') === 'cliente' ? 'Mi perfil de cliente' : 'Mi cuenta' ?></h1>
            <?php if (session()->get('rol') === 'cliente'): ?>
                <p class="text-secondary">Tus datos de contacto se usan para gestionar las solicitudes del taller.</p>
            <?php else: ?>
                <p class="text-secondary">Las cuentas administrativas y de trabajadores no requieren datos de identificación de clientes.</p>
            <?php endif; ?>
        </div>

        <?php if ($success = session()->getFlashdata('success')): ?><div class="alert alert-success" role="status"><?= esc($success) ?></div><?php endif; ?>
        <?php if ($error = session()->getFlashdata('error')): ?><div class="alert alert-danger" role="alert"><?= esc($error) ?></div><?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

        <section class="card bg-secondary text-white border-secondary shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="mb-4 pb-3 border-bottom border-light border-opacity-25">
                    <h2 class="h5 mb-1"><?= esc($usuario['nombre']) ?></h2>
                    <div class="small text-white-50"><?= esc($usuario['email']) ?></div>
                    <span class="badge bg-dark text-uppercase mt-2"><?= esc($usuario['rol']) ?></span>
                </div>
                <?php if (session()->get('rol') === 'cliente'): ?>
                    <form action="<?= base_url('perfil') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-12 col-md-6"><label for="dpi" class="form-label">DPI</label><input class="form-control bg-dark text-light border-secondary" id="dpi" name="dpi" value="<?= esc(old('dpi', $usuario['dpi'] ?? '')) ?>" maxlength="20" autocomplete="off" placeholder="Número de identificación"></div>
                            <div class="col-12 col-md-6"><label for="nit" class="form-label">NIT</label><input class="form-control bg-dark text-light border-secondary" id="nit" name="nit" value="<?= esc(old('nit', $usuario['nit'] ?? '')) ?>" maxlength="20" autocomplete="off" placeholder="Número de identificación tributaria"></div>
                            <div class="col-12 col-md-6"><label for="telefono" class="form-label">Teléfono</label><input class="form-control bg-dark text-light border-secondary" id="telefono" name="telefono" type="tel" value="<?= esc(old('telefono', $usuario['telefono'] ?? '')) ?>" maxlength="30" autocomplete="tel" placeholder="Teléfono de contacto"></div>
                            <div class="col-12"><label for="direccion" class="form-label">Dirección</label><input class="form-control bg-dark text-light border-secondary" id="direccion" name="direccion" value="<?= esc(old('direccion', $usuario['direccion'] ?? '')) ?>" maxlength="255" autocomplete="street-address" placeholder="Dirección de contacto"></div>
                        </div>
                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-danger px-4">Guardar datos</button>
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-light">Cancelar</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
