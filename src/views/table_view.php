<?php

use Gibbon\Tables\DataTable;

// 🔹 Creamos la tabla (solo presentación)
$table = DataTable::create('constancias');
$table->setTitle(__('Mis Constancias'));

$table->addColumn('materia', __('Materia'))
    ->format(fn($row) => $row['examen']['materia']);

$table->addColumn('presentarAnte', __('Presentar Ante'))
    ->format(fn($row) => $row['presentarAnte'] ?? '');

$table->addColumn('fechaExamen', __('Fecha del Examen'))
    ->format(fn($row) => formatTimestamp($row['examen']['fechaExamen']));

$table->addColumn('fechaPedido', __('Fecha de Solicitud'))
    ->format(fn($row) => formatTimestamp($row['fechaPedido']));

$table->addColumn('estado', __('Estado'))
    ->format(function ($row) {
        $estado = ucfirst($row['estado']);
        $class = '';

        switch (strtolower($row['estado'])) {
            case 'pendiente':
                $class = 'badge-warning';
                break;
            case 'completado':
                $class = 'badge-success';
                break;
            case 'rechazado':
                $class = 'badge-danger';
                break;
        }

        return '<div class="text-center">
                    <span class="badge ' . $class . '">' . $estado . '</span>
                </div>';
    });

$table->addActionColumn()
    ->addParam('constanciaId')
    ->format(function ($row, $actions) {
        if ($row['estado'] === 'completado' && !empty($row['pdfUrl'])) {
            return '<div class="text-center">
                        <a href="'.$row['pdfUrl'].'" target="_blank" 
                           class="button button--primary button--small">
                           Ver Constancia
                        </a>
                    </div>';
        }
    });

// 🔹 estilos (solo visual)
echo '<style>
#constancias table td, 
#constancias table th { 
    text-align: center; 
    vertical-align: middle; 
}
</style>';

// 🔹 render
echo $table->render($paginatedData);
?>

<?php if ($totalPages > 1): ?>
<div class="pagination-controls" style="text-align: center; margin-top: 20px;">

    <?php if ($pageNumber > 1): ?>
        <a href="#" data-page="<?= $pageNumber - 1 ?>" 
           class="button button--primary page-link" 
           style="margin-right: 10px;">
           &laquo; Anterior
        </a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php
            $active = $i == $pageNumber;
            $style = $active ? 'background-color:#935EE1;color:white;border-color:#935EE1;' : '';
        ?>
        <a href="#" 
           data-page="<?= $i ?>" 
           class="button page-link <?= $active ? 'active' : '' ?>" 
           style="margin: 0 5px; <?= $style ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($pageNumber < $totalPages): ?>
        <a href="#" data-page="<?= $pageNumber + 1 ?>" 
           class="button button--primary page-link" 
           style="margin-left: 10px;">
           Siguiente &raquo;
        </a>
    <?php endif; ?>

</div>
<?php endif; ?>