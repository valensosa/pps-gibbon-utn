<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\controllers\StudentConstanciasController;

$controller = new StudentConstanciasController(
    $connection2,
    $session,
    $page,
    $guid
);

$controller->handle();