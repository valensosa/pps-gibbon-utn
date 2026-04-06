<?php

require_once dirname(__DIR__, 3) . '/gibbon.php';

use App\controllers\TableController;
use App\infrastructure\repository\StudentRepository;
use App\infrastructure\repository\FirestoreRepository;

$credentialsPath = dirname(__DIR__) . '/credentials/firebase-credentials.json';
$studentRepo     = new StudentRepository($connection2);
$firestoreRepo   = new FirestoreRepository($credentialsPath);

$controller = new TableController($studentRepo, $firestoreRepo);
echo $controller->handle();