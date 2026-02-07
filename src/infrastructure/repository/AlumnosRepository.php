<?php

namespace App\infrastructure\repository;

use App\config\UTNApiQueries;
use App\config\UTNApiUtils;


class AlumnosRepository
{
    // Metodos para interactuar con la API UTN relacionados con alumnos

    function getPersonasByDNI($studentDNI)
    {
        // Validar y formatear el DNI
        if (!UTNApiUtils::validateDNI($studentDNI)) {
            error_log("DNI inválido: " . $studentDNI);
            return null;
        }

        $dni = UTNApiUtils::formatDNI($studentDNI);

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

        return $data;
    }

    function getDatosAnalitico($personaId)
    {
        $url = UTNApiQueries::getDatosAnalitico($personaId);
        $result = UTNApiUtils::makeRequest($url);

        if (!$result['success']) {
            error_log("Error al obtener datos analíticos para persona ID " . $personaId . ": " . $result['error']);
            return null;
        }

        return $result;
    }
}
