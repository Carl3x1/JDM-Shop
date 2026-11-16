<?php
helper('form');

$fields = [
    ['codigo_motor', 'Código Motor', 'text', '100'],
    ['hp_stock', 'HP Stock', 'number', null],
    ['torque_stock', 'Torque Stock', 'number', null],
    ['limite_hp_bloque_stock', 'Límite HP Bloque Stock', 'number', null],
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
            value="<?= esc(old($name, $motor[$name] ?? '')) ?>"
            <?php if ($type === 'number'): ?>
                min="0"
                step="0.01"
            <?php else: ?>
                maxlength="<?= esc($maxLength) ?>"
            <?php endif; ?>
            required
        >
    </div>
<?php endforeach; ?>
<div class="mb-3"><label for="descripcion" class="form-label">Descripción</label><textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="5000" placeholder="Describe la arquitectura, modelos compatibles y puntos destacados."><?= esc(old('descripcion', $motor['descripcion'] ?? '')) ?></textarea></div>
