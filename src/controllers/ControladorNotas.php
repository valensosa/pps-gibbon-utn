<?php

namespace App\Controllers;

use App\Services\NotasService;
use App\Services\AlumnoService;

class ControladorNotas
{
    private $notasService;
    private $alumnoService;
    public function __construct(NotasService $notasService, AlumnoService $alumnoService)
    {
        $this->notasService = $notasService;
        $this->alumnoService = $alumnoService;
    }


    function handleFullFlow($context)
    {

        // Obtener el rol del usuario usando las nuevas queries
        $userRole = null;
        if ($context['gibbonPersonID']) {
            $userRole = $this->getUserRole($context['gibbonPersonID']);
        }

        // Determinar qué DNI buscar
        $targetDNI = null;
        $targetDNI = $this->getStudentDni($context['gibbonPersonID'], $userRole, $context['selected_dni'], $context['page']);

        // Procesar solicitud si hay un DNI objetivo
        $result = [];
        if ($targetDNI) {
            $request = [
                'student_dni' => $targetDNI,
                'page' => $context['page']
            ];
            $result = $this->handleRequest($request);
        }

        $result['userRole'] = $userRole;

        return $result;
    }

    public function handleRequest($request)
    {
        $dni = isset($request['student_dni']) ? trim($request['student_dni']) : '';
        $page = $request['page'] ?? 1;

        if (!$dni) {
            return ['error' => 'Debe ingresar un DNI.'];
        }

        $alumno = $this->notasService->buscarAlumnoPorDNI($dni);
        $result = $this->notasService->notasPaginacion($alumno['materias'], $page, 10);

        if (!$result) {
            return ['error' => 'No se encontraron notas para el DNI ingresado.'];
        }

        return [
            'success' => true,
            'student' => [
                'dni' => $dni,
                'nombre' => $alumno['nombre'] ?? '',
                'apellido' => $alumno['apellido'] ?? ''
            ],
            'materias' => $result['materias'],
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

    function getUserRole($gibbonPersonID)
    {
        return $this->alumnoService->getGibbonUserRoleByID($gibbonPersonID);
    }

    function getStudentDni($gibbonPersonID, $userRole, $selectedStudentDni, $page)
    {
        if ($userRole === 'Student') {
            // Si es estudiante, buscar su DNI en el sistema de documentos personales
            $userDNI = $this->alumnoService->getStudentDNI($gibbonPersonID);

            // Si no tiene DNI registrado, mostrar error
            if (!$userDNI) {
                $page->addError(__('No se encontró un DNI registrado en el sistema. Por favor, contacte a la administración.'));
            }
            return $userDNI;
        } elseif ($selectedStudentDni) {
            // Si es admin y seleccionó un estudiante
            return $selectedStudentDni;
        }
    }
}
