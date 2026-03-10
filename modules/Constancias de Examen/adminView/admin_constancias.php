<?php
use Gibbon\Forms\Form;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;

// Module includes
require_once dirname(__DIR__) . '/moduleFunctions.php';
require_once __DIR__ . '/includes/admin_functions.php';

if (isActionAccessible($guid, $connection2, '/modules/Constancias de Examen/admin_constancias.php') === false) {
    // Access denied
    $page->addError(__('You do not have access to this action.'));
} else {
    // Proceed!
    $page->breadcrumbs->add(__('Gestionar Constancias de Examen'));
    
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

    // Check if user has admin permissions
    if (!in_array($userRole, ['Administrator', 'Support'])) {
        $page->addError(__('Esta página es solo para administradores.'));
        return;
    }

    // Handle file upload before rendering the rest of the page
    if ((isset($_POST['submit']) || isset($_FILES['file'])) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $constanciaId = $_POST['constanciaId'] ?? '';
        $dniAlumno = $_POST['dniAlumno'] ?? '';
        $materia = $_POST['materia'] ?? '';
        
        $result = handleConstanciaUpload($constanciaId, $dniAlumno, $materia, $_FILES['file'] ?? []);
        
        if ($result['success']) {
            // Notificación eliminada - no se envían notificaciones automáticas
            // We are using AJAX, so we can output a simple success message and die.
            // The JS will handle the reload.
            $page->addSuccess(__($result['message']));
        } else {
            $page->addError(__($result['message']));
        }
    }
    
    // Add CSS
    echo "<link rel='stylesheet' type='text/css' href='" . $session->get('absoluteURL') . "/modules/Constancias de Examen/adminView/css/admin.css' />";
    // Font Awesome for icons
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';
    // Add JavaScript for upload functionality
    echo "<script src='" . $session->get('absoluteURL') . "/modules/Constancias de Examen/adminView/js/admin.js'></script>";
    
    echo '<div class="constancias-module">';

    // Get data from Firestore
    $constancias = getAllConstancias();
    $tableData = [];
    foreach ($constancias as $doc) {
        $data = parseFirestoreDocument($doc);
        $data['constanciaId'] = getFirestoreDocumentId($doc);
        $tableData[] = $data;
    }
    
    // Calculate stats from the full dataset
    $stats = [
        'total' => count($tableData),
        'pending' => 0,
        'completed' => 0,
    ];

    foreach ($tableData as $row) {
        if ($row['estado'] == 'pendiente') {
            $stats['pending']++;
        } elseif ($row['estado'] == 'completado') {
            $stats['completed']++;
        }
    }

    // Handle Search & Filtering
    $searchQuery = $_GET['search'] ?? '';
    $statusFilter = $_GET['status'] ?? 'todos';
    $filteredData = $tableData;

    if ($statusFilter !== 'todos' && !empty($statusFilter)) {
        $filteredData = array_filter($filteredData, function($row) use ($statusFilter) {
            return $row['estado'] === $statusFilter;
        });
    }

    if (!empty($searchQuery)) {
        $filteredData = array_filter($filteredData, function($row) use ($searchQuery) {
            $searchLower = strtolower($searchQuery);
            return strpos(strtolower($row['nombre'] ?? ''), $searchLower) !== false ||
                   strpos(strtolower($row['dniAlumno'] ?? ''), $searchLower) !== false ||
                   strpos(strtolower($row['email'] ?? ''), $searchLower) !== false ||
                   strpos(strtolower($row['examen']['materia'] ?? ''), $searchLower) !== false ||
                   strpos(strtolower($row['presentarAnte'] ?? ''), $searchLower) !== false;
        });
    }

    // Custom sort to order by state (pending first) and then by date
    usort($filteredData, function ($a, $b) {
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

    // Display Stats and Filters
    ?>
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon-wrapper total"><i class="fas fa-file-alt"></i></div>
            <div class="stat-content">
                <div class="stat-title">Total Pedidos</div>
                <div class="stat-number"><?= $stats['total'] ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-wrapper pending"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-title">Pendientes</div>
                <div class="stat-number"><?= $stats['pending'] ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-wrapper sent"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-title">Enviadas</div>
                <div class="stat-number"><?= $stats['completed'] ?></div>
            </div>
        </div>
    </div>

    <div class="filter-container">
        <form method="get" class="search-form">
            <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Buscar por alumno, materia o email..." value="<?= htmlspecialchars($searchQuery) ?>" class="search-input">
            </div>
            <div class="filter-box">
                <i class="fas fa-filter filter-icon"></i>
                <select name="status" onchange="this.form.submit()" class="filter-select">
                    <option value="todos" <?= $statusFilter == 'todos' ? 'selected' : '' ?>>Todos los estados</option>
                    <option value="pendiente" <?= $statusFilter == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="completado" <?= $statusFilter == 'completado' ? 'selected' : '' ?>>Enviada</option>
                </select>
            </div>
        </form>
    </div>

    <?php

    // Show table of all requests
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
    
    // Manual pagination on the filtered data
    $pageNumber = $_GET['page'] ?? 1;
    $rowsPerPage = 10;
    $totalRows = count($filteredData);
    $totalPages = ceil($totalRows / $rowsPerPage);
    $pageNumber = max(1, min($pageNumber, $totalPages));
    $offset = ($pageNumber - 1) * $rowsPerPage;
    $paginatedData = array_slice($filteredData, $offset, $rowsPerPage);
    
    echo $table->render($paginatedData);

    // Pagination controls
    if ($totalPages > 1) {
        $queryParams = $_GET;
        echo '<div class="pagination-controls" style="text-align: center; margin-top: 20px;">';
        
        if ($pageNumber > 1) {
            $queryParams['page'] = $pageNumber - 1;
            echo '<a href="?'.http_build_query($queryParams).'" class="button button--primary" style="margin-right: 10px;">&laquo; Anterior</a>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            $queryParams['page'] = $i;
            $activeClass = ($i == $pageNumber) ? 'active' : '';
            $activeStyle = ($i == $pageNumber) ? 'background-color: #935EE1; color: white; border-color: #935EE1;' : '';
            echo '<a href="?'.http_build_query($queryParams).'" class="button '.$activeClass.'" style="margin: 0 5px; '.$activeStyle.'">'.$i.'</a>';
        }

        if ($pageNumber < $totalPages) {
            $queryParams['page'] = $pageNumber + 1;
            echo '<a href="?'.http_build_query($queryParams).'" class="button button--primary" style="margin-left: 10px;">Siguiente &raquo;</a>';
        }
        echo '</div>';
    }
    
    echo '</div>';
} 