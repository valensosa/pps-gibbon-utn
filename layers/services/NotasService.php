<?php
namespace NotasUTNAPI\Services;

use NotasUTNAPI\Services\AlumnoService;

class NotasService {
    private $alumnoService;
    
    public function __construct(AlumnoService $alumnoService) {
        $this->alumnoService = $alumnoService;
    }

    public function getStudentHistory($dni) {
        return $this->alumnoService->getStudentHistory($dni);
    }

    public function paginate($items, $page, $perPage) {
        return $this->alumnoService->paginate($items, $page, $perPage);
    }

    public function getNotasPaginadas($dni, $page, $perPage = 10) {
        $studentData = $this->getStudentHistory($dni);

        if (!$studentData || empty($studentData['materias'])) {
            return null;
        }

        $pagination = $this->paginate($studentData['materias'], $page, $perPage);

        return [
            'student' => [
                'dni' => $studentData['dni'],
                'nombre' => $studentData['nombre'],
                'apellido' => $studentData['apellido']
            ],
            'pagination' => $pagination
        ];
    }

    public function searchStudents($term) {
        return $this->alumnoService->searchStudents($term);
    }
}