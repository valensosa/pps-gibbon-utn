<?php

namespace App\services;

interface IAlumnoService
{
    /**
     * Usa UTN API para obtener datos del estudiante
     * 
     * @param string $studentDNI
     * @return array|null
     */
    public function getStudentDataFromAPI($studentDNI);

    /**
     * Formatea los datos del estudiante obtenidos de la API
     * 
     * @param array $apiData
     * @param string $studentID
     * @return array|null
     */
    public function formatStudentData($apiData, $studentID);

    /**
     * Obtiene el DNI de un estudiante usando el sistema de documentos personales de Gibbon
     * 
     * @param int $gibbonPersonID ID de la persona en Gibbon
     * @return string|null DNI del estudiante o null si no se encuentra
     */
    public function getStudentDNI($gibbonPersonID);

    /**
     * Obtiene el nombre y apellido de un estudiante por su DNI
     * 
     * @param string $studentDni
     * @return array|null
     */
    public function getStudentNameByDNI($studentDni);

    /**
     * Busca estudiantes por término de búsqueda
     * 
     * @param string $searchTerm
     * @return array
     */
    public function searchStudents($searchTerm);

    /**
     * Obtiene el rol de usuario de Gibbon por ID
     * 
     * @param int $gibbonPersonID
     * @return string|null
     */
    public function getGibbonUserRoleByID($gibbonPersonID);
}
