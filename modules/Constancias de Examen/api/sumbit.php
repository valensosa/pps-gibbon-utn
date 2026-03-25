<?php

require_once dirname(__DIR__, 3) . '/gibbon.php';
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use App\Infrastructure\Repository\StudentRepository;
use App\Infrastructure\Repository\FirestoreRepository;

header('Content-Type: application/json');

$gibbonPersonID = (int) $session->get('gibbonPersonID');
if (!$gibbonPersonID) {
    echo json_encode(['success' => false, 'message' => 'No se pudo identificar al usuario.']);
    exit;
}

$materia       = trim($_POST['materia']       ?? '');
$fechaExamen   = trim($_POST['fechaExamen']   ?? '');
$presentarAnte = trim($_POST['presentarAnte'] ?? '');

if (empty($materia) || empty($fechaExamen) || empty($presentarAnte)) {
    echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos.']);
    exit;
}

try {
    $studentRepo = new StudentRepository($connection2);

    $student = $studentRepo->getByPersonId($gibbonPersonID);
    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'No se pudo obtener la información del estudiante.']);
        exit;
    }

    $dni = $studentRepo->getDniByPersonId($gibbonPersonID);
    if (!$dni) {
        echo json_encode(['success' => false, 'message' => 'No se encontró el documento del estudiante.']);
        exit;
    }

    $credentialsPath = dirname(__DIR__) . '/credentials/firebase-credentials.json';
    $firestoreRepo   = new FirestoreRepository($credentialsPath);

    $firestoreRepo->create([
        'dniAlumno'    => $dni,
        'nombre'       => $student['firstName'] . ' ' . $student['surname'],
        'email'        => $student['email'],
        'materia'      => $materia,
        'fechaExamen'  => $fechaExamen,
        'presentarAnte'=> $presentarAnte,
        'fechaPedido'  => date('Y-m-d')
    ]);

    echo json_encode(['success' => true, 'message' => 'Solicitud de constancia enviada correctamente.']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al enviar la solicitud: ' . $e->getMessage()]);
}