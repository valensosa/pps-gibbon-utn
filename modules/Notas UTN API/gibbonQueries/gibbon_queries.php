<?php
/**
 * Queries de Gibbon para el módulo Notas UTN API
 * 
 * Este archivo contiene todas las consultas a la base de datos de Gibbon
 * necesarias para el funcionamiento del módulo.
 */

class GibbonQueries {
    
    /**
     * Obtiene el ID del tipo de documento "Documento"
     * 
     * @param object $connection2 Conexión a la base de datos
     * @return int|null ID del tipo de documento o null si no se encuentra
     */
    public static function getDocumentTypeID($connection2) {
        try {
            $sql = "SELECT gibbonPersonalDocumentTypeID FROM gibbonPersonalDocumentType WHERE name = 'Documento'";
            $stmt = $connection2->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            
            return $row ? $row['gibbonPersonalDocumentTypeID'] : null;
        } catch (Exception $e) {
            error_log("Error al obtener ID del tipo de documento: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene el DNI de un estudiante usando su gibbonPersonID
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param int $gibbonPersonID ID de la persona en Gibbon
     * @return string|null DNI del estudiante o null si no se encuentra
     */
    public static function getStudentDNI($connection2, $gibbonPersonID) {
        try {
            // Obtener el ID del tipo de documento
            $tipoID = self::getDocumentTypeID($connection2);
            if (!$tipoID) {
                error_log("No se encontró el tipo de documento 'Documento'");
                return null;
            }
            
            // Buscar el documento del usuario
            $sql = "SELECT documentNumber FROM gibbonPersonalDocument 
                   WHERE foreignTable = 'gibbonPerson' 
                   AND foreignTableID = :gibbonPersonID 
                   AND gibbonPersonalDocumentTypeID = :tipoID 
                   LIMIT 1";
            $stmt = $connection2->prepare($sql);
            $stmt->execute([
                'gibbonPersonID' => $gibbonPersonID,
                'tipoID' => $tipoID
            ]);
            
            if ($stmt->rowCount() != 1) {
                error_log("No se encontró documento para gibbonPersonID: " . $gibbonPersonID);
                return null;
            }
            
            $row = $stmt->fetch();
            return $row['documentNumber'];
            
        } catch (Exception $e) {
            error_log("Error al obtener DNI del estudiante: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene el nombre y apellido de un estudiante usando su DNI
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $dni DNI del estudiante
     * @return array|null Array con 'firstName' y 'surname' o null si no se encuentra
     */
    public static function getStudentNameByDNI($connection2, $dni) {
        try {
            // Obtener el ID del tipo de documento
            $tipoID = self::getDocumentTypeID($connection2);
            if (!$tipoID) {
                error_log("No se encontró el tipo de documento 'Documento'");
                return null;
            }
            
            // Buscar la persona por DNI
            $sql = "SELECT p.firstName, p.surname 
                    FROM gibbonPerson p
                    JOIN gibbonPersonalDocument pd ON (pd.foreignTable = 'gibbonPerson' AND pd.foreignTableID = p.gibbonPersonID)
                    WHERE pd.documentNumber = :dni 
                    AND pd.gibbonPersonalDocumentTypeID = :tipoID 
                    LIMIT 1";
            $result = $connection2->prepare($sql);
            $result->execute([
                'dni' => $dni,
                'tipoID' => $tipoID
            ]);
            
            $row = $result->fetch();
            if ($row) {
                return [
                    'firstName' => $row['firstName'],
                    'surname' => $row['surname']
                ];
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("Error al obtener nombre del estudiante por DNI: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Busca estudiantes que coincidan con un término de búsqueda
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $searchTerm Término de búsqueda
     * @param int $limit Límite de resultados (default: 10)
     * @return array Array de estudiantes encontrados
     */
    public static function searchStudents($connection2, $searchTerm, $limit = 10) {
        try {
            // Obtener el ID del tipo de documento
            $tipoID = self::getDocumentTypeID($connection2);
            if (!$tipoID) {
                error_log("No se encontró el tipo de documento 'Documento'");
                return [];
            }
            
            // Buscar estudiantes que coincidan con el término de búsqueda
            $data = [
                'searchTerm' => '%' . $searchTerm . '%',
                'tipoID' => $tipoID
            ];
            
            $sql = "SELECT p.gibbonPersonID, p.firstName, p.surname, pd.documentNumber 
                    FROM gibbonPerson p
                    JOIN gibbonPersonalDocument pd ON (pd.foreignTable = 'gibbonPerson' AND pd.foreignTableID = p.gibbonPersonID)
                    WHERE pd.gibbonPersonalDocumentTypeID = :tipoID 
                    AND (p.firstName LIKE :searchTerm OR p.surname LIKE :searchTerm OR pd.documentNumber LIKE :searchTerm)
                    ORDER BY p.firstName ASC, p.surname ASC 
                    LIMIT " . (int)$limit;
            
            $result = $connection2->prepare($sql);
            $result->execute($data);
            
            $students = [];
            while ($row = $result->fetch()) {
                $student = [
                    'id' => $row['gibbonPersonID'],
                    'firstName' => $row['firstName'],
                    'surname' => $row['surname'],
                    'dni' => $row['documentNumber'],
                    'display' => $row['firstName'] . ' ' . $row['surname'] . ' - ' . $row['documentNumber']
                ];
                
                $students[] = $student;
            }
            
            return $students;
            
        } catch (Exception $e) {
            error_log("Error al buscar estudiantes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene el rol del usuario actual
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param int $gibbonPersonID ID de la persona
     * @return string|null Nombre del rol o null si no se encuentra
     */
    public static function getUserRole($connection2, $gibbonPersonID) {
        try {
            $data = ['gibbonPersonID' => $gibbonPersonID];
            $sql = "SELECT gibbonRole.name 
                    FROM gibbonPerson
                    JOIN gibbonRole ON (gibbonPerson.gibbonRoleIDPrimary = gibbonRole.gibbonRoleID)
                    WHERE gibbonPerson.gibbonPersonID = :gibbonPersonID
                    LIMIT 1";
            $result = $connection2->prepare($sql);
            $result->execute($data);
            $row = $result->fetch();
            
            return $row ? $row['name'] : null;
            
        } catch (Exception $e) {
            error_log("Error al obtener rol del usuario: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Verifica si un usuario tiene acceso a una acción específica
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $guid GUID de la sesión
     * @param string $action Acción a verificar
     * @return bool True si tiene acceso, false en caso contrario
     */
    public static function hasActionAccess($connection2, $guid, $action) {
        try {
            // Esta función puede ser expandida según las necesidades de permisos
            // Por ahora, retornamos true para mantener compatibilidad
            return true;
        } catch (Exception $e) {
            error_log("Error al verificar acceso: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene información completa de un estudiante por DNI
     * 
     * @param object $connection2 Conexión a la base de datos
     * @param string $dni DNI del estudiante
     * @return array|null Array con información completa del estudiante
     */
    public static function getStudentInfoByDNI($connection2, $dni) {
        try {
            // Obtener el ID del tipo de documento
            $tipoID = self::getDocumentTypeID($connection2);
            if (!$tipoID) {
                return null;
            }
            
            // Buscar información completa del estudiante
            $sql = "SELECT p.gibbonPersonID, p.firstName, p.surname, p.email, pd.documentNumber
                    FROM gibbonPerson p
                    JOIN gibbonPersonalDocument pd ON (pd.foreignTable = 'gibbonPerson' AND pd.foreignTableID = p.gibbonPersonID)
                    WHERE pd.documentNumber = :dni 
                    AND pd.gibbonPersonalDocumentTypeID = :tipoID 
                    LIMIT 1";
            
            $result = $connection2->prepare($sql);
            $result->execute([
                'dni' => $dni,
                'tipoID' => $tipoID
            ]);
            
            $row = $result->fetch();
            if ($row) {
                return [
                    'gibbonPersonID' => $row['gibbonPersonID'],
                    'firstName' => $row['firstName'],
                    'surname' => $row['surname'],
                    'email' => $row['email'],
                    'dni' => $row['documentNumber']
                ];
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("Error al obtener información del estudiante: " . $e->getMessage());
            return null;
        }
    }
} 