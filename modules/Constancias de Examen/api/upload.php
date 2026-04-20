<?php
require_once dirname(__DIR__, 3) . '/gibbon.php';

use App\services\UploadService;
use App\services\AdminConstanciasService;
use App\infrastructure\repository\FirestoreRepository;
use App\infrastructure\repository\StudentRepository;
use Gibbon\Tables\DataTable;

header('Content-Type: application/json');

$credentialsPath = dirname(__DIR__, 1) . '/credentials/firebase-credentials.json';
$firestoreRepo   = new FirestoreRepository($credentialsPath);
$studentRepo     = new StudentRepository($connection2);
$uploadService   = new UploadService($firestoreRepo);
$adminService = new AdminConstanciasService($firestoreRepo, $studentRepo);

$response = ['success' => false, 'message' => '', 'tableHtml' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Solicitud inválida.';
    echo json_encode($response);
    exit;
}

$constanciaId = $_POST['constanciaId'] ?? '';
$dniAlumno    = $_POST['dniAlumno']    ?? '';
$materia      = $_POST['materia']      ?? '';

if (empty($constanciaId) || empty($dniAlumno) || empty($materia)) {
    $response['message'] = 'Faltan datos necesarios.';
    echo json_encode($response);
    exit;
}

if (!isset($_FILES['file']) || empty($_FILES['file']['tmp_name'])) {
    $response['message'] = 'No se ha seleccionado ningún archivo.';
    echo json_encode($response);
    exit;
}

$result = $uploadService->handleUpload($constanciaId, $dniAlumno, $materia, $_FILES['file']);
$response['success'] = $result['success'];
$response['message'] = $result['message'];

// Reconstruir tabla actualizada
$data        = $adminService->getViewData();
$solicitudes = $data['solicitudes'];

foreach ($solicitudes as &$row) {
    $row['email'] = '';
    if (!empty($row['dniAlumno'])) {
        $info = $studentRepo->getStudentInfoByDni($row['dniAlumno']);
        if ($info) {
            $row['email'] = $info['email'];
        }
    }
}
unset($row);

ob_start();
include dirname(__DIR__, 3) . '/src/layers/views/admin_constancias_view.php';
$response['tableHtml'] = ob_get_clean();

echo json_encode($response);