<?php
require_once dirname(__DIR__, 4) . '/gibbon.php';
require_once dirname(__DIR__, 2) . '/moduleFunctions.php';

header('Content-Type: application/json');

$gibbonPersonID = $session->get('gibbonPersonID');
if (!$gibbonPersonID) {
    echo json_encode(['success' => false, 'message' => 'No se pudo identificar al usuario.']);
    exit;
}

// Get student information using gibbonPersonID
$data = array('gibbonPersonID' => $gibbonPersonID);
$sql = "SELECT gibbonPersonID, username, firstName, surname, email, gibbonRoleIDPrimary 
        FROM gibbonPerson 
        WHERE gibbonPersonID = :gibbonPersonID AND status = 'Full' LIMIT 1";
$result = $connection2->prepare($sql);
$result->execute($data);

if ($result->rowCount() != 1) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener la información del estudiante.']);
    exit;
}

$student = $result->fetch();
$username = $student['username'];
$firstName = $student['firstName'];
$surname = $student['surname'];
$email = $student['email'];

// Get student DNI from gibbonPersonalDocument table
// Paso 1: Obtener el ID del tipo de documento "Documento"
$sqlTipo = "SELECT gibbonPersonalDocumentTypeID FROM gibbonPersonalDocumentType WHERE name = 'Documento'";
$stmtTipo = $connection2->prepare($sqlTipo);
$stmtTipo->execute();
$tipoRow = $stmtTipo->fetch();

if (!$tipoRow) {
    echo json_encode(['success' => false, 'message' => 'No se encontró el tipo de documento "Documento".']);
    exit;
}

$tipoID = $tipoRow['gibbonPersonalDocumentTypeID'];

// Paso 2: Buscar el documento del usuario
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
    echo json_encode(['success' => false, 'message' => 'No se encontró el documento del estudiante.']);
    exit;
}

$rowDoc = $stmtDoc->fetch();
$dniAlumno = $rowDoc['documentNumber'];

$materia = $_POST['materia'] ?? '';
$fechaExamen = $_POST['fechaExamen'] ?? '';
$presentarAnte = $_POST['presentarAnte'] ?? '';

if (empty($materia) || empty($fechaExamen) || empty($presentarAnte)) {
    echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos.']);
    exit;
}

try {
    $data = [
        'dniAlumno' => $dniAlumno,
        'nombre' => $firstName . ' ' . $surname,
        'email' => $email,
        'materia' => $materia,
        'fechaExamen' => $fechaExamen,
        'presentarAnte' => $presentarAnte,
        'fechaPedido' => date('Y-m-d')
    ];
    createConstancia($data);
    echo json_encode(['success' => true, 'message' => 'Solicitud de constancia enviada correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al enviar la solicitud: ' . $e->getMessage()]);
} 