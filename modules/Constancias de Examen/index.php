<?php

use Gibbon\Forms\Form;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;

// Module includes (OBLIGATORIO)
require_once __DIR__ . '/moduleFunctions.php';

// 1️⃣ Obtener usuario actual
$gibbonPersonID = $session->get('gibbonPersonID');

if (!$gibbonPersonID) {
    $page->addError(__('No se pudo identificar al usuario.'));
    return;
}

// 2️⃣ Obtener rol del usuario
$sql = "
    SELECT gibbonRole.name
    FROM gibbonPerson
    JOIN gibbonRole 
        ON gibbonPerson.gibbonRoleIDPrimary = gibbonRole.gibbonRoleID
    WHERE gibbonPerson.gibbonPersonID = :id
    LIMIT 1
";

$stmt = $connection2->prepare($sql);
$stmt->execute(['id' => $gibbonPersonID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$userRole = $row ? $row['name'] : null;

if (!$userRole) {
    $page->addError(__('No se pudo determinar el rol del usuario.'));
    return;
}

// 3️⃣ Router interno por rol (SIN redirects, SIN isActionAccessible)
switch ($userRole) {

    case 'Administrator':
    case 'Support':
        $adminView = __DIR__ . '/adminView/admin_constancias.php';
        if (file_exists($adminView)) {
            include $adminView;
        } else {
            $page->addError(__('No se encontró la vista de administración.'));
        }
        break;

    case 'Student':
        $studentView = __DIR__ . '/studentView/student_constancias.php';
        if (file_exists($studentView)) {
            include $studentView;
        } else {
            $page->addError(__('No se encontró la vista del estudiante.'));
        }
        break;

    default:
        $page->addError(__('No tienes permisos para acceder a este módulo.'));
        break;
}
