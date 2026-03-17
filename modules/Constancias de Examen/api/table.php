<?php

require_once __DIR__ . '/../../../../vendor/autoload.php'; // ajustar si hace falta

use App\Controllers\TableController;

$controller = new TableController();
echo $controller->handle();