<?php

namespace App\services;

use App\services\IAlumnoService;

class NotasService implements INotasService
{
    private $alumnoService;

    public function __construct(IAlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }


    public function buscarAlumnoPorDNI($studentDni)
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
    public function sortMateriasByDateDesc($materias)
    {
        usort($materias, function ($a, $b) {
            $fechaA = strtotime($a['fecha'] ?? '1970-01-01');
            $fechaB = strtotime($b['fecha'] ?? '1970-01-01');
            return $fechaB <=> $fechaA; // Descendente
        });
        return $materias;
    }

    // Filtrar solo materias con actividad_nombre
    public function filterMateriasWithActividad($materias)
    {
        return array_filter($materias, function ($materia) {
            return !empty($materia['actividad_nombre']);
        });
    }

    // Paginación de materias
    public function notasPaginacion($materias, $paginaActual, $materiasPorPagina)
    {
        $totalMaterias = count($materias);
        $totalPaginas = ceil($totalMaterias / $materiasPorPagina);
        $paginaActual = max(1, min($paginaActual, $totalPaginas));
        $offset = ($paginaActual - 1) * $materiasPorPagina;
        return array_slice($materias, $offset, $materiasPorPagina);
    }
}
