<?php
require_once dirname(__DIR__, 4) . '/gibbon.php';
require_once dirname(__DIR__, 2) . '/moduleFunctions.php';

global $connection2;
// Obtener el gibbonPersonID del estudiante
$gibbonPersonID = $_GET['gibbonPersonID'] ?? null;
if (!$gibbonPersonID) {
    echo '<div class="alert alert-danger">No se pudo identificar al estudiante.</div>';
    exit;
}

// Get student DNI from gibbonPersonDocument table
$sqlTipo = "SELECT gibbonPersonalDocumentTypeID FROM gibbonPersonalDocumentType WHERE name = 'Documento'";
$stmtTipo = $connection2->prepare($sqlTipo);
$stmtTipo->execute();
$tipoRow = $stmtTipo->fetch();

if (!$tipoRow) {
    echo '<div class="alert alert-danger">No se encontró el tipo de documento "Documento".</div>';
    exit;
}

$tipoID = $tipoRow['gibbonPersonalDocumentTypeID'];

$sqlDoc = "SELECT documentNumber FROM gibbonPersonalDocument 
           WHERE foreignTable = 'gibbonPerson' 
           AND foreignTableID = :gibbonPersonID 
           AND gibbonPersonalDocumentTypeID = :tipoID LIMIT 1";
$stmtDoc = $connection2->prepare($sqlDoc);
$stmtDoc->execute([
    'gibbonPersonID' => $gibbonPersonID,
    'tipoID' => $tipoID
]);

if ($stmtDoc->rowCount() != 1) {
    echo '<div class="alert alert-danger">No se encontró el documento del estudiante.</div>';
    exit;
}

$rowDoc = $stmtDoc->fetch();
$dniAlumno = $rowDoc['documentNumber'];

// Obtener constancias
$constancias = getStudentConstancias($dniAlumno);
$tableData = [];
foreach ($constancias as $doc) {
    $data = parseFirestoreDocument($doc);
    $data['constanciaId'] = getFirestoreDocumentId($doc);
    $tableData[] = $data;
}

// Custom sort
usort($tableData, function ($a, $b) {
    $statusOrder = [
        'pendiente' => 1,
        'completado' => 2,
        'rechazado' => 3,
    ];

    $aOrder = $statusOrder[$a['estado']] ?? 99;
    $bOrder = $statusOrder[$b['estado']] ?? 99;

    if ($aOrder !== $bOrder) {
        return $aOrder <=> $bOrder;
    }

    $aDate = strtotime($a['fechaPedido'] ?? 0);
    $bDate = strtotime($b['fechaPedido'] ?? 0);

    return $bDate <=> $aDate;
});

// Manual pagination
$pageNumber = $_GET['page'] ?? 1;
$rowsPerPage = 10;
$totalRows = count($tableData);
$totalPages = ceil($totalRows / $rowsPerPage);
$pageNumber = max(1, min($pageNumber, $totalPages));
$offset = ($pageNumber - 1) * $rowsPerPage;
$paginatedData = array_slice($tableData, $offset, $rowsPerPage);

// Renderizar la tabla
use Gibbon\Tables\DataTable;
$table = DataTable::create('constancias');
$table->setTitle(__('Mis Constancias'));

$table->addColumn('materia', __('Materia'))
    ->format(function ($row) { 
        return htmlspecialchars($row['examen']['materia'] ?? ''); 
    });

$table->addColumn('presentarAnte', __('Presentar Ante'))
    ->format(function ($row) { 
        return htmlspecialchars($row['presentarAnte'] ?? ''); 
    });

$table->addColumn('fechaExamen', __('Fecha del Examen'))
    ->format(function ($row) { 
        return formatTimestamp($row['examen']['fechaExamen']); 
    });

$table->addColumn('fechaPedido', __('Fecha de Solicitud'))
    ->format(function ($row) { 
        return formatTimestamp($row['fechaPedido']); 
    });

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
        return '<span class="badge ' . $class . '">' . htmlspecialchars($estado) . '</span>';
    });

// Columna de acciones sin usar addActionColumn()
$table->addColumn('acciones', __('Acciones'))
    ->notSortable()
    ->format(function ($row) {
        if ($row['estado'] == 'completado' && !empty($row['pdfUrl'])) {
            return '<a href="'.htmlspecialchars($row['pdfUrl']).'" target="_blank" class="button button--primary">Ver Constancia</a>';
        }
        return '<span class="text-muted">-</span>';
    });

// CSS para mejorar el espaciado
echo '<style>
    #constancias table td, 
    #constancias table th { 
        text-align: center; 
        vertical-align: middle; 
        padding: 12px 15px !important;
    }
    #constancias table td:last-child,
    #constancias table th:last-child {
        min-width: 150px;
    }
    .badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 500;
        display: inline-block;
    }
    .badge-warning {
        background-color: #f0ad4e;
        color: white;
    }
    .badge-success {
        background-color: #5cb85c;
        color: white;
    }
    .badge-danger {
        background-color: #d9534f;
        color: white;
    }
    .button--primary {
        padding: 8px 16px;
        font-size: 14px;
    }
</style>';

echo $table->render($paginatedData); 

// Pagination controls
if ($totalPages > 1) {
    echo '<div class="pagination-controls" style="text-align: center; margin-top: 20px;">';
    
    if ($pageNumber > 1) {
        echo '<a href="#" data-page="'.($pageNumber - 1).'" class="button button--primary page-link" style="margin-right: 10px;">&laquo; Anterior</a>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        $activeStyle = ($i == $pageNumber) ? 'background-color: #935EE1; color: white; border-color: #935EE1;' : '';
        echo '<a href="#" data-page="'.$i.'" class="button page-link" style="margin: 0 5px; '.$activeStyle.'">'.$i.'</a>';
    }

    if ($pageNumber < $totalPages) {
        echo '<a href="#" data-page="'.($pageNumber + 1).'" class="button button--primary page-link" style="margin-left: 10px;">Siguiente &raquo;</a>';
    }
    echo '</div>';
}