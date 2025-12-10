<?php
require_once dirname(__DIR__, 3) . '/gibbon.php';
require_once dirname(__DIR__, 2) . '/moduleFunctions.php';
use Gibbon\Forms\Form;
use Gibbon\Tables\DataTable;

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'tableHtml' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $constanciaId = $_POST['constanciaId'] ?? '';
    $dniAlumno = $_POST['dniAlumno'] ?? '';
    $materia = $_POST['materia'] ?? '';
    
    if (empty($constanciaId) || empty($dniAlumno) || empty($materia)) {
        $response['message'] = 'Faltan datos necesarios.';
    } else if (!isset($_FILES['file']) || empty($_FILES['file']['tmp_name'])) {
        $response['message'] = 'No se ha seleccionado ningún archivo.';
    } else {
        try {
            $url = uploadConstanciaPDF($constanciaId, $dniAlumno, $materia, $_FILES['file']['tmp_name']);
            $response['success'] = true;
            $response['message'] = 'Constancia subida correctamente.';
        } catch (Exception $e) {
            $response['message'] = 'Error al subir la constancia: ' . $e->getMessage();
        }
    }
} else {
    $response['message'] = 'Solicitud inválida.';
}

// Actualizar tabla
// Obtener todas las constancias para renderizar la tabla actualizada
$constancias = getAllConstancias();
$tableData = [];
foreach ($constancias as $doc) {
    $data = parseFirestoreDocument($doc);
    $data['constanciaId'] = getFirestoreDocumentId($doc);
    // Obtener email del alumno usando la nueva función
    $data['email'] = '';
    if (!empty($data['dniAlumno'])) {
        $studentInfo = getStudentInfoByDNI($data['dniAlumno']);
        if ($studentInfo) {
            $data['email'] = $studentInfo['email'];
        }
    }
    $tableData[] = $data;
}

// Custom sort to order by state (pending first) and then by date
usort($tableData, function ($a, $b) {
    $statusOrder = [
        'pendiente' => 1,
        'completado' => 2,
    ];

    $aOrder = $statusOrder[$a['estado']] ?? 99;
    $bOrder = $statusOrder[$b['estado']] ?? 99;

    if ($aOrder !== $bOrder) {
        return $aOrder <=> $bOrder;
    }

    // If status is the same, sort by date descending
    $aDate = strtotime($a['fechaPedido'] ?? 0);
    $bDate = strtotime($b['fechaPedido'] ?? 0);

    return $bDate <=> $aDate;
});

$table = DataTable::create('constancias');
$table->setTitle(__('Solicitudes de Constancias'));

$table->addColumn('nombre', __('Estudiante'))
    ->setClass('text-center col-estudiante');

$table->addColumn('dniAlumno', __('DNI'))
    ->setClass('text-center col-dni');

$table->addColumn('email', __('Email'))
    ->setClass('text-center col-email');

$table->addColumn('materia', __('Materia'))
    ->setClass('text-center col-materia')
    ->format(function ($row) { return $row['examen']['materia']; });

$table->addColumn('presentarAnte', __('Presentar Ante'))
    ->setClass('text-center col-presentar-ante')
    ->format(function ($row) { return $row['presentarAnte'] ?? ''; });

$table->addColumn('fechaExamen', __('Fecha del Examen'))
    ->setClass('text-center col-fecha-examen')
    ->format(function ($row) { return formatTimestamp($row['examen']['fechaExamen']); });

$table->addColumn('fechaPedido', __('Fecha de Solicitud'))
    ->setClass('text-center col-fecha-solicitud')
    ->format(function ($row) { return formatTimestamp($row['fechaPedido']); });

$table->addColumn('estado', __('Estado'))
    ->setClass('text-center col-estado')
    ->format(function ($row) { 
        $estado = ucfirst($row['estado']);
        $class = '';
        switch(strtolower($estado)) {
            case 'pendiente':
                $class = 'badge-warning';
                break;
            case 'completado':
                $class = 'badge-success';
                break;
        }
        return '<div class="text-center"><span class="badge ' . $class . '">' . $estado . '</span></div>';
    });

$table->addColumn('constancia', __('Constancia'))
    ->setClass('text-center col-constancia')
    ->format(function ($row) {
        if ($row['estado'] == 'pendiente') {
            $formId = 'uploadForm'.$row['constanciaId'];
            
            $html = '<div class="text-center">';
            $html .= '<form id="'.$formId.'" class="inline" method="POST" enctype="multipart/form-data">';
            $html .= '<input type="hidden" name="constanciaId" value="'.$row['constanciaId'].'">';
            $html .= '<input type="hidden" name="dniAlumno" value="'.$row['dniAlumno'].'">';
            $html .= '<input type="hidden" name="materia" value="'.$row['examen']['materia'].'">';
            $html .= '<input type="hidden" name="submit" value="1">';
            $html .= '<label class="upload-button" id="uploadLabel'.$row['constanciaId'].'">';
            $html .= '<input type="file" name="file" accept=".pdf" required>';
            $html .= '<span class="button button--upload">';
            $html .= '<svg class="upload-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
            $html .= '<path d="M11 14.9861C11 15.5384 11.4477 15.9861 12 15.9861C12.5523 15.9861 13 15.5384 13 14.9861V7.82831L16.2428 11.0711L17.657 9.65685L12 4L6.34315 9.65685L7.75736 11.0711L11 7.82831V14.9861Z" fill="currentColor"/>';
            $html .= '<path d="M4 14H6V18H18V14H20V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V14Z" fill="currentColor"/>';
            $html .= '</svg>';
            $html .= '<svg class="check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">';
            $html .= '<path d="M20.664 5.253a1 1 0 0 1 .083 1.411l-10.666 12a1 1 0 0 1 1.495 0l-5.333-6a1 1 0 0 1 1.494-1.328l4.586 5.159 9.92-11.159a1 1 0 0 1 1.411-.083z" fill="currentColor"/>';
            $html .= '</svg>';
            $html .= '<span class="button-text">Subir PDF</span></span>';
            $html .= '</label>';
            $html .= '</form>';
            $html .= '</div>';
            
            return $html;
        } else if (!empty($row['pdfUrl'])) {
            return '<div class="text-center"><a href="'.$row['pdfUrl'].'" target="_blank" class="button button--pdf">Ver PDF</a></div>';
        }
        return '';
    });

$table->addActionColumn()
    ->setClass('text-center col-acciones')
    ->addParam('constanciaId')
    ->format(function ($row, $actions) {
        if ($row['estado'] == 'pendiente') {
            $formId = 'uploadForm'.$row['constanciaId'];
            echo '<div class="text-center"><button type="button" class="button button--primary upload-submit-btn" data-form-id="'.$formId.'" disabled>Enviar</button></div>';
        } else if ($row['estado'] == 'completado') {
            echo '<div class="text-center"><button type="button" class="button button--secondary" disabled>Enviada</button></div>';
        }
    });

$response['tableHtml'] = $table->render($tableData);
echo json_encode($response); 