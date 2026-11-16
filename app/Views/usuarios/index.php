<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de usuarios | JDM Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .users-panel { background:#f6f7f4; border:1px solid #c9d1cc; border-radius:14px; box-shadow:0 12px 28px rgba(28,38,43,.1); }
        html[data-theme="dark"] .users-panel { background:#292f34; border-color:#3b4349; }
        .role-tab { border:1px solid #c4ceca; border-radius:999px; color:#435159; background:#f9faf8; padding:.5rem .9rem; text-decoration:none; font-size:.9rem; font-weight:650; }
        .role-tab:hover,.role-tab.active { color:#fff; background:#bd1734; border-color:#bd1734; }
        html[data-theme="dark"] .role-tab { color:#e6ece9; background:#252c31; border-color:#465158; }
        html[data-theme="dark"] .role-tab.active,html[data-theme="dark"] .role-tab:hover { color:white; background:#bd1734; border-color:#bd1734; }
        .role-count { margin-left:.3rem; opacity:.8; }
    </style>
</head>
<body class="bg-dark text-light">
    <?= $this->include('layout/header') ?>
    <main class="container py-4 py-lg-5">
        <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <span class="small fw-bold text-uppercase" style="color:#bd1734;letter-spacing:.12em">Administración</span>
                <h1 class="h2 fw-bold mt-1 mb-0">Gestión de usuarios</h1>
                <p class="text-secondary mb-0 mt-2">Administra clientes, trabajadores y cuentas con permisos administrativos.</p>
            </div>
            <a href="<?= base_url('usuarios/crear') ?>" class="btn btn-danger fw-semibold">+ Crear cuenta</a>
        </div>

        <?php if ($success = session()->getFlashdata('success')): ?><div class="alert alert-success" role="status"><?= esc($success) ?></div><?php endif; ?>
        <?php if ($error = session()->getFlashdata('error')): ?><div class="alert alert-danger" role="alert"><?= esc($error) ?></div><?php endif; ?>

        <nav class="d-flex flex-wrap gap-2 mb-3" aria-label="Filtrar cuentas por rol">
            <a class="role-tab <?= $activeRole === 'todos' ? 'active' : '' ?>" href="<?= base_url('usuarios') ?>">Todos <span class="role-count"><?= array_sum($roleCounts) ?></span></a>
            <a class="role-tab <?= $activeRole === 'cliente' ? 'active' : '' ?>" href="<?= base_url('usuarios?rol=cliente') ?>">Clientes <span class="role-count"><?= esc($roleCounts['cliente']) ?></span></a>
            <a class="role-tab <?= $activeRole === 'trabajador' ? 'active' : '' ?>" href="<?= base_url('usuarios?rol=trabajador') ?>">Trabajadores <span class="role-count"><?= esc($roleCounts['trabajador']) ?></span></a>
            <a class="role-tab <?= $activeRole === 'admin' ? 'active' : '' ?>" href="<?= base_url('usuarios?rol=admin') ?>">Administradores <span class="role-count"><?= esc($roleCounts['admin']) ?></span></a>
        </nav>

        <?php $mostrarDatosCliente = in_array($activeRole, ['todos', 'cliente'], true); ?>
        <section class="users-panel p-2 p-md-3" aria-label="Cuentas registradas">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover align-middle mb-0">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><?php if ($mostrarDatosCliente): ?><th>DPI</th><th>NIT</th><th>Contacto</th><?php endif; ?><th>Rol</th><th>Registro</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td><?= esc($user['id_usuario']) ?></td>
                                <td class="fw-semibold"><?= esc($user['nombre']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <?php if ($mostrarDatosCliente): ?>
                                    <td><?= $user['rol'] === 'cliente' ? esc($user['dpi'] ?: '—') : '—' ?></td>
                                    <td><?= $user['rol'] === 'cliente' ? esc($user['nit'] ?: '—') : '—' ?></td>
                                    <td><?php if ($user['rol'] === 'cliente'): ?><span class="d-block"><?= esc($user['telefono'] ?: '—') ?></span><small class="text-secondary"><?= esc($user['direccion'] ?: 'Sin dirección') ?></small><?php else: ?>—<?php endif; ?></td>
                                <?php endif; ?>
                                <td><span class="badge <?= $user['rol'] === 'admin' ? 'text-bg-danger' : ($user['rol'] === 'trabajador' ? 'text-bg-warning' : 'text-bg-info') ?>"><?= esc(ucfirst($user['rol'])) ?></span></td>
                                <td class="text-nowrap"><?= esc($user['fecha_registro']) ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="<?= base_url('usuarios/editar/' . $user['id_usuario']) ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <?php if ((int) $user['id_usuario'] !== (int) session()->get('id_usuario')): ?>
                                        <form action="<?= base_url('usuarios/eliminar/' . $user['id_usuario']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta cuenta?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($usuarios)): ?><tr><td colspan="<?= $mostrarDatosCliente ? 9 : 6 ?>" class="text-center text-secondary py-5">No hay cuentas en este grupo.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
