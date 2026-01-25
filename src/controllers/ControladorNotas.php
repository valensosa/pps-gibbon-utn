<?php

namespace NotasUTNAPI\Controllers;

use NotasUTNAPI\Services\NotasService;
use NotasUTNAPI\Services\AlumnoService;

class ControladorNotas
{
    private $notasService;
    private $alumnoService;
    public function __construct(NotasService $notasService, AlumnoService $alumnoService)
    {
        $this->notasService = $notasService;
        $this->alumnoService = $alumnoService;
    }

    public function handleRequest($request)
    {
        $dni = isset($request['student_dni']) ? trim($request['student_dni']) : '';
        $page = $request['page'] ?? 1;

        if (!$dni) {
            return ['error' => 'Debe ingresar un DNI.'];
        }

        $notas = $this->notasService->buscarNotasPorDNI($dni);
        $result = $this->notasService->notasPaginacion($notas, $page, 10);

        if (!$result) {
            return ['error' => 'No se encontraron notas para el DNI ingresado.'];
        }

        return [
            'success' => true,
            'student' => $result['student'],
            'pagination' => $result['pagination']
        ];
    }

    public function searchStudents($request)
    {
        $q = $request['q'] ?? '';
        if (strlen($q) < 2) {
            return [];
        }
        return $this->alumnoService->searchStudents($q);
    }
}
