<?php

namespace App\services;

use App\services\IAlumnoService;
use App\domain\Materia;

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

        // Convertir arrays a objetos Materia
        $materias = [];
        foreach ($studentData['materias'] as $materiaData) {
            // AlumnoService mezcla datos del alumno (strings) con materias (arrays), filtramos aquí
            if (!is_array($materiaData)) {
                continue;
            }

            $tituloAraucano = $materiaData['titulo_araucano'] ?? '';
            $tituloNombre = $materiaData['titulo_nombre'] ?? '';
            $planVigente = $materiaData['plan_vigente'] ?? '';
            $actividadNombre = $materiaData['actividad_nombre'] ?? '';
            $actividadCodigo = $materiaData['actividad_codigo'] ?? '';
            $fecha = isset($materiaData['fecha']) ? $materiaData['fecha'] : '';
            $nota = isset($materiaData['nota']) ? (string)$materiaData['nota'] : '';
            $resultado = $materiaData['resultado'] ?? '';
            $promedio = $materiaData['promedio'] ?? '';
            $formaAprobacion = $materiaData['forma_aprobacion'] ?? '';
            $esOptativa = $materiaData['es_optativa'] ?? '';

            $materias[] = new Materia($tituloAraucano, $tituloNombre, $planVigente, $actividadNombre, $actividadCodigo, $fecha, $nota, $resultado, $promedio, $formaAprobacion, $esOptativa);
        }

        $this->sortMateriasByDateDesc($materias);

        $materiasConActividad = $this->filterMateriasWithActividad($materias);

        $studentData['materias'] = $materiasConActividad;
        return $studentData;
    }

    // Ordenar materias por fecha (descendente - más reciente primero)
    public function sortMateriasByDateDesc($materias)
    {
        usort($materias, function (Materia $a, Materia $b) {
            $fechaA = strtotime($a->getFecha() ?: '1970-01-01');
            $fechaB = strtotime($b->getFecha() ?: '1970-01-01');
            return $fechaB <=> $fechaA; // Descendente
        });
        return $materias;
    }

    // Filtrar solo materias con actividad_nombre
    public function filterMateriasWithActividad($materias)
    {
        return array_filter($materias, function (Materia $materia) {
            return !empty($materia->getActividadNombre());
        });
    }

    // Paginación de materias
    public function notasPaginacion($materias, $paginaActual, $materiasPorPagina)
    {
        $totalMaterias = count($materias);
        $totalPaginas = ceil($totalMaterias / $materiasPorPagina);
        $paginaActual = max(1, min($paginaActual, $totalPaginas));
        $offset = ($paginaActual - 1) * $materiasPorPagina;

        return [
            'materias' => array_slice($materias, $offset, $materiasPorPagina),
            'pagination' => [
                'totalItems' => $totalMaterias,
                'totalPages' => $totalPaginas,
                'currentPage' => $paginaActual,
                'perPage' => $materiasPorPagina,
                'offset' => $offset
            ]
        ];
    }
}
