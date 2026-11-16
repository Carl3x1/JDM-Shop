<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de productos | JDM Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<?= $this->include('layout/header') ?>
<main class="container py-4 py-lg-5">
    <?php $esAdmin = session()->get('rol') === 'admin'; ?>
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div><span class="small text-danger text-uppercase fw-bold">Catálogo JDM</span><h1 class="h2 fw-bold mb-1">Productos</h1><p class="text-secondary mb-0">Explora piezas, motores y componentes en un solo lugar.</p></div>
        <?php if ($esAdmin): ?><div class="d-flex flex-wrap gap-2"><a class="btn btn-outline-danger" href="<?= base_url('motores/crear') ?>">+ Motor</a><a class="btn btn-outline-danger" href="<?= base_url('componentes/crear') ?>">+ Componente</a><a class="btn btn-danger" href="<?= base_url('productos/crear') ?>">+ Pieza</a></div><?php endif; ?>
    </div>
    <?php if ($success = session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc($success) ?></div><?php endif; ?>
    <?php if ($error = session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
    <div class="d-flex flex-wrap gap-2 mb-3" role="group" aria-label="Filtrar tipo de producto">
        <?php foreach (['todos'=>'Todo el catálogo','producto'=>'Piezas','motor'=>'Motores','componente'=>'Componentes'] as $key=>$label): ?>
            <a class="btn btn-sm <?= $tipo === $key ? 'btn-danger' : 'btn-outline-secondary' ?>" href="<?= base_url('productos?tipo='.$key) ?>"><?= esc($label) ?></a>
        <?php endforeach; ?>
    </div>
    <form class="card bg-secondary bg-opacity-25 border-secondary p-3 mb-4" method="get" action="<?= base_url('productos') ?>">
        <input type="hidden" name="tipo" value="<?= esc($tipo) ?>">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md"><label class="form-label small" for="q">Buscar</label><input class="form-control" id="q" name="q" value="<?= esc($busqueda) ?>" placeholder="Nombre, categoría o descripción"></div>
            <div class="col-12 col-md-4"><label class="form-label small" for="categoria">Categoría</label><select class="form-select" id="categoria" name="categoria"><option value="">Todas las categorías</option><?php foreach ($categorias as $opcion): ?><option value="<?= esc($opcion) ?>" <?= $categoria === $opcion ? 'selected' : '' ?>><?= esc($opcion) ?></option><?php endforeach; ?></select></div>
            <div class="col-12 col-md-auto d-flex gap-2"><button class="btn btn-danger" type="submit">Filtrar</button><a class="btn btn-outline-secondary" href="<?= base_url('productos?tipo='.$tipo) ?>">Limpiar</a></div>
        </div>
    </form>
    <div class="row g-3">
        <?php foreach ($catalogo as $item): ?>
            <div class="col-12 col-md-6 col-xl-4"><article class="card h-100 bg-secondary bg-opacity-25 border-secondary shadow-sm"><div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between gap-2 mb-2"><span class="badge text-bg-danger"><?= esc(ucfirst($item['tipo'])) ?></span><span class="small text-secondary"><?= esc($item['categoria']) ?></span></div>
                <h2 class="h5 fw-bold"><?= esc($item['nombre']) ?></h2>
                <p class="small text-secondary flex-grow-1"><?= esc($item['descripcion'] ?: 'Consulta la ficha para conocer más detalles de este artículo.') ?></p>
                <div class="small fw-semibold mb-3"><?= esc($item['detalle']) ?></div>
                <div class="d-flex gap-2"><a class="btn btn-sm btn-outline-light" href="<?= base_url($item['url']) ?>">Ver ficha</a><?php if ($esAdmin): ?><a class="btn btn-sm btn-outline-primary" href="<?= base_url($item['editar']) ?>">Editar</a><?php endif; ?></div>
            </div></article></div>
        <?php endforeach; ?>
        <?php if (empty($catalogo)): ?><div class="col-12"><div class="card bg-secondary bg-opacity-25 border-secondary p-4 text-center text-secondary">No hay artículos que coincidan con esos filtros.</div></div><?php endif; ?>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
