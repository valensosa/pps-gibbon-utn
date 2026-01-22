<?php
require_once '../gibbon.php';
require_once __DIR__ . '/infrastructure/Repository/StudentRepository.php';
require_once __DIR__ . '/services/StudentService.php';
require_once __DIR__ . '/controllers/GradesController.php';

use NotasUTNAPI\Infrastructure\Repository\StudentRepository;
use NotasUTNAPI\Services\StudentService;
use NotasUTNAPI\Controllers\GradesController;

header('Content-Type: text/html; charset=utf-8');

// 1. Inicialización de capas (Dependency Injection manual)
$repository = new StudentRepository($connection2);
$service = new StudentService($repository);
$controller = new GradesController($service);

// 2. Procesar request
$result = $controller->handleRequest($_GET);

if (isset($result['error'])) {
    echo '<div class="alert alert-warning">' . htmlspecialchars($result['error']) . '</div>';
    exit;
}

// 3. Extraer datos para la vista
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

// 4. Renderizar la vista (Template)
require __DIR__ . '/views/gradesTable.php';