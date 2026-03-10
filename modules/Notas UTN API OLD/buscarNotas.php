<?php
require_once '../../gibbon.php';
require_once 'moduleFunctions.php';

header('Content-Type: text/html; charset=utf-8');

$studentDni = isset($_GET['student_dni']) ? trim($_GET['student_dni']) : '';
if (!$studentDni) {
    echo '<div class="alert alert-warning">Debe ingresar un DNI.</div>';
    exit;
}

// Buscar nombre y apellido en Gibbon usando las nuevas queries
$nombre = '';
$apellido = '';
try {
    $studentName = GibbonQueries::getStudentNameByDNI($connection2, $studentDni);
    if ($studentName) {
        $nombre = $studentName['firstName'];
        $apellido = $studentName['surname'];
    }
} catch (Exception $e) {
    error_log("Error al buscar estudiante por DNI: " . $e->getMessage());
}

$apiData = getStudentDataFromAPI($studentDni);
if (!$apiData) {
    echo '<div class="alert alert-warning">No se encontraron notas para el DNI ingresado.</div>';
    exit;
}

// Formatear datos como en index.php
$studentData = formatStudentData($apiData, $studentDni);
if (!$studentData || empty($studentData['materias'])) {
    echo '<div class="alert alert-warning">No se encontraron notas para el DNI ingresado.</div>';
    exit;
}

// Ordenar materias por fecha (descendente - más reciente primero)
$materias = $studentData['materias'];
usort($materias, function($a, $b) {
    $fechaA = strtotime($a['fecha'] ?? '1970-01-01');
    $fechaB = strtotime($b['fecha'] ?? '1970-01-01');
    return $fechaB <=> $fechaA; // Descendente
});

// Filtrar solo materias con actividad_nombre
$materiasConActividad = array_filter($materias, function($materia) {
    return !empty($materia['actividad_nombre']);
});

// Paginación
$materiasPorPagina = 10;
$totalMaterias = count($materiasConActividad);
$totalPaginas = ceil($totalMaterias / $materiasPorPagina);
$paginaActual = $_GET['page'] ?? 1;
$paginaActual = max(1, min($paginaActual, $totalPaginas));
$offset = ($paginaActual - 1) * $materiasPorPagina;
$materiasPaginadas = array_slice($materiasConActividad, $offset, $materiasPorPagina);

// Mostrar tabla
?>
<div class="content-block student-block" data-dni="<?= htmlspecialchars($studentData['dni']) ?>">
    <h2><?= htmlspecialchars(trim($nombre . ' ' . $apellido . ' - ' . $studentData['dni'])) ?></h2>
    
    <?php if ($totalMaterias > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered grades-table">
                <thead>
                    <tr>
                        <th>Título Araucano</th>
                        <th>Título Nombre</th>
                        <th>Plan Vigente</th>
                        <th>Actividad</th>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Nota</th>
                        <th>Resultado</th>
                        <th>Promedio</th>
                        <th>Forma Aprobación</th>
                        <th>Optativa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiasPaginadas as $materia): ?>
                    <tr>
                        <td><?= htmlspecialchars($materia['titulo_araucano'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['titulo_nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['plan_vigente'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['actividad_nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['actividad_codigo'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['fecha'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['nota'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['resultado'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['promedio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['forma_aprobacion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($materia['es_optativa'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPaginas > 1): ?>
            <div class="pagination-controls">
                <?php
                $queryParams = $_GET;
                
                if ($paginaActual > 1) {
                    $queryParams['page'] = $paginaActual - 1;
                    echo '<a href="javascript:void(0)" onclick="loadPage(' . $queryParams['page'] . ')" class="button">&laquo; Anterior</a>';
                }
                
                for ($i = 1; $i <= $totalPaginas; $i++) {
                    $activeClass = ($i == $paginaActual) ? 'active' : '';
                    echo '<a href="javascript:void(0)" onclick="loadPage(' . $i . ')" class="button ' . $activeClass . '">' . $i . '</a>';
                }
                
                if ($paginaActual < $totalPaginas) {
                    $queryParams['page'] = $paginaActual + 1;
                    echo '<a href="javascript:void(0)" onclick="loadPage(' . $queryParams['page'] . ')" class="button">Siguiente &raquo;</a>';
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 10px; color: #6c757d; font-size: 0.9rem;">
            Mostrando <?= $offset + 1 ?>-<?= min($offset + $materiasPorPagina, $totalMaterias) ?> de <?= $totalMaterias ?> materias
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 20px; color: #6c757d;">
            No se encontraron materias con actividades para este estudiante.
        </div>
    <?php endif; ?>
</div>

<style>
.grades-table thead tr,
.grades-table thead th,
.grades-table thead tr th {
    background-color: #935EE1 !important;
    color: #fff !important;
}
.grades-table td, .grades-table th {
    text-align: center;
}
</style> 