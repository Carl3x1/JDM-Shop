<?php
helper('form');

$fields = [
    ['nombre', 'Nombre', 'text', '255'],
    ['categoria', 'Categoría', 'text', '100'],
    ['precio', 'Precio', 'number', null],
    ['stock', 'Stock', 'number', null],
    ['aporte_hp', 'Aporte HP', 'number', null],
    ['aporte_torque', 'Aporte Torque', 'number', null],
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
            value="<?= esc(old($name, $producto[$name] ?? '')) ?>"
            <?php if ($type === 'number'): ?>
                step="<?= $name === 'stock' ? '1' : '0.01' ?>"
                <?= in_array($name, ['precio', 'stock'], true) ? 'min="0"' : '' ?>
            <?php else: ?>
                maxlength="<?= esc($maxLength) ?>"
            <?php endif; ?>
            required
        >
    </div>
<?php endforeach; ?>
<div class="mb-3"><label for="descripcion" class="form-label">Descripción</label><textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="5000" placeholder="Describe el uso, compatibilidad y características principales."><?= esc(old('descripcion', $producto['descripcion'] ?? '')) ?></textarea></div>
