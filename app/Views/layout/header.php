<?php
$currentPath = trim(service('uri')->getPath(), '/');
$userName = (string) (session()->get('nombre') ?? 'Cuenta');
?>
<style>
    html[data-theme="light"] body { background: #e9ecea !important; color: #303943 !important; }
    html[data-theme="light"] .bg-dark { background-color: #e9ecea !important; }
    html[data-theme="light"] .text-light { color: #303943 !important; }
    html[data-theme="light"] .text-secondary { color: #687278 !important; }
    html[data-theme="light"] .card, html[data-theme="light"] .bg-secondary { background-color: #fff !important; color: #26343a !important; }
    html[data-theme="light"] .bg-secondary.text-white, html[data-theme="light"] .card.text-white, html[data-theme="light"] .card.text-white .text-white { color: #26343a !important; }
    html[data-theme="light"] .table-dark { --bs-table-bg: #f0f1ef; --bs-table-color: #303943; --bs-table-striped-bg: #dfe4e1; --bs-table-striped-color: #303943; --bs-table-hover-bg: #d4dcd7; --bs-table-hover-color: #303943; border-color: #b6c0ba; }
    html[data-theme="light"] .table-dark thead th { background-color: #cbd4ce; color: #303943; border-color: #aeb9b2; }
    html[data-theme="light"] .table-dark tbody td { border-color: #b9c4bd; }
    html[data-theme="light"] .form-control.bg-dark, html[data-theme="light"] .form-select.bg-dark { background-color: #fff !important; color: #26343a !important; border-color: #cbd6d0 !important; }
    html[data-theme="light"] .border-secondary { border-color: #ccd6d1 !important; }
    html[data-theme="dark"] body { background: #1e2428; color: #edf1f3; }
    html[data-theme="dark"] .card { background-color: #292f34; color: #edf1f3; border-color: #3b4349; }
    html[data-theme="dark"] .table { --bs-table-color: #edf1f3; }
    html[data-theme="dark"] .form-control, html[data-theme="dark"] .form-select { background-color: #191e22; color: #edf1f3; border-color: #485158; }
    .app-navbar { background: #f5f6f4; border-bottom: 1px solid #cbd2ce; }
    html[data-theme="dark"] .app-navbar, html[data-theme="dark"] .navbar { background: #222a2e !important; border-color: #3c474a !important; }
    .app-navbar .navbar-brand { color: #26343a; font-weight: 750; letter-spacing: -.025em; }
    .app-navbar .navbar-brand .badge { background: #bd1734; }
    .app-navbar .nav-link { color: #424c54; }
    html[data-theme="dark"] .app-navbar .navbar-brand, html[data-theme="dark"] .app-navbar .nav-link, html[data-theme="dark"] .navbar .nav-link { color: #e5edeb !important; }
    .app-navbar .dropdown-menu { border-color: #d9e2dd; box-shadow: 0 12px 25px rgba(28,48,40,.12); }
    html[data-theme="dark"] .app-navbar .dropdown-menu { background: #293237; border-color: #455257; }
    html[data-theme="dark"] .app-navbar .dropdown-item { color: #e5edeb; }
    html[data-theme="dark"] .app-navbar .dropdown-item:hover { background: #39464a; }
    .app-back { color: #761f35; border-color: #bd8291; }
    html[data-theme="dark"] .app-back { color: #edb5c2; border-color: #80505c; }
    .btn-danger { --bs-btn-bg: #bd1734; --bs-btn-border-color: #bd1734; --bs-btn-hover-bg: #94152d; --bs-btn-hover-border-color: #94152d; --bs-btn-active-bg: #761f35; }
    .btn-outline-danger { --bs-btn-color: #a31935; --bs-btn-border-color: #bc7182; --bs-btn-hover-bg: #a31935; --bs-btn-hover-border-color: #a31935; }
    .btn-primary { --bs-btn-bg: #3b424a; --bs-btn-border-color: #3b424a; --bs-btn-hover-bg: #282e35; --bs-btn-hover-border-color: #282e35; }
    .btn-outline-primary { --bs-btn-color: #8e233d; --bs-btn-border-color: #b77887; --bs-btn-hover-bg: #8e233d; --bs-btn-hover-border-color: #8e233d; }
    .text-danger { color: #b51f3b !important; }
    .text-primary { color: #8e233d !important; }
    .text-warning { color: #a52b49 !important; }
    .border-warning { border-color: #a83b56 !important; }
    .bg-warning { background-color: #b63754 !important; color: #fff !important; }
    .btn-warning { --bs-btn-color: #fff; --bs-btn-bg: #a52b49; --bs-btn-border-color: #a52b49; --bs-btn-hover-bg: #7c1f38; --bs-btn-hover-border-color: #7c1f38; }
    .btn-outline-warning { --bs-btn-color: #91243f; --bs-btn-border-color: #b65c72; --bs-btn-hover-bg: #91243f; --bs-btn-hover-border-color: #91243f; }
</style>
<nav class="navbar navbar-expand-lg app-navbar shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand text-uppercase d-flex align-items-center" href="<?= base_url('/') ?>">
            <span class="badge text-white me-2" style="font-size:.65rem;letter-spacing:.08em;padding:.48rem .55rem">JDM</span>JDM Shop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Abrir navegación">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item"><a class="nav-link px-lg-3" href="<?= base_url('/') ?>">Inicio</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-lg-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Secciones</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= base_url('productos') ?>">Productos</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('vehiculos') ?>">Vehículos base</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('productos?tipo=motor') ?>">Motores</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('productos?tipo=componente') ?>">Componentes</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('proyectos') ?>">Proyectos de taller</a></li>
                        <?php if (session()->get('rol') === 'admin'): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('usuarios') ?>">Usuarios</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
            <div class="d-flex flex-wrap align-items-center gap-2 py-2 py-lg-0">
                <?php if ($currentPath !== ''): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary app-back" onclick="if (history.length > 1) history.back(); else location.href='<?= base_url('/') ?>'" aria-label="Volver a la página anterior">← Volver</button>
                <?php endif; ?>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="themeToggle" aria-label="Activar modo oscuro" title="Cambiar tema">☾</button>
                <?php if (session()->get('isLoggedIn')): ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de cuenta">Cuenta</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text fw-semibold"><?= esc($userName) ?></span></li>
                            <li><span class="dropdown-item-text small text-secondary text-uppercase"><?= esc(session()->get('rol') ?? 'cliente') ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('perfil') ?>">Mi perfil</a></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('acceso') ?>" class="btn btn-sm btn-outline-secondary">Iniciar sesión</a>
                    <a href="<?= base_url('registro') ?>" class="btn btn-sm btn-danger">Crear cuenta</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<script>
    (() => {
        const root = document.documentElement;
        const key = 'jdm-shop-theme';
        root.dataset.theme = localStorage.getItem(key) === 'dark' ? 'dark' : 'light';
        const button = document.getElementById('themeToggle');
        const update = () => {
            const dark = root.dataset.theme === 'dark';
            button.textContent = dark ? '☀' : '☾';
            button.setAttribute('aria-label', dark ? 'Activar modo claro' : 'Activar modo oscuro');
            button.setAttribute('aria-pressed', dark ? 'true' : 'false');
            button.title = dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro';
        };
        update();
        button.addEventListener('click', () => {
            root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem(key, root.dataset.theme);
            update();
        });
    })();
</script>
