<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once __DIR__ . '/moduleFunctions.php';

use App\infrastructure\repository\StudentRepository;
use App\infrastructure\repository\FirestoreRepository;
use App\controllers\AdminConstanciasController;
use App\controllers\StudentConstanciasController;

$gibbonPersonID = (int) $session->get('gibbonPersonID');

if (!$gibbonPersonID) {
    $page->addError(__('No se pudo identificar al usuario.'));
    return;
}

$studentRepo = new StudentRepository($connection2);
$userRole    = $studentRepo->getUserRoleByPersonId($gibbonPersonID);

if (!$userRole) {
    $page->addError(__('No se pudo determinar el rol del usuario.'));
    return;
}

switch ($userRole) {

    case 'Administrator':
    case 'Support':
        $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
        $firestoreRepo   = new FirestoreRepository($credentialsPath);

        $controller = new AdminConstanciasController(
            $session,
            $page,
            $guid,
            $firestoreRepo,
            $studentRepo
        );
        $controller->handle();
        break;

    case 'Student':
        $controller = new StudentConstanciasController(
            $connection2,
            $session,
            $page,
            $guid
        );
        $controller->handle();
        break;

    default:
        $page->addError(__('No tienes permisos para acceder a este módulo.'));
        break;
}