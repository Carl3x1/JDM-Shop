<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explora el catálogo JDM de productos, vehículos, motores y proyectos de taller.">
    <title>JDM Shop | Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --ink: #303943; --muted: #6e777c; --paper: #e9ecea; --teal: #bd1734; --teal-dark: #761f35; --line: #d2d9d5; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; background: var(--paper); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; }
        .navbar { background: rgba(248,250,247,.96) !important; border-bottom: 1px solid var(--line) !important; padding-block: .8rem; }
        .navbar .navbar-brand { color: var(--ink) !important; letter-spacing: -.03em; }
        .navbar .badge.bg-danger { background: var(--teal) !important; border-radius: 7px; padding: .48rem .55rem; }
        .navbar .nav-link { color: #526269 !important; font-size: .92rem; font-weight: 550; padding-inline: .72rem !important; }
        .navbar .nav-link:hover { color: var(--teal) !important; }
        .navbar .navbar-toggler { border-color: #cbd6d1; }
        .navbar .navbar-toggler-icon { filter: brightness(.35); }
        .navbar .btn-outline-light { color: var(--teal-dark); border-color: #9fb9b2; }
        .navbar .btn-outline-light:hover { color: white; background: var(--teal); }
        .navbar .btn-outline-danger { color: #9d5447; border-color: #c9988e; }
        .navbar .text-light { color: var(--ink) !important; }
        .home-wrap { max-width: 1240px; }
        .hero { position: relative; isolation: isolate; overflow: hidden; min-height: 390px; padding: clamp(2.1rem, 5vw, 4.6rem); border-radius: 24px; color: #f8fbf8; background-color: #343b43; background-image: linear-gradient(100deg, rgba(38,45,53,.95) 0%, rgba(71,43,52,.84) 56%, rgba(128,27,53,.58) 100%), url("<?= base_url('images/taller-mecanico.jpg') ?>"); background-position: center; background-size: cover; box-shadow: 0 20px 45px rgba(34,40,45,.18); }
        .hero-copy { max-width: 610px; }
        .eyebrow { font-size: .76rem; font-weight: 750; letter-spacing: .17em; text-transform: uppercase; color: #e8899b; }
        .hero h1 { max-width: 610px; margin: .85rem 0 1rem; font-size: clamp(2.25rem, 5vw, 4rem); line-height: .99; letter-spacing: -.055em; font-weight: 780; }
        .hero p { max-width: 530px; color: rgba(241,248,242,.78); font-size: 1.05rem; line-height: 1.65; }
        .btn-hero { margin-top: .6rem; padding: .78rem 1.15rem; border-radius: 9px; color: white; background: #bd1734; font-weight: 700; }
        .btn-hero:hover { color: white; background: #8f1730; transform: translateY(-1px); }
        .catalog-section { position: relative; z-index: 1; margin: 1.75rem auto 0; padding-inline: clamp(.5rem, 2.4vw, 2rem); }
        .section-heading { display: flex; justify-content: space-between; align-items: end; gap: 1rem; margin: 0 0 1.15rem; }
        .section-heading h2 { margin: 0; font-size: 1.23rem; font-weight: 750; letter-spacing: -.025em; }
        .section-heading p { margin: .25rem 0 0; color: var(--muted); font-size: .9rem; }
        .catalog-card { --accent: #176c68; position: relative; overflow: hidden; min-height: 222px; height: 100%; padding: 1.4rem; border: 1px solid rgba(217,226,220,.9); border-radius: 15px; background: #fbfcfa; box-shadow: 0 12px 28px rgba(38,55,49,.09); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
        .catalog-card:hover { transform: translateY(-5px); border-color: #abc9bc; box-shadow: 0 18px 34px rgba(38,55,49,.14); }
        .catalog-card.featured { color: white; border-color: transparent; background: linear-gradient(145deg, #ce2947, #7d1c36); }
        .catalog-card .card-icon { display: grid; place-items: center; width: 42px; height: 42px; margin-bottom: 1.05rem; border-radius: 12px; color: var(--accent); background: color-mix(in srgb, var(--accent) 11%, white); }
        .catalog-card.featured .card-icon { color: white; background: rgba(255,255,255,.15); }
        .catalog-card h3 { margin: 0 0 .5rem; font-size: 1.08rem; font-weight: 750; letter-spacing: -.02em; }
        .catalog-card p { max-width: 270px; min-height: 42px; margin: 0 0 1rem; color: var(--muted); font-size: .88rem; line-height: 1.5; }
        .catalog-card.featured p { color: rgba(255,255,255,.78); }
        .card-link { display: inline-flex; gap: .4rem; align-items: center; color: var(--accent); text-decoration: none; font-size: .87rem; font-weight: 750; }
        .card-link:hover { color: #761f35; }
        .featured .card-link { color: white; }
        .card-count { position: absolute; top: 1.45rem; right: 1.35rem; color: #899a93; font-size: .75rem; font-weight: 700; }
        .featured .card-count { color: rgba(255,255,255,.7); }
        .flash-wrap { position: relative; z-index: 2; }
        .home-footer { padding: 2rem 0 1rem; color: #84918c; font-size: .82rem; }
        @media (max-width: 767.98px) { .hero { min-height: 350px; border-radius: 18px; } .catalog-section { margin-top: 1.4rem; padding-inline: .25rem; } .catalog-card { min-height: 205px; } }
        html[data-theme="dark"] body { background: #172226; color: #edf3f0; }
        html[data-theme="dark"] .section-heading h2 { color: #edf3f0; }
        html[data-theme="dark"] .section-heading p, html[data-theme="dark"] .catalog-card p { color: #b0c0bb; }
        html[data-theme="dark"] .catalog-card { background: #253338; color: #edf3f0; border-color: #3c4c4e; }
        html[data-theme="dark"] .catalog-card.featured { background: linear-gradient(145deg, #ce2947, #7d1c36); }
        html[data-theme="dark"] .catalog-card .card-count { color: #b4c3be; }
        html[data-theme="dark"] .home-footer { color: #aab8b4; }
        @media (prefers-reduced-motion: reduce) { *, *:before, *:after { transition-duration: .01ms !important; scroll-behavior: auto !important; } }
    </style>
</head>
<body>
    <?= $this->include('layout/header') ?>

    <main class="container home-wrap py-4 py-lg-5">
        <div class="flash-wrap">
            <?php if ($error = session()->getFlashdata('error')): ?>
                <div class="alert alert-danger rounded-3 shadow-sm" role="alert"><?= esc($error) ?></div>
            <?php endif; ?>
            <?php if ($success = session()->getFlashdata('success')): ?>
                <div class="alert alert-success rounded-3 shadow-sm" role="alert"><?= esc($success) ?></div>
            <?php endif; ?>
        </div>

        <?php $rolActual = session()->get('rol') ?? 'cliente'; $esAdmin = $rolActual === 'admin'; ?>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-copy">
                <div class="eyebrow">JDM Build &amp; Shop</div>
                <h1 id="hero-title">Construye algo que se sienta tuyo.</h1>
                                <p><?= $esAdmin
                    ? 'Gestiona el cat&aacute;logo y da forma a nuevos proyectos de taller desde un solo lugar.'
                    : ($rolActual === 'trabajador'
                        ? 'Da seguimiento a los proyectos y actualiza cada fase del trabajo en taller.'
                        : 'Encuentra las piezas, plataformas y motores ideales para tu pr&oacute;ximo proyecto.') ?></p>
                <a href="<?= base_url('productos') ?>" class="btn btn-hero">Explorar catálogo <span aria-hidden="true">→</span></a>
            </div>
        </section>

        <section class="catalog-section" aria-labelledby="catalog-title">
            <div class="section-heading">
                <div><h2 id="catalog-title">Encuentra tu siguiente proyecto</h2><p>Todo lo que necesitas para armarlo a tu manera.</p></div>
            </div>
            <div class="row g-3 g-lg-4">
                <?php
                $cards = [
                    ['productos', 'Productos', 'Explora piezas, motores y componentes con filtros por tipo y categoría.', 'productos', 'Catálogo', 'box', 'var(--teal)', false],
                    ['vehiculos', 'Vehículos base', 'Explora plataformas JDM y encuentra la base ideal.', 'vehiculos', 'Modelos', 'car', 'var(--teal)', false],
                    ['proyectos', 'Proyectos de taller', 'Organiza tus builds y revisa tus cotizaciones.', 'proyectos', 'Proyectos', 'wrench', '#a65e4d', true],
                ];
                if ($esAdmin) $cards[] = ['usuarios', 'Usuarios', 'Administra las cuentas de la comunidad.', 'usuarios', 'Cuentas', 'users', '#66748a', false];
                $icons = [
                    'box' => '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 8 9 5 9-5M3 8v9l9 5 9-5V8M12 13v9"/>',
                    'car' => '<path d="m5 11 1.5-5h11l1.5 5M3 11h18l1 3v5h-3v-2H5v2H2v-5l1-3Z"/><circle cx="6.5" cy="14.5" r="1"/><circle cx="17.5" cy="14.5" r="1"/>',
                    'engine' => '<path d="M4 10h3l2-3h6l2 3h3v8h-3l-2 2h-6l-2-2H4v-8Z"/><path d="M10 7V4h4v3M20 12h2M2 12h2M10 13h4v3h-4z"/>',
                    'gear' => '<path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.8-.6a8 8 0 0 1-1.6.9l-.4 1.9h-2.8l-.4-1.9a8 8 0 0 1-1.6-.9l-1.8.6-1.4-2.4 1.5-1.2a7 7 0 0 1 0-1.8l-1.5-1.2 1.4-2.4 1.8.6a8 8 0 0 1 1.6-.9l.4-1.9h2.8l.4 1.9a8 8 0 0 1 1.6.9l1.8-.6 1.4 2.4-1.5 1.2a7 7 0 0 1 0 1.8Z" transform="translate(-1 -1)"/>',
                    'wrench' => '<path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3 3.7-3.7Z"/>',
                    'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6M23 11h-6"/>',
                ];
                ?>
                <?php foreach ($cards as [$key, $title, $description, $url, $countLabel, $icon, $color, $featured]): ?>
                    <div class="col-12 col-sm-6 col-xl-4">
                        <article class="catalog-card <?= $featured ? 'featured' : '' ?>" style="--accent: <?= esc($color) ?>">
                            <?php if (isset($totales[$key])): ?><span class="card-count"><?= esc($totales[$key]) ?> <?= esc($countLabel) ?></span><?php endif; ?>
                            <div class="card-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $icons[$icon] ?></svg></div>
                            <h3><?= esc($title) ?></h3>
                            <p><?= esc($description) ?></p>
                            <a class="card-link" href="<?= base_url($url) ?>">Ver sección <span aria-hidden="true">→</span></a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <footer class="home-footer text-center">JDM Shop <span aria-hidden="true">·</span> Hecho para construir a tu manera</footer>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
