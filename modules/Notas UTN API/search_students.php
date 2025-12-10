<?php
require_once '../../gibbon.php';
require_once __DIR__ . '/moduleFunctions.php';

header('Content-Type: application/json');

// Verificar que el usuario esté autenticado
$gibbonPersonID = $session->get('gibbonPersonID');
if (!$gibbonPersonID) {
    echo json_encode(['error' => 'No se pudo identificar al usuario.']);
    exit;
}

// Verificar conexión a la base de datos
if (!$connection2) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Obtener el término de búsqueda
$searchTerm = $_GET['q'] ?? '';
$searchTerm = trim($searchTerm);

if (strlen($searchTerm) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Usar la nueva función centralizada para buscar estudiantes
    $students = GibbonQueries::searchStudents($connection2, $searchTerm, 10);
    
    echo json_encode($students);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al buscar estudiantes: ' . $e->getMessage()]);
} 