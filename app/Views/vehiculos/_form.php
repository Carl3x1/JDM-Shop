<?php
helper('form');

$fields = [
    ['marca', 'Marca', 'text', '100'],
    ['modelo', 'Modelo', 'text', '100'],
    ['anio', 'Año', 'number', null],
    ['codigo_chasis', 'Código Chasis', 'text', '100'],
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
            value="<?= esc(old($name, $vehiculo[$name] ?? '')) ?>"
            <?php if ($type === 'number'): ?>
                min="1886"
                max="<?= esc(date('Y') + 1) ?>"
                step="1"
            <?php else: ?>
                maxlength="<?= esc($maxLength) ?>"
            <?php endif; ?>
            required
        >
    </div>
<?php endforeach; ?>
<div class="mb-3"><label for="descripcion" class="form-label">Descripción completa</label><textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="5000" placeholder="Describe la plataforma, su generación, enfoque y datos destacados."><?= esc(old('descripcion', $vehiculo['descripcion'] ?? '')) ?></textarea></div>
