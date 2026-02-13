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
        return
    });