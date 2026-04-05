<?php
namespace App\services;
use App\infrastructure\repository\StudentRepository;
class StudentConstanciasService
{
    private StudentRepository $studentRepo;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->studentRepo = $studentRepo;
    }

    public function getViewData(int $gibbonPersonID): array
    {
        $student = $this->studentRepo->getByPersonId($gibbonPersonID);

        if (!$student) {
            throw new \RuntimeException('No se pudo obtener la información del estudiante.');
        }

        $role = $this->studentRepo->getUserRoleByPersonId($gibbonPersonID);
        if ($role !== 'Student') {
            throw new \RuntimeException('Esta página es solo para estudiantes.');
        }

        $dni = $this->studentRepo->getDniByPersonId($gibbonPersonID);
        if (!$dni) {
            throw new \RuntimeException('No se encontró el documento del estudiante.');
        }

        return [
            'student' => $student,
            'dni'     => $dni,
        ];
    }
}
