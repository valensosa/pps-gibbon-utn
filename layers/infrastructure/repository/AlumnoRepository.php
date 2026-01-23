<?php
namespace NotasUTNAPI\Infrastructure\Repository;

require_once __DIR__ . '/../../config/utn_api_config.php';
require_once __DIR__ . '/GibbonAlumnoRepository.php';

use UTNApiQueries;
use UTNApiUtils;

class AlumnoRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getStudentNameByDNI($dni) {
        return GibbonAlumnoRepository::getStudentNameByDNI($this->connection, $dni);
    }

    public function getStudentDataFromAPI($studentID) {
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
        
        return $analiticoData;
    }

    public function searchStudents($term) {
        return GibbonAlumnoRepository::searchStudents($this->connection, $term);
    }
}