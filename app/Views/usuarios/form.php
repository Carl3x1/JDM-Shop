<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $esEdicion ? 'Editar cuenta' : 'Crear cuenta' ?> | JDM Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-form-card { background:#f6f7f4; border:1px solid #c9d1cc; border-radius:14px; box-shadow:0 12px 28px rgba(28,38,43,.1); }
        html[data-theme="dark"] .user-form-card { background:#292f34; border-color:#3b4349; }
    </style>
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>
    <main class="container py-4 py-lg-5" style="max-width:940px">
        <div class="mb-4">
            <span class="small fw-bold text-uppercase" style="color:#bd1734;letter-spacing:.12em">Administración</span>
            <h1 class="h2 fw-bold mt-1 mb-0"><?= $esEdicion ? 'Editar cuenta' : 'Crear cuenta' ?></h1>
            <p class="text-secondary mb-0 mt-2"><?= $esEdicion ? 'Actualiza el rol, los datos de cliente o restablece la contraseña.' : 'Crea una cuenta y asigna el rol que corresponde.' ?></p>
        </div>
        <?php if ($error = session()->getFlashdata('error')): ?><div class="alert alert-danger" role="alert"><?= esc($error) ?></div><?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <section class="user-form-card p-4 p-md-5">
            <form action="<?= $esEdicion ? base_url('usuarios/editar/' . $usuario['id_usuario']) : base_url('usuarios/guardar') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input class="form-control" id="nombre" name="nombre" value="<?= esc(old('nombre', $usuario['nombre'] ?? '')) ?>" maxlength="100" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input class="form-control" type="email" id="email" name="email" value="<?= esc(old('email', $usuario['email'] ?? '')) ?>" maxlength="255" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <?php foreach (['cliente' => 'Cliente', 'trabajador' => 'Trabajador', 'admin' => 'Administrador'] as $roleValue => $roleLabel): ?>
                                <option value="<?= esc($roleValue) ?>" <?= old('rol', $usuario['rol'] ?? 'cliente') === $roleValue ? 'selected' : '' ?>><?= esc($roleLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label"><?= $esEdicion ? 'Nueva contraseña (opcional)' : 'Contraseña' ?></label>
                        <input class="form-control" type="password" id="password" name="password" minlength="10" maxlength="72" <?= $esEdicion ? '' : 'required' ?> autocomplete="new-password">
                        <div class="form-text"><?= $esEdicion ? 'Déjalo vacío para conservar la contraseña actual.' : 'Usa al menos 10 caracteres.' ?></div>
                    </div>
                    <div class="col-12 <?= old('rol', $usuario['rol'] ?? 'cliente') === 'cliente' ? '' : 'd-none' ?>" id="customerIdentityFields"><div class="row g-3">
                    <div class="col-12"><hr class="border-secondary"><h2 class="h5">Datos del cliente <span class="text-secondary fw-normal">(opcionales)</span></h2></div>
                    <div class="col-12 col-md-6"><label for="dpi" class="form-label">DPI</label><input class="form-control" id="dpi" name="dpi" value="<?= esc(old('dpi', $usuario['dpi'] ?? '')) ?>" maxlength="20"></div>
                    <div class="col-12 col-md-6"><label for="nit" class="form-label">NIT</label><input class="form-control" id="nit" name="nit" value="<?= esc(old('nit', $usuario['nit'] ?? '')) ?>" maxlength="20"></div>
                    <div class="col-12 col-md-6"><label for="telefono" class="form-label">Teléfono</label><input class="form-control" id="telefono" name="telefono" value="<?= esc(old('telefono', $usuario['telefono'] ?? '')) ?>" maxlength="30"></div>
                    <div class="col-12 col-md-6"><label for="direccion" class="form-label">Dirección</label><input class="form-control" id="direccion" name="direccion" value="<?= esc(old('direccion', $usuario['direccion'] ?? '')) ?>" maxlength="255"></div>
                    </div></div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-danger px-4" type="submit"><?= $esEdicion ? 'Guardar cambios' : 'Crear cuenta' ?></button>
                    <a class="btn btn-outline-secondary" href="<?= base_url('usuarios') ?>">Cancelar</a>
                </div>
            </form>
        </section>
    </main>
    <script>
        const roleSelect = document.getElementById('rol');
        const customerFields = document.getElementById('customerIdentityFields');
        roleSelect.addEventListener('change', () => customerFields.classList.toggle('d-none', roleSelect.value !== 'cliente'));
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
