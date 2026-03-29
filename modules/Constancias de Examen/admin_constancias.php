<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\controllers\AdminConstanciasController;
use App\infrastructure\repository\FirestoreRepository;
use App\infrastructure\repository\StudentRepository;

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