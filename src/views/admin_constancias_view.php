<?php

use Gibbon\Tables\DataTable;
use App\Infrastructure\Repository\FirestoreRepository;

// CSS
echo "<link rel='stylesheet' type='text/css' href='" . $session->get('absoluteURL') . "/modules/Constancias de Examen/css/admin.css' />";
?>

<div class="constancias-module">

    <!-- Stats -->
    <div class="stats-container">
        <?php
        $total     = count($solicitudes);
        $pendiente = count(array_filter($solicitudes, fn($r) => $r['estado'] === 'pendiente'));
        $completado = count(array_filter($solicitudes, fn($r) => $r['estado'] === 'completado'));
        $rechazado = count(array_filter($solicitudes, fn($r) => $r['estado'] === 'rechazado'));
        ?>
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
        <?php
        $table = DataTable::create('constancias');
        $table->setTitle(__('Solicitudes de Constancias'));

        $table->addColumn('nombre', __('Estudiante'))
            ->setClass('text-center col-estudiante');

        $table->addColumn('dniAlumno', __('DNI'))
            ->setClass('text-center col-dni');

        $table->addColumn('email', __('Email'))
            ->setClass('text-center col-email');

        $table->addColumn('materia', __('Materia'))
            ->setClass('text-center col-materia')
            ->format(fn($row) => $row['examen']['materia']);

        $table->addColumn('presentarAnte', __('Presentar Ante'))
            ->setClass('text-center col-presentar-ante')
            ->format(fn($row) => $row['presentarAnte'] ?? '');

        $table->addColumn('fechaExamen', __('Fecha del Examen'))
            ->setClass('text-center col-fecha-examen')
            ->format(fn($row) => FirestoreRepository::formatTimestamp($row['examen']['fechaExamen']));

        $table->addColumn('fechaPedido', __('Fecha de Solicitud'))
            ->setClass('text-center col-fecha-solicitud')
            ->format(fn($row) => FirestoreRepository::formatTimestamp($row['fechaPedido']));

        $table->addColumn('estado', __('Estado'))
            ->setClass('text-center col-estado')
            ->format(function ($row) {
                $estado = ucfirst($row['estado']);
                $class  = match(strtolower($row['estado'])) {
                    'pendiente'  => 'badge-warning',
                    'completado' => 'badge-success',
                    'rechazado'  => 'badge-danger',
                    default      => ''
                };
                return '<div class="text-center"><span class="badge ' . $class . '">' . $estado . '</span></div>';
            });

        $table->addColumn('constancia', __('Constancia'))
            ->setClass('text-center col-constancia')
            ->format(function ($row) {
                if ($row['estado'] === 'pendiente') {
                    $formId = 'uploadForm' . $row['constanciaId'];
                    return '
                        <div class="text-center">
                            <form id="' . $formId . '" class="inline" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="constanciaId" value="' . $row['constanciaId'] . '">
                                <input type="hidden" name="dniAlumno" value="' . $row['dniAlumno'] . '">
                                <input type="hidden" name="materia" value="' . $row['examen']['materia'] . '">
                                <label class="upload-button" id="uploadLabel' . $row['constanciaId'] . '">
                                    <input type="file" name="file" accept=".pdf" required>
                                    <span class="button button--upload">
                                        <svg class="upload-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M11 14.9861C11 15.5384 11.4477 15.9861 12 15.9861C12.5523 15.9861 13 15.5384 13 14.9861V7.82831L16.2428 11.0711L17.657 9.65685L12 4L6.34315 9.65685L7.75736 11.0711L11 7.82831V14.9861Z" fill="currentColor"/>
                                            <path d="M4 14H6V18H18V14H20V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V14Z" fill="currentColor"/>
                                        </svg>
                                        <span class="button-text">Subir PDF</span>
                                    </span>
                                </label>
                            </form>
                        </div>';
                }

                if (!empty($row['pdfUrl'])) {
                    return '<div class="text-center"><a href="' . $row['pdfUrl'] . '" target="_blank" class="button button--pdf">Ver PDF</a></div>';
                }

                return '';
            });

        $table->addActionColumn()
            ->setClass('text-center col-acciones')
            ->addParam('constanciaId')
            ->format(function ($row, $actions) {
                $formId = 'uploadForm' . $row['constanciaId'];
                if ($row['estado'] === 'pendiente') {
                    echo '<div class="text-center"><button type="button" class="button button--primary upload-submit-btn" data-form-id="' . $formId . '" disabled>Enviar</button></div>';
                } elseif ($row['estado'] === 'completado') {
                    echo '<div class="text-center"><button type="button" class="button button--secondary" disabled>Enviada</button></div>';
                }
            });

        echo $table->render($solicitudes);
        ?>
    </div>

</div>

<script src="<?= $session->get('absoluteURL') ?>/modules/Constancias de Examen/js/admin.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableContainer = document.getElementById('constanciasTableContainer');

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const status = statusFilter.value.toLowerCase();
        const rows   = tableContainer.querySelectorAll('tbody tr');

        rows.forEach(function (row) {
            const text       = row.textContent.toLowerCase();
            const badge      = row.querySelector('.badge');
            const rowStatus  = badge ? badge.textContent.trim().toLowerCase() : '';

            const matchSearch = !search || text.includes(search);
            const matchStatus = !status || rowStatus === status;

            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Upload via AJAX
    tableContainer.addEventListener('change', function (e) {
        if (e.target.matches('input[type="file"]')) {
            const form        = e.target.closest('form');
            const constanciaId = form.querySelector('[name="constanciaId"]').value;
            const submitBtn   = document.querySelector('[data-form-id="' + form.id + '"]');
            const label       = document.getElementById('uploadLabel' + constanciaId);
            const buttonText  = label.querySelector('.button-text');
            const file        = e.target.files[0];

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

    tableContainer.addEventListener('click', function (e) {
        const btn = e.target.closest('.upload-submit-btn');
        if (!btn) return;

        const formId = btn.getAttribute('data-form-id');
        const form   = document.getElementById(formId);
        if (!form) return;

        btn.disabled = true;
        btn.textContent = 'Enviando...';

        const formData = new FormData(form);

        fetch('modules/Constancias de Examen/api/upload.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                tableContainer.innerHTML = data.tableHtml;
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.textContent = 'Enviar';
            }
        })
        .catch(() => {
            alert('Error de conexión.');
            btn.disabled = false;
            btn.textContent = 'Enviar';
        });
    });
});
</script>