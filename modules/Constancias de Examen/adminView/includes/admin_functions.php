<?php
/**
 * Funciones específicas para la administración de constancias
 */

require_once dirname(__DIR__, 2) . '/moduleFunctions.php';

/**
 * Maneja la subida de una constancia
 * 
 * @param string $constanciaId ID de la constancia
 * @param string $dniAlumno DNI del alumno
 * @param string $materia Nombre de la materia
 * @param array $file Archivo subido ($_FILES['file'])
 * @return array Resultado de la operación
 */
function handleConstanciaUpload($constanciaId, $dniAlumno, $materia, $file) {
    try {
        if (empty($constanciaId) || empty($dniAlumno) || empty($materia)) {
            throw new Exception('Faltan datos necesarios');
        }
        
        if (empty($file['tmp_name'])) {
            throw new Exception('No se ha seleccionado ningún archivo');
        }
        
        $url = uploadConstanciaPDF($constanciaId, $dniAlumno, $materia, $file['tmp_name']);
        
        return [
            'success' => true,
            'url' => $url,
            'message' => 'Constancia subida correctamente'
        ];
    } catch (Exception $e) {
        error_log('Error al subir la constancia: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al subir la constancia: ' . $e->getMessage()
        ];
    }
}

// Función de notificación eliminada - no se envían notificaciones automáticas

function getStudentInfoByDNI($dniAlumno) {
    global $connection2;
    
    // Paso 1: Obtener el ID del tipo de documento "Documento"
    $sqlTipo = "SELECT gibbonPersonalDocumentTypeID FROM gibbonPersonalDocumentType WHERE name = 'Documento'";
    $stmtTipo = $connection2->prepare($sqlTipo);
    $stmtTipo->execute();
    $tipoRow = $stmtTipo->fetch();

    if (!$tipoRow) {
        return null;
    }

    $tipoID = $tipoRow['gibbonPersonalDocumentTypeID'];

    // Paso 2: Buscar el usuario por número de documento
    $sql = "SELECT gp.gibbonPersonID, gp.firstName, gp.surname, gp.email, gp.username 
            FROM gibbonPerson gp 
            JOIN gibbonPersonalDocument gpd ON gp.gibbonPersonID = gpd.foreignTableID 
            WHERE gpd.documentNumber = :dniAlumno 
            AND gpd.foreignTable = 'gibbonPerson'
            AND gpd.gibbonPersonalDocumentTypeID = :tipoID 
            AND gp.status = 'Full' 
            LIMIT 1";
    
    $stmt = $connection2->prepare($sql);
    $stmt->execute([
        'dniAlumno' => $dniAlumno,
        'tipoID' => $tipoID
    ]);
    
    if ($stmt->rowCount() != 1) {
        return null;
    }
    
    return $stmt->fetch();
} 