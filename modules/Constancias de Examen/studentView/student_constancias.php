<?php

require_once dirname(__DIR__) . '/moduleFunctions.php';
require_once __DIR__ . '/../controllers/StudentConstanciasController.php';

$controller = new StudentConstanciasController(
    $connection2,
    $session,
    $page,
    $guid
);

$controller->handle();
