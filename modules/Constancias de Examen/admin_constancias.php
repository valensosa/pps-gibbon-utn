<?php

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use App\Controllers\AdminConstanciasController;
use App\Infrastructure\Repository\FirestoreRepository;
use App\Infrastructure\Repository\StudentRepository;

$credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
$firestoreRepo   = new FirestoreRepository($credentialsPath);
$studentRepo     = new StudentRepository($connection2);

$controller = new AdminConstanciasController(
    $session,
    $page,
    $guid,
    $firestoreRepo,
    $studentRepo
);

$controller->handle();