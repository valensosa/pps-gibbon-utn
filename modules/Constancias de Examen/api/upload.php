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
$uploadService   = new UploadService($firestoreRepo);

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

echo json_encode($response);