<?php

namespace App\services;

use App\services\AlumnoService;

class NotasService
{
    private $alumnoService;

    public function __construct(AlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }


    function buscarAlumnoPorDNI($studentDni)
    {
        // Obtener datos del estudiante desde la UTN API
        $apiData = $this->alumnoService->getStudentDataFromAPI($studentDni);
        if (!$apiData) {
            return null;
        }

        $studentData = $this->alumnoService->formatStudentData($apiData, $studentDni);
        if (!$studentData || empty($studentData['materias'])) {
            return null;
        }

        $materias = $studentData['materias'];

        $this->sortMateriasByDateDesc($materias);

        $materiasConActividad = $this->filterMateriasWithActividad($materias);

        $studentData['materias'] = $materiasConActividad;
        return $studentData;
    }

    // Ordenar materias por fecha (descendente - más reciente primero)
    function sortMateriasByDateDesc($materias)
    {
        usort($materias, function ($a, $b) {
            $fechaA = strtotime($a['fecha'] ?? '1970-01-01');
            $fechaB = strtotime($b['fecha'] ?? '1970-01-01');
            return $fechaB <=> $fechaA; // Descendente
        });
        return $materias;
    }

    // Filtrar solo materias con actividad_nombre
    function filterMateriasWithActividad($materias)
    {
        return array_filter($materias, function ($materia) {
            return !empty($materia['actividad_nombre']);
        });
    }

    // Paginación de materias
    function notasPaginacion($materias, $paginaActual, $materiasPorPagina)
    {
        $totalMaterias = count($materias);
        $totalPaginas = ceil($totalMaterias / $materiasPorPagina);
        $paginaActual = max(1, min($paginaActual, $totalPaginas));
        $offset = ($paginaActual - 1) * $materiasPorPagina;
        return array_slice($materias, $offset, $materiasPorPagina);
    }
}
