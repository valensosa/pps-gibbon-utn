<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Cargar el autoloader de la raíz
require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar la arquitectura en capas
use App\DependencyFactory;

$page->breadcrumbs->add(__('Notas de Estudiantes API'));

// Obtener el contexto actual para el controlador
$context = [
    'gibbonPersonID' => $session->get('gibbonPersonID'),
    'selected_dni'   => $_GET['student_dni'] ?? '',
    'page'           => $_GET['page'] ?? 1
];

// Inicialización de capas mediante Factory
$controller = DependencyFactory::createControladorNotas($connection2);

// Manejar el flujo completo
$result = $controller->handleFullFlow($context);


// // Pasamos $result a la vista BuscadorNotas para que ella decida qué mostrar
require __DIR__ . '/../../src/views/BuscadorNotas.php';
