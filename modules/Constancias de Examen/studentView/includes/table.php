<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// DEBUG
echo "<!-- DEBUG DNI Alumno: " . htmlspecialchars($dniAlumno) . " -->";

// Obtener constancias
$constancias = getStudentConstancias($dniAlumno);

echo "<!-- DEBUG Total documentos de Firebase: " . count($constancias) . " -->";

$tableData = [];
foreach ($constancias as $doc) {
    $data = parseFirestoreDocument($doc);
    $data['constanciaId'] = getFirestoreDocumentId($doc);
    $tableData[] = $data;
}

echo "<!-- DEBUG Datos parseados: " . count($tableData) . " -->";
if (!empty($tableData)) {
    echo "<!-- DEBUG Primer registro: " . htmlspecialchars(json_encode($tableData[0])) . " -->";
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

// Renderizar tabla manualmente
echo '<h2>Mis Constancias</h2>';

if (empty($paginatedData)) {
    echo '<div class="alert alert-info">No tienes constancias registradas aún.</div>';
} else {
    echo '<table class="fullWidth colorOddEven" cellspacing="0" style="width: 100%;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th style="width: 25%; text-align: left;">Materia</th>';
    echo '<th style="width: 15%; text-align: center;">Presentar Ante</th>';
    echo '<th style="width: 13%; text-align: center;">Fecha del Examen</th>';
    echo '<th style="width: 13%; text-align: center;">Fecha de Solicitud</th>';
    echo '<th style="width: 12%; text-align: center;">Estado</th>';
    echo '<th style="width: 15%; text-align: center;">Acciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    foreach ($paginatedData as $row) {
        echo '<tr>';
        
        // Materia
        echo '<td style="text-align: left;">' . htmlspecialchars($row['examen']['materia'] ?? '') . '</td>';
        
        // Presentar Ante
        echo '<td style="text-align: center;">' . htmlspecialchars($row['presentarAnte'] ?? '') . '</td>';
        
        // Fecha Examen
        echo '<td style="text-align: center;">' . formatTimestamp($row['examen']['fechaExamen'] ?? '') . '</td>';
        
        // Fecha Solicitud
        echo '<td style="text-align: center;">' . formatTimestamp($row['fechaPedido'] ?? '') . '</td>';
        
        // Estado
        $estadoCompleto = $row['estado'] ?? 'pendiente';
        $estado = ucfirst($estadoCompleto);
        $class = '';
        switch (strtolower($estadoCompleto)) {
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
        echo '<td style="text-align: center;"><span class="badge ' . $class . '">' . htmlspecialchars($estado) . '</span></td>';
        
        // Acciones
        echo '<td style="text-align: center;">';
        if ($estadoCompleto == 'completado' && !empty($row['pdfUrl'])) {
            echo '<a href="'.htmlspecialchars($row['pdfUrl']).'" target="_blank" class="button button--primary">Ver Constancia</a>';
        } else {
            echo '<span class="text-muted">-</span>';
        }
        echo '</td>';
        
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}

// CSS
echo '<style>
    .badge {
        padding: 4px 8px;
        border-radius: 3px;
        font-weight: 500;
        font-size: 12px;
        display: inline-block;
        white-space: nowrap;
        line-height: 1.2;
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
    table.fullWidth td {
        vertical-align: middle !important;
        padding: 10px 8px !important;
    }
</style>';

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