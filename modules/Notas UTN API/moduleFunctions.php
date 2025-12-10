<?php

// Incluir configuración de la API UTN
require_once __DIR__ . '/api/config/utn_api_config.php';

// Incluir queries de Gibbon
require_once __DIR__ . '/gibbonQueries/gibbon_queries.php';

function getStudentDataFromAPI($studentID) {
    // Validar y formatear el DNI
    if (!UTNApiUtils::validateDNI($studentID)) {
        error_log("DNI inválido: " . $studentID);
        return null;
    }
    
    $dni = UTNApiUtils::formatDNI($studentID);
    
    // Paso 1: Buscar persona por DNI
    $url = UTNApiQueries::getPersonasByDNI($dni);
    $result = UTNApiUtils::makeRequest($url);
    
    if (!$result['success']) {
        error_log("Error en primera llamada API: " . $result['error']);
        return null;
    }
    
    $data = $result['data'];
    if (empty($data) || !isset($data[0]['persona'])) {
        error_log("No se encontró persona en la respuesta para DNI: " . $dni);
        return null;
    }
    
    $personaId = $data[0]['persona'];
    
    // Paso 2: Obtener datos analíticos
    $url = UTNApiQueries::getDatosAnalitico($personaId);
    $result = UTNApiUtils::makeRequest($url);
    
    if (!$result['success']) {
        error_log("Error en segunda llamada API: " . $result['error']);
        return null;
    }
    
    $analiticoData = $result['data'];
    if (empty($analiticoData)) {
        error_log("No se encontraron datos analíticos para persona ID: " . $personaId);
        return null;
    }
    
    // Combinar los datos de ambas llamadas
    if (isset($data) && isset($data[0])) {
        $result = array_merge($data[0], $analiticoData);
    } else {
        $result = $analiticoData;
    }
    
    error_log("Datos combinados de la API para DNI " . $dni . ": " . json_encode($result));
    return $result;
}

function formatStudentData($apiData, $studentID) {
    if (empty($apiData) || !is_array($apiData)) {
        return null;
    }
    
    // Obtener nombre y apellido del estudiante desde Gibbon usando las nuevas queries
    $nombre = '';
    $apellido = '';
    
    try {
        global $connection2;
        
        $studentName = GibbonQueries::getStudentNameByDNI($connection2, $studentID);
        if ($studentName) {
            $nombre = $studentName['firstName'];
            $apellido = $studentName['surname'];
        }
    } catch (Exception $e) {
        error_log("Error al obtener datos del estudiante: " . $e->getMessage());
    }
    
    return [
        'dni' => $studentID,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'materias' => $apiData
    ];
}

/**
 * Obtiene el DNI de un estudiante usando el sistema de documentos personales de Gibbon
 * 
 * @param int $gibbonPersonID ID de la persona en Gibbon
 * @return string|null DNI del estudiante o null si no se encuentra
 */
function getStudentDNI($gibbonPersonID) {
    global $connection2;
    return GibbonQueries::getStudentDNI($connection2, $gibbonPersonID);
} 