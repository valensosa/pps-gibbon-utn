<?php
namespace NotasUTNAPI\Controllers;

use NotasUTNAPI\Services\StudentService;

class GradesController {
    private $service;

    public function __construct(StudentService $service) {
        $this->service = $service;
    }

    public function handleRequest($request) {
        $dni = isset($request['student_dni']) ? trim($request['student_dni']) : '';
        $page = $request['page'] ?? 1;

        if (!$dni) {
            return ['error' => 'Debe ingresar un DNI.'];
        }

        $studentData = $this->service->getStudentHistory($dni);

        if (!$studentData || empty($studentData['materias'])) {
            return ['error' => 'No se encontraron notas para el DNI ingresado.'];
        }

        $pagination = $this->service->paginate($studentData['materias'], $page, 10);

        return [
            'success' => true,
            'student' => [
                'dni' => $studentData['dni'],
                'nombre' => $studentData['nombre'],
                'apellido' => $studentData['apellido']
            ],
            'pagination' => $pagination
        ];
    }
}
