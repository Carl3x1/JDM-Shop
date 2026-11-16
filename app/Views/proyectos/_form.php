<?php helper('form'); ?>

<div class="row g-3">
    <!-- Información Principal del Proyecto -->
    <div class="col-12 col-md-6">
        <label for="nombre_proyecto" class="form-label text-secondary small fw-semibold text-uppercase">Nombre del Proyecto</label>
        <input
            type="text"
            class="form-control bg-dark text-light border-secondary"
            id="nombre_proyecto"
            name="nombre_proyecto"
            placeholder="Ej. Nissan GT-R R34 Mine's Tribute"
            value="<?= esc(old('nombre_proyecto', $proyecto['nombre_proyecto'] ?? '')) ?>"
            maxlength="150"
            required
        >
    </div>

    <div class="col-12">
        <label for="detalle_solicitud" class="form-label text-secondary small fw-semibold text-uppercase">¿Qué te gustaría modificar?</label>
        <textarea class="form-control bg-dark text-light border-secondary" id="detalle_solicitud" name="detalle_solicitud" rows="3" maxlength="2000" placeholder="Cuéntanos el objetivo del proyecto, cambios deseados y cualquier detalle importante."><?= esc(old('detalle_solicitud', $proyecto['detalle_solicitud'] ?? '')) ?></textarea>
        <div class="form-text">Máximo 2000 caracteres. El equipo del taller revisará esta información con tu solicitud.</div>
    </div>

    <?php if (($rolActual ?? '') !== 'cliente'): ?>
    <div class="col-12 col-md-6">
        <label for="id_usuario" class="form-label text-secondary small fw-semibold text-uppercase">Cliente / Propietario</label>
        <select class="form-select bg-dark text-light border-secondary" id="id_usuario" name="id_usuario" required>
            <option value="">-- Seleccionar Cliente --</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option
                    value="<?= esc($usuario['id_usuario']) ?>"
                    <?= (string) old('id_usuario', $proyecto['id_usuario'] ?? '') === (string) $usuario['id_usuario'] ? 'selected' : '' ?>
                >
                    <?= esc($usuario['nombre']) ?> (<?= esc($usuario['email']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>

    <div class="col-12 col-md-4">
        <label for="id_vehiculo" class="form-label text-secondary small fw-semibold text-uppercase">Vehículo Base</label>
        <select class="form-select bg-dark text-light border-secondary" id="id_vehiculo" name="id_vehiculo" required>
            <option value="">-- Seleccionar Vehículo --</option>
            <?php foreach ($vehiculos as $vehiculo): ?>
                <option
                    value="<?= esc($vehiculo['id_vehiculo']) ?>"
                    <?= (string) old('id_vehiculo', $proyecto['id_vehiculo'] ?? '') === (string) $vehiculo['id_vehiculo'] ? 'selected' : '' ?>
                >
                    <?= esc($vehiculo['marca']) ?> <?= esc($vehiculo['modelo']) ?> (<?= esc($vehiculo['anio']) ?>) [<?= esc($vehiculo['codigo_chasis']) ?>]
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-4">
        <label for="id_motor" class="form-label text-secondary small fw-semibold text-uppercase">Motor Base</label>
        <select class="form-select bg-dark text-light border-secondary" id="id_motor" name="id_motor" required>
            <option value="" data-hp="0" data-torque="0" data-limite="0">-- Seleccionar Motor --</option>
            <?php foreach ($motores as $motor): ?>
                <option
                    value="<?= esc($motor['id_motor']) ?>"
                    data-hp="<?= esc($motor['hp_stock']) ?>"
                    data-torque="<?= esc($motor['torque_stock']) ?>"
                    data-limite="<?= esc($motor['limite_hp_bloque_stock']) ?>"
                    <?= (string) old('id_motor', $proyecto['id_motor'] ?? '') === (string) $motor['id_motor'] ? 'selected' : '' ?>
                >
                    <?= esc($motor['codigo_motor']) ?> (Stock: <?= esc($motor['hp_stock']) ?> HP / <?= esc($motor['torque_stock']) ?> lb-ft)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if (($rolActual ?? '') !== 'cliente'): ?>
    <div class="col-12 col-md-4">
        <label for="fase_taller" class="form-label text-secondary small fw-semibold text-uppercase">Fase en Taller</label>
        <?php
        $fases = [
            'Presupuesto Inicial',
            'Desarme & Diagnóstico',
            'Mecanizado & Forjado',
            'Instalación de Piezas',
            'Dyno Tuning & Electrónica',
            'Pruebas de Calle / Pista',
            'Finalizado & Entregado',
        ];
        $faseActual = old('fase_taller', $proyecto['fase_taller'] ?? 'Presupuesto Inicial');
        ?>
        <select class="form-select bg-dark text-light border-secondary" id="fase_taller" name="fase_taller" required>
            <?php foreach ($fases as $fase): ?>
                <option value="<?= esc($fase) ?>" <?= $faseActual === $fase ? 'selected' : '' ?>>
                    <?= esc($fase) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
</div>

<!-- PANEL ESTIMADOR DE RENDIMIENTO EN TIEMPO REAL -->
<div class="card bg-black border border-secondary shadow-sm mt-4">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <span class="text-info text-uppercase fw-bold small">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-speedometer2 me-1" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L10.18 6.8a.5.5 0 0 0 .708.708l1.046-1.046a.39.39 0 0 0 .02-.528M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m0 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1"/>
                </svg>
                Cotizador &amp; Estimador Dyno en Vivo
            </span>
            <span class="badge bg-secondary" id="livePiezasCount">0 componentes seleccionados</span>
        </div>

        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="p-2 rounded bg-dark border border-secondary border-opacity-50">
                    <span class="text-secondary small d-block">HP Estimado</span>
                    <span class="h4 fw-bold text-danger mb-0" id="liveHp">0.00 HP</span>
                    <span class="d-block small text-secondary" id="liveHpSub">Stock: 0 HP</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 rounded bg-dark border border-secondary border-opacity-50">
                    <span class="text-secondary small d-block">Torque Estimado</span>
                    <span class="h4 fw-bold text-warning mb-0" id="liveTorque">0.00 lb-ft</span>
                    <span class="d-block small text-secondary" id="liveTorqueSub">Stock: 0 lb-ft</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 rounded bg-dark border border-secondary border-opacity-50">
                    <span class="text-secondary small d-block">Incremento Rendimiento</span>
                    <span class="h5 fw-bold text-info mb-0" id="liveGainPct">+0.0% HP</span>
                    <span class="d-block small text-secondary" id="liveGainTqPct">+0.0% TQ</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 rounded bg-dark border border-secondary border-opacity-50">
                    <span class="text-secondary small d-block">Total Cotización</span>
                    <span class="h4 fw-bold text-success mb-0" id="liveTotal">$0.00 USD</span>
                    <span class="d-block small text-secondary">Componentes instalados</span>
                </div>
            </div>
        </div>

        <!-- Alerta de bloque motor -->
        <div id="liveBlockWarning" class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis py-2 px-3 small rounded mt-3 mb-0 d-none">
            <strong>⚠️ Alerta de Taller:</strong> La potencia estimada supera el límite recomendado del bloque de fábrica (<span id="liveBlockLimit">0</span> HP). Se sugiere incorporar forjado de pistones y bielas.
        </div>
    </div>
</div>

<!-- SELECCIÓN DE COMPONENTES DE MODIFICACIÓN -->
<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="h5 fw-bold text-light mb-0">Componentes de Modificación</h4>
            <p class="text-secondary small mb-0">Selecciona las piezas a cotizar e instalar en el vehículo</p>
        </div>
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary" id="btnSelectAll">Seleccionar Todos</button>
            <button type="button" class="btn btn-outline-secondary" id="btnDeselectAll">Deseleccionar</button>
        </div>
    </div>

    <?php
    // Agrupar componentes por categoría
    $componentesPorCategoria = [];
    foreach ($componentes as $comp) {
        $cat = ! empty($comp['categoria']) ? $comp['categoria'] : 'Otras Modificaciones';
        $componentesPorCategoria[$cat][] = $comp;
    }
    $seleccionados = is_array($componentes_seleccionados ?? null) ? $componentes_seleccionados : [];
    ?>

    <?php if (empty($componentesPorCategoria)): ?>
        <div class="alert alert-warning">
            No hay componentes registrados en el catálogo. Agrega componentes desde el módulo correspondiente para cotizar.
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($componentesPorCategoria as $categoria => $items): ?>
                <div class="col-12">
                    <div class="border-bottom border-secondary pb-1 mb-2">
                        <span class="badge text-bg-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            <?= esc($categoria) ?>
                        </span>
                    </div>
                    <div class="row g-2">
                        <?php foreach ($items as $item): ?>
                            <?php $isChecked = in_array((int) $item['id_componente'], array_map('intval', $seleccionados), true); ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="card h-100 bg-dark text-light border-secondary border-opacity-75 p-2 component-card <?= $isChecked ? 'border-danger bg-opacity-75' : '' ?>" style="cursor: pointer;">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="pt-1">
                                            <input
                                                type="checkbox"
                                                name="componentes[]"
                                                value="<?= esc($item['id_componente']) ?>"
                                                class="form-check-input componente-checkbox mt-0"
                                                data-precio="<?= (float) $item['precio'] ?>"
                                                data-hp="<?= (float) ($item['aporte_hp_pct'] ?? $item['aporte_hp'] ?? 0) ?>"
                                                data-torque="<?= (float) ($item['aporte_torque_pct'] ?? $item['aporte_torque'] ?? 0) ?>"
                                                <?= $isChecked ? 'checked' : '' ?>
                                            >
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <span class="fw-semibold small d-block text-light"><?= esc($item['nombre']) ?></span>
                                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 ms-1">
                                                    $<?= number_format((float) $item['precio'], 2) ?>
                                                </span>
                                            </div>
                                            <div class="d-flex gap-1 mt-1 flex-wrap">
                                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25" style="font-size: 0.68rem;">
                                                    +<?= (float) ($item['aporte_hp_pct'] ?? $item['aporte_hp'] ?? 0) ?>% HP
                                                </span>
                                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25" style="font-size: 0.68rem;">
                                                    +<?= (float) ($item['aporte_torque_pct'] ?? $item['aporte_torque'] ?? 0) ?>% TQ
                                                </span>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">
                                                    Stock: <?= esc($item['stock'] ?? 0) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Script interactivo de estimación dyno y cotización -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const motorSelect = document.getElementById('id_motor');
    const checkboxes  = document.querySelectorAll('.componente-checkbox');
    const liveHp      = document.getElementById('liveHp');
    const liveTorque  = document.getElementById('liveTorque');
    const liveHpSub   = document.getElementById('liveHpSub');
    const liveTorqueSub = document.getElementById('liveTorqueSub');
    const liveGainPct = document.getElementById('liveGainPct');
    const liveGainTqPct = document.getElementById('liveGainTqPct');
    const liveTotal   = document.getElementById('liveTotal');
    const livePiezasCount = document.getElementById('livePiezasCount');
    const liveBlockWarning = document.getElementById('liveBlockWarning');
    const liveBlockLimit   = document.getElementById('liveBlockLimit');

    function actualizarCalculos() {
        const selectedOption = motorSelect.options[motorSelect.selectedIndex];
        const hpStock     = parseFloat(selectedOption ? selectedOption.getAttribute('data-hp') : 0) || 0;
        const torqueStock = parseFloat(selectedOption ? selectedOption.getAttribute('data-torque') : 0) || 0;
        const limiteHp    = parseFloat(selectedOption ? selectedOption.getAttribute('data-limite') : 0) || 0;

        let totalPrecio = 0;
        let sumaPctHp   = 0;
        let sumaPctTq   = 0;
        let cantPiezas  = 0;

        checkboxes.forEach(cb => {
            const card = cb.closest('.component-card');
            if (cb.checked) {
                totalPrecio += parseFloat(cb.getAttribute('data-precio')) || 0;
                sumaPctHp   += parseFloat(cb.getAttribute('data-hp')) || 0;
                sumaPctTq   += parseFloat(cb.getAttribute('data-torque')) || 0;
                cantPiezas++;
                if (card) {
                    card.classList.add('border-danger', 'bg-opacity-100');
                }
            } else {
                if (card) {
                    card.classList.remove('border-danger', 'bg-opacity-100');
                }
            }
        });

        const hpFinal     = hpStock * (1 + (sumaPctHp / 100));
        const torqueFinal = torqueStock * (1 + (sumaPctTq / 100));

        liveHp.textContent       = hpFinal.toFixed(2) + ' HP';
        liveTorque.textContent   = torqueFinal.toFixed(2) + ' lb-ft';
        liveHpSub.textContent    = 'Stock: ' + hpStock.toFixed(2) + ' HP';
        liveTorqueSub.textContent = 'Stock: ' + torqueStock.toFixed(2) + ' lb-ft';
        liveGainPct.textContent  = '+' + sumaPctHp.toFixed(1) + '% HP';
        liveGainTqPct.textContent = '+' + sumaPctTq.toFixed(1) + '% TQ';
        liveTotal.textContent    = '$' + totalPrecio.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' USD';
        livePiezasCount.textContent = cantPiezas + ' componente' + (cantPiezas === 1 ? '' : 's') + ' seleccionado' + (cantPiezas === 1 ? '' : 's');

        // Alerta de límite de bloque
        if (limiteHp > 0 && hpFinal > limiteHp) {
            liveBlockLimit.textContent = limiteHp.toFixed(0);
            liveBlockWarning.classList.remove('d-none');
        } else {
            liveBlockWarning.classList.add('d-none');
        }
    }

    motorSelect.addEventListener('change', actualizarCalculos);
    checkboxes.forEach(cb => cb.addEventListener('change', actualizarCalculos));

    document.getElementById('btnSelectAll')?.addEventListener('click', function () {
        checkboxes.forEach(cb => cb.checked = true);
        actualizarCalculos();
    });

    document.getElementById('btnDeselectAll')?.addEventListener('click', function () {
        checkboxes.forEach(cb => cb.checked = false);
        actualizarCalculos();
    });

    actualizarCalculos();
});
</script>
