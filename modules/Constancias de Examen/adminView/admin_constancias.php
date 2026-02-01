<?php

use Modules\Constancias\Controllers\AdminConstanciasController;

require_once __DIR__ . '/../controllers/AdminConstanciasController.php';

// Gibbon ya te da esto:
global $connection2, $session, $page, $guid;

$controller = new AdminConstanciasController(
    $connection2,
    $session,
    $page,
    $guid
);

$controller->handle();
