<?php

require_once dirname(__DIR__, 3) . '/gibbon.php';

use App\infrastructure\repository\StudentRepository;

header('Content-Type: application/json');

$gibbonPersonID = $session->get('gibbonPersonID');
if (!$gibbonPersonID) {
    echo json_encode(['error' => 'No se pudo identificar al usuario.']);
    exit;
}

if (!$connection2) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

$searchTerm = trim($_GET['q'] ?? '');

if (strlen($searchTerm) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $studentRepo = new StudentRepository($connection2);
    echo json_encode($studentRepo->searchCourses($searchTerm));
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al buscar materias: ' . $e->getMessage()]);
}