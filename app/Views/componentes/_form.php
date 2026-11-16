<?php
helper('form');

$fields = [
    ['categoria', 'Categoría', 'text', '100'],
    ['nombre', 'Nombre', 'text', '255'],
    ['aporte_hp_pct', 'Aporte HP (%)', 'number', null],
    ['aporte_torque_pct', 'Aporte Torque (%)', 'number', null],
    ['precio', 'Precio', 'number', null],
    ['stock', 'Stock', 'number', null],
];
?>
<?php foreach ($fields as [$name, $label, $type, $maxLength]): ?>
    <div class="mb-3">
        <label for="<?= esc($name) ?>" class="form-label"><?= esc($label) ?></label>
        <input
            type="<?= esc($type) ?>"
            class="form-control"
            id="<?= esc($name) ?>"
            name="<?= esc($name) ?>"
            value="<?= esc(old($name, $componente[$name] ?? '')) ?>"
            <?php if ($type === 'number'): ?>
                <?= $name === 'stock' ? 'step="1"' : 'step="0.01"' ?>
                <?= in_array($name, ['precio', 'stock'], true) ? 'min="0"' : '' ?>
            <?php else: ?>
                maxlength="<?= esc($maxLength) ?>"
            <?php endif; ?>
            required
        >
    </div>
<?php endforeach; ?>
<div class="mb-3"><label for="descripcion" class="form-label">Descripción</label><textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="5000" placeholder="Explica para qué sirve, compatibilidad y beneficios de la pieza."><?= esc(old('descripcion', $componente['descripcion'] ?? '')) ?></textarea></div>
