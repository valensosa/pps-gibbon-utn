<?php
namespace NotasUTNAPI\Services;

use NotasUTNAPI\Infrastructure\Repository\StudentRepository;

class StudentService {
    private $repository;

    public function __construct(StudentRepository $repository) {
        $this->repository = $repository;
    }

    public function getStudentHistory($dni) {
        $nombre = '';
        $apellido = '';
        
        try {
            $studentName = $this->repository->getStudentNameByDNI($dni);
            if ($studentName) {
                $nombre = $studentName['firstName'];
                $apellido = $studentName['surname'];
            }
        } catch (\Exception $e) {
            error_log("Error al buscar estudiante por DNI: " . $e->getMessage());
        }

        $apiData = $this->repository->getStudentDataFromAPI($dni);
        if (!$apiData) {
            return null;
        }

        // Formatear datos
        $studentData = [
            'dni' => $dni,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'materias' => $apiData
        ];

        if (empty($studentData['materias'])) {
            return $studentData;
        }

        // Ordenar materias por fecha (descendente)
        usort($studentData['materias'], function($a, $b) {
            $fechaA = strtotime($a['fecha'] ?? '1970-01-01');
            $fechaB = strtotime($b['fecha'] ?? '1970-01-01');
            return $fechaB <=> $fechaA;
        });

        // Filtrar solo materias con actividad_nombre
        $studentData['materias'] = array_filter($studentData['materias'], function($materia) {
            return !empty($materia['actividad_nombre']);
        });

        return $studentData;
    }

    public function paginate($items, $page, $perPage) {
        $totalItems = count($items);
        $totalPages = ceil($totalItems / $perPage);
        
        // Asegurar que la página actual sea válida
        $page = max(1, min($page, $totalPages > 0 ? $totalPages : 1));
        
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);

        return [
            'data' => $paginatedItems,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'offset' => $offset,
            'perPage' => $perPage
        ];
    }
}
