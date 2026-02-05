<?php

namespace App\Services;

use Exception;
use App\Infrastructure\Repository\GibbonAlumnoRepository;
use App\Infrastructure\Repository\AlumnosRepository;

class AlumnoService
{
    private $repositoryGibbon;
    private $repositoryUTN;

    public function __construct(GibbonAlumnoRepository $repositoryGibbon, AlumnosRepository $repositoryUTN)
    {
        $this->repositoryGibbon = $repositoryGibbon;
        $this->repositoryUTN = $repositoryUTN;
    }

    //Usa UTN API para obtener datos del estudiante
    function getStudentDataFromAPI($studentDNI)
    {
        $data = $this->repositoryUTN->getPersonasByDNI($studentDNI);

        $personaId = $data[0]['persona'];

        $result = $this->repositoryUTN->getDatosAnalitico($personaId);
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

        error_log("Datos combinados de la API para DNI " . $studentDNI . ": " . json_encode($result));
        return $result;
    }

    function formatStudentData($apiData, $studentID)
    {
        if (empty($apiData) || !is_array($apiData)) {
            return null;
        }

        // Obtener nombre y apellido del estudiante desde Gibbon usando las nuevas queries
        $nombre = '';
        $apellido = '';

        try {
            global $connection2;

            $studentName = GibbonAlumnoRepository::getStudentNameByDNI($connection2, $studentID);
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
    function getStudentDNI($gibbonPersonID)
    {
        global $connection2;
        return $this->repositoryGibbon::getStudentDNI($connection2, $gibbonPersonID);
    }

    function getStudentNameByDNI($studentDni)
    {
        global $connection2;
        return $this->repositoryGibbon::getStudentNameByDNI($connection2, $studentDni);
    }

    function searchStudents($searchTerm)
    {
        global $connection2;
        return $this->repositoryGibbon::searchStudents($connection2, $searchTerm, 10);
    }

    function getGibbonUserRoleByID($gibbonPersonID)
    {
        global $connection2;
        return $this->repositoryGibbon::getUserRole($connection2, $gibbonPersonID);
    }
}
