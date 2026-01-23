<?php
namespace NotasUTNAPI\Controllers;

use NotasUTNAPI\Services\NotasService;

class ControladorNotas {
    private $notasService;

    public function __construct(NotasService $notasService) {
        $this->notasService = $notasService;
    }

    public function handleRequest($request) {
        $dni = isset($request['student_dni']) ? trim($request['student_dni']) : '';
        $page = $request['page'] ?? 1;

        if (!$dni) {
            return ['error' => 'Debe ingresar un DNI.'];
        }

        $result = $this->notasService->getNotasPaginadas($dni, $page, 10);

        if (!$result) {
            return ['error' => 'No se encontraron notas para el DNI ingresado.'];
        }

        return [
            'success' => true,
            'student' => $result['student'],
            'pagination' => $result['pagination']
        ];
    }

    public function searchStudents($request) {
        $q = $request['q'] ?? '';
        if (strlen($q) < 2) {
            return [];
        }
        return $this->notasService->searchStudents($q);
    }
}
