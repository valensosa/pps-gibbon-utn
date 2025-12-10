<?php
use Gibbon\Forms\Form;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;

// Module includes
require_once __DIR__ . '/moduleFunctions.php';

if (isActionAccessible($guid, $connection2, '/modules/Constancias de Examen/index.php') === false) {
    // Access denied
    $page->addError(__('You do not have access to this action.'));
} else {
    // Get current user info
    $gibbonPersonID = $session->get('gibbonPersonID');
    $userRole = null;
    
    if ($gibbonPersonID) {
        $data = array('gibbonPersonID' => $gibbonPersonID);
        $sql = "SELECT gibbonRole.name FROM gibbonPerson JOIN gibbonRole ON (gibbonPerson.gibbonRoleIDPrimary = gibbonRole.gibbonRoleID) WHERE gibbonPerson.gibbonPersonID = :gibbonPersonID LIMIT 1";
        $result = $connection2->prepare($sql);
        $result->execute($data);
        $row = $result->fetch();
        $userRole = $row ? $row['name'] : null;
    }

    // Redirect based on user role
    if (in_array($userRole, ['Administrator', 'Support'])) {
        // Redirect to admin view
        header('Location: ' . $session->get('absoluteURL') . '/index.php?q=/modules/Constancias de Examen/admin_constancias.php');
        exit;
    } else if ($userRole === 'Student') {
        // Redirect to student view
        header('Location: ' . $session->get('absoluteURL') . '/index.php?q=/modules/Constancias de Examen/student_constancias.php');
        exit;
    } else {
        // Unauthorized role
        $page->addError(__('No tienes permisos para acceder a este módulo.'));
    }
}
?>
<style>
.button.button--primary {
    background: #935EE1 !important;
    color: #fff !important;
    border: none;
}
.button.button--primary:hover {
    background: #7a3fd1 !important;
}
.modal-constancia {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0; width: 100%; height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.3);
}
.modal-constancia-content {
    background: #fff;
    margin: 5% auto;
    padding: 2em 2em 1em 2em;
    border-radius: 8px;
    width: 100%;
    max-width: 420px;
    position: relative;
    box-shadow: 0 2px 16px rgba(0,0,0,0.15);
}
.modal-constancia-close {
    color: #935EE1;
    position: absolute;
    top: 12px; right: 18px;
    font-size: 2em;
    font-weight: bold;
    cursor: pointer;
}
.form-row { margin-bottom: 1.2em; }
.form-row label { display: block; margin-bottom: 0.3em; font-weight: 500; }
.form-row input[type="text"], .form-row input[type="date"] {
    width: 100%;
    padding: 0.5em;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
}
#solicitudMsg { margin-top: 1em; }
</style> 