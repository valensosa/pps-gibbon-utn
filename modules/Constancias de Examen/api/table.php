<?php

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__, 3) . '/gibbon.php';
use App\controllers\TableController;

$controller = new TableController();
echo $controller->handle();