<?php
require_once dirname(__DIR__, 4) . '/gibbon.php';
require_once dirname(__DIR__, 2) . '/moduleFunctions.php';

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
    // Verificar si la tabla gibbonCourse existe
    $checkTable = "SHOW TABLES LIKE 'gibbonCourse'";
    $tableResult = $connection2->prepare($checkTable);
    $tableResult->execute();
    
    if ($tableResult->rowCount() == 0) {
        echo json_encode(['error' => 'La tabla gibbonCourse no existe.']);
        exit;
    }
    
    // Buscar materias que coincidan con el término de búsqueda
    $data = array('searchTerm' => '%' . $searchTerm . '%');
    $sql = "SELECT gibbonCourseID, name, nameShort 
            FROM gibbonCourse 
            WHERE (name LIKE :searchTerm OR nameShort LIKE :searchTerm) 
            ORDER BY name ASC 
            LIMIT 10";
    
    $result = $connection2->prepare($sql);
    $result->execute($data);
    
    $courses = [];
    while ($row = $result->fetch()) {
        $course = [
            'id' => $row['gibbonCourseID'],
            'name' => $row['name'],
            'description' => '',
            'code' => $row['nameShort'] ?? '',
            'display' => $row['name'] . ($row['nameShort'] ? ' (' . $row['nameShort'] . ')' : '')
        ];
        
        $courses[] = $course;
    }
    
    echo json_encode($courses);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al buscar materias: ' . $e->getMessage()]);
} 