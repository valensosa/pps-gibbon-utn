<?php

namespace App\infrastructure\repository;

interface IGibbonAlumnoRepository
{
    /**
     * Obtiene el ID del tipo de documento "Documento"
     * 
     * @param object $connection2 Conexión a la base de datos
     * @return int|null ID del tipo de documento o null si no se encuentra
     */
    public static function getDocumentTypeID($connection2);

    /**
     * Obtiene el DNI de un estudiante usando su gibbonPersonID
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param int $gibbonPersonID ID de la persona en Gibbon
     * @return string|null DNI del estudiante o null si no se encuentra
     */
    public static function getStudentDNI($connection2, $gibbonPersonID);

    /**
     * Obtiene el nombre y apellido de un estudiante usando su DNI
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $dni DNI del estudiante
     * @return array|null Array con 'firstName' y 'surname' o null si no se encuentra
     */
    public static function getStudentNameByDNI($connection2, $dni);

    /**
     * Busca estudiantes que coincidan con un término de búsqueda
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $searchTerm Término de búsqueda
     * @param int $limit Límite de resultados (default: 10)
     * @return array Array de estudiantes encontrados
     */
    public static function searchStudents($connection2, $searchTerm, $limit = 10);

    /**
     * Obtiene el rol del usuario actual
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param int $gibbonPersonID ID de la persona
     * @return string|null Nombre del rol o null si no se encuentra
     */
    public static function getUserRole($connection2, $gibbonPersonID);

    /**
     * Verifica si un usuario tiene acceso a una acción específica
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $guid GUID de la sesión
     * @param string $action Acción a verificar
     * @return bool True si tiene acceso, false en caso contrario
     */
    public static function hasActionAccess($connection2, $guid, $action);

    /**
     * Obtiene información completa de un estudiante por DNI
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $dni DNI del estudiante
     * @return array|null Array con información completa del estudiante
     */
    public static function getStudentInfoByDNI($connection2, $dni);
}
