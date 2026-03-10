<?php

namespace App\services;

use Exception;
use App\infrastructure\repository\IGibbonAlumnoRepository;
use App\infrastructure\repository\IAlumnosRepository;

class AlumnoService implements IAlumnoService
{
    private $repositoryGibbon;
    private $repositoryUTN;

    public function __construct(IGibbonAlumnoRepository $repositoryGibbon, IAlumnosRepository $repositoryUTN)
    {
        $this->repositoryGibbon = $repositoryGibbon;
        $this->repositoryUTN = $repositoryUTN;
    }

    //Usa UTN API para obtener datos del estudiante
    public function getStudentDataFromAPI($studentDNI)
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

    public function formatStudentData($apiData, $studentID)
    {
        if (empty($apiData) || !is_array($apiData)) {
            return null;
        }

        // Obtener nombre y apellido del estudiante desde Gibbon usando las nuevas queries
        $nombre = '';
        $apellido = '';

        try {
            global $connection2;

            $studentName = IGibbonAlumnoRepository::getStudentNameByDNI($connection2, $studentID);
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
    public function getStudentDNI($gibbonPersonID)
    {
        global $connection2;
        return $this->repositoryGibbon::getStudentDNI($connection2, $gibbonPersonID);
    }

    public function getStudentNameByDNI($studentDni)
    {
        global $connection2;
        return $this->repositoryGibbon::getStudentNameByDNI($connection2, $studentDni);
    }

    public function searchStudents($searchTerm): array
    {
        global $connection2;
        return $this->repositoryGibbon::searchStudents($connection2, $searchTerm, 10);
    }

    public function getGibbonUserRoleByID($gibbonPersonID)
    {
        global $connection2;
        return $this->repositoryGibbon::getUserRole($connection2, $gibbonPersonID);
    }
}
