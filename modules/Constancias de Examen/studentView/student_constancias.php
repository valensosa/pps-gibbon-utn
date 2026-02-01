<?php

require_once dirname(__DIR__) . '/moduleFunctions.php';
require_once __DIR__ . '/../controllers/student_constancias_controller.php';

$controller = new StudentConstanciasController(
    $connection2,
    $session,
    $page,
    $guid
);

$controller->handle();
