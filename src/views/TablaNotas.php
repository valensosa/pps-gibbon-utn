<?php

/**
 * Vista parcial para la tabla de notas
 * Espera las variables: $student, $pagination
 */

$materias = $result['materias'] ?? [];
$totalPaginas = $pagination['totalPages'];
$paginaActual = $pagination['currentPage'];
$offset = $pagination['offset'];
$totalMaterias = $pagination['totalItems'];
$materiasPorPagina = $pagination['perPage'];
?>

<div class="content-block student-block" data-dni="<?= htmlspecialchars($student['dni']) ?>">
    <h2><?php echo htmlspecialchars($student['nombre'] . ' ' . $student['apellido'] . ' - ' . $student['dni']); ?></h2>

    <?php if (!empty($materias)): ?>
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
                    <?php foreach ($materias as $materia): ?>
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
                    echo '<a href="?' . http_build_query($queryParams) . '" class="button">&laquo; Anterior</a>';
                }

                for ($i = 1; $i <= $totalPaginas; $i++) {
                    $queryParams['page'] = $i;
                    $activeClass = ($i == $paginaActual) ? 'active' : '';
                    echo '<a href="?' . http_build_query($queryParams) . '" class="button ' . $activeClass . '">' . $i . '</a>';
                }

                if ($paginaActual < $totalPaginas) {
                    $queryParams['page'] = $paginaActual + 1;
                    echo '<a href="?' . http_build_query($queryParams) . '" class="button">Siguiente &raquo;</a>';
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