<?php

use App\DependencyFactory;

header('Content-Type: text/html; charset=utf-8');

// 1. Inicialización de capas mediante Factory
$controller = DependencyFactory::createControladorNotas($connection2);

// 2. Verificar si es una búsqueda de estudiantes (JSON)
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    header('Content-Type: application/json; charset=utf-8');
    $results = $controller->searchStudents($_GET);
    echo json_encode($results);
    exit;
}

// 3. Procesar request
$result = $controller->handleRequest($_GET);

if (isset($result['error'])) {
    echo '<div class="alert alert-warning">' . htmlspecialchars($result['error']) . '</div>';
    exit;
}

// 4. Extraer datos para la vista
$studentData = $result['student'];
$pagination = $result['pagination'];

$nombre = $studentData['nombre'];
$apellido = $studentData['apellido'];
$materiasPaginadas = $pagination['data'];
$totalMaterias = $pagination['totalItems'];
$totalPaginas = $pagination['totalPages'];
$paginaActual = $pagination['currentPage'];
$offset = $pagination['offset'];
$materiasPorPagina = $pagination['perPage'];

// 5. Renderizar la vista (Template)
require __DIR__ . '/views/DatosTablaNotas.php';
