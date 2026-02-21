<?php

// Inicializar el entorno de Gibbon (incluye conexión a BD y sesión)
require_once __DIR__ . '/../../gibbon.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\DependencyFactory;

header('Content-Type: text/html; charset=utf-8');

try {
    // 1. Inicialización de capas mediante Factory
    $controller = DependencyFactory::createControladorNotas();

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
    $materiasPaginadas = $result['materias'] ?? [];
    $totalMaterias = $pagination['totalItems'] ?? 0;
    $totalPaginas = $pagination['totalPages'] ?? 0;
    $paginaActual = $pagination['currentPage'] ?? 1;
    $offset = $pagination['offset'] ?? 0;
    $materiasPorPagina = $pagination['perPage'] ?? 10;

    // 5. Renderizar la vista (Template)
    require __DIR__ . '/views/DatosTablaNotas.php';
} catch (\Throwable $e) {
    if (isset($_GET['action']) && $_GET['action'] === 'search') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
