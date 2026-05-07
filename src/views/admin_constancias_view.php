<?php

use App\infrastructure\repository\FirestoreRepository;

// CSS
echo "<link rel='stylesheet' type='text/css' href='" . $session->get('absoluteURL') . "/modules/Constancias de Examen/css/admin.css?v=" . time() . "' />";

$total      = count($solicitudes);
$pendiente  = count(array_filter($solicitudes, fn($r) => $r['estado'] === 'pendiente'));
$completado = count(array_filter($solicitudes, fn($r) => $r['estado'] === 'completado'));
$rechazado  = count(array_filter($solicitudes, fn($r) => $r['estado'] === 'rechazado'));
?>

<div class="constancias-module">

    <!-- Stats -->
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon-wrapper total">&#9782;</div>
            <div class="stat-content">
                <div class="stat-title">Total</div>
                <div class="stat-number"><?= $total ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-wrapper pending">&#9201;</div>
            <div class="stat-content">
                <div class="stat-title">Pendientes</div>
                <div class="stat-number"><?= $pendiente ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-wrapper sent">&#10003;</div>
            <div class="stat-content">
                <div class="stat-title">Completadas</div>
                <div class="stat-number"><?= $completado ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-wrapper rejected">&#10007;</div>
            <div class="stat-content">
                <div class="stat-title">Rechazadas</div>
                <div class="stat-number"><?= $rechazado ?></div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filter-container">
        <div class="search-form">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre, DNI o materia...">
            </div>
            <div class="filter-box">
                <span class="filter-icon">&#9660;</span>
                <select id="statusFilter" class="filter-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="completado">Completado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div id="constanciasTableContainer">
        <h2 class="constancias-table-title">Solicitudes de Constancias</h2>
        <div class="table-wrapper">
            <table class="constancias-table">
                <thead>
                    <tr>
                        <th class="col-estudiante">Estudiante</th>
                        <th class="col-dni">DNI</th>
                        <th class="col-email">Email</th>
                        <th class="col-materia">Materia</th>
                        <th class="col-presentar-ante">Presentar Ante</th>
                        <th class="col-fecha-examen">Fecha del Examen</th>
                        <th class="col-fecha-solicitud">Fecha de Solicitud</th>
                        <th class="col-estado">Estado</th>
                        <th class="col-constancia">Constancia</th>
                        <th class="col-acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($solicitudes)): ?>
                    <tr>
                        <td colspan="10" class="empty-row">No hay solicitudes registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($solicitudes as $row): ?>
                    <?php
                        $constanciaId = $row['constanciaId'];
                        $formId       = 'uploadForm' . $constanciaId;
                        $estado       = strtolower($row['estado'] ?? '');
                        $badgeClass   = match($estado) {
                            'pendiente'  => 'badge-warning',
                            'completado' => 'badge-success',
                            'rechazado'  => 'badge-danger',
                            default      => ''
                        };
                        $fechaExamen = FirestoreRepository::formatTimestamp($row['examen']['fechaExamen'] ?? null);
                        $fechaPedido = FirestoreRepository::formatTimestamp($row['fechaPedido'] ?? null);
                    ?>
                    <tr>
                        <td class="col-estudiante"><?= htmlspecialchars($row['nombre'] ?? '') ?></td>
                        <td class="col-dni"><?= htmlspecialchars($row['dniAlumno'] ?? '') ?></td>
                        <td class="col-email"><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td class="col-materia"><?= htmlspecialchars($row['examen']['materia'] ?? '') ?></td>
                        <td class="col-presentar-ante"><?= htmlspecialchars($row['presentarAnte'] ?? '') ?></td>
                        <td class="col-fecha-examen"><?= htmlspecialchars($fechaExamen) ?></td>
                        <td class="col-fecha-solicitud"><?= htmlspecialchars($fechaPedido) ?></td>
                        <td class="col-estado">
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($estado) ?></span>
                        </td>
                        <td class="col-constancia">
                            <?php if ($estado === 'pendiente'): ?>
                                <form id="<?= $formId ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="constanciaId" value="<?= $constanciaId ?>">
                                    <input type="hidden" name="dniAlumno" value="<?= htmlspecialchars($row['dniAlumno']) ?>">
                                    <input type="hidden" name="materia" value="<?= htmlspecialchars($row['examen']['materia'] ?? '') ?>">
                                    <label class="upload-button" id="uploadLabel<?= $constanciaId ?>">
                                        <input type="file" name="file" accept=".pdf" required>
                                        <span class="button button--upload">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path d="M11 14.9861C11 15.5384 11.4477 15.9861 12 15.9861C12.5523 15.9861 13 15.5384 13 14.9861V7.82831L16.2428 11.0711L17.657 9.65685L12 4L6.34315 9.65685L7.75736 11.0711L11 7.82831V14.9861Z" fill="currentColor"/>
                                                <path d="M4 14H6V18H18V14H20V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V14Z" fill="currentColor"/>
                                            </svg>
                                            <span class="button-text">Subir PDF</span>
                                        </span>
                                    </label>
                                </form>
                            <?php elseif (!empty($row['pdfUrl'])): ?>
                                <a href="<?= htmlspecialchars($row['pdfUrl']) ?>" target="_blank" class="button button--pdf">Ver PDF</a>
                            <?php endif; ?>
                        </td>
                        <td class="col-acciones">
                            <?php if ($estado === 'pendiente'): ?>
                                <button type="button" class="button button--primary upload-submit-btn" data-form-id="<?= $formId ?>" disabled>Enviar</button>
                            <?php elseif ($estado === 'completado'): ?>
                                <button type="button" class="button button--secondary" disabled>Enviada</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="<?= $session->get('absoluteURL') ?>/modules/Constancias de Examen/js/admin.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput    = document.getElementById('searchInput');
    const statusFilter   = document.getElementById('statusFilter');
    const tableContainer = document.getElementById('constanciasTableContainer');

    // ---- Sidebar toggle ----
    // Buscar el sidebar de Gibbon (el nav que contiene el menú del módulo)
    const gibbon_sidebar = document.querySelector('nav.shadow') ||
                           document.querySelector('[class*="col-span-1"]');
    const gibbon_content = document.querySelector('#content-inner') ||
                           document.querySelector('[class*="flex-1"]');

    // Botón MODULE MENU de Gibbon — interceptar su click
    const moduleMenuBtn = document.querySelector('button.relative.w-full.flex.rounded');
    if (moduleMenuBtn && gibbon_sidebar) {
        moduleMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = gibbon_sidebar.style.display === 'none';
            gibbon_sidebar.style.display = isHidden ? '' : 'none';
            if (gibbon_content) {
                gibbon_content.style.maxWidth = isHidden ? '' : '100%';
                gibbon_content.style.flex     = isHidden ? '' : '1 1 100%';
            }
        });
    }

    // ---- Filtros ----
    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const status = statusFilter.value.toLowerCase();
        const rows   = tableContainer.querySelectorAll('tbody tr');

        rows.forEach(function (row) {
            const text      = row.textContent.toLowerCase();
            const badge     = row.querySelector('.badge');
            const rowStatus = badge ? badge.textContent.trim().toLowerCase() : '';

            const matchSearch = !search || text.includes(search);
            const matchStatus = !status || rowStatus === status;

            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // ---- Selección de archivo ----
    tableContainer.addEventListener('change', function (e) {
        if (e.target.matches('input[type="file"]')) {
            const form         = e.target.closest('form');
            const constanciaId = form.querySelector('[name="constanciaId"]').value;
            const submitBtn    = document.querySelector('[data-form-id="' + form.id + '"]');
            const label        = document.getElementById('uploadLabel' + constanciaId);
            const buttonText   = label.querySelector('.button-text');
            const file         = e.target.files[0];

            if (!file) return;

            if (file.type !== 'application/pdf') {
                alert('Por favor seleccioná un archivo PDF.');
                e.target.value = '';
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 10MB.');
                e.target.value = '';
                return;
            }

            buttonText.textContent = file.name.length > 20
                ? file.name.substring(0, 17) + '...'
                : file.name;

            if (submitBtn) submitBtn.disabled = false;
        }
    });

    // ---- Envío via AJAX ----
    tableContainer.addEventListener('click', function (e) {
        const btn = e.target.closest('.upload-submit-btn');
        if (!btn) return;

        const formId = btn.getAttribute('data-form-id');
        const form   = document.getElementById(formId);
        if (!form) return;

        btn.disabled    = true;
        btn.textContent = 'Enviando...';

        const formData = new FormData(form);

        fetch('modules/Constancias de Examen/api/upload.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
                btn.disabled    = false;
                btn.textContent = 'Enviar';
            }
        })
        .catch(() => {
            alert('Error de conexión.');
            btn.disabled    = false;
            btn.textContent = 'Enviar';
        });
    });
});
</script>