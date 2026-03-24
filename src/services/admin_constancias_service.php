<?php

namespace App\Services;

use App\Infrastructure\Repository\FirestoreRepository;
use App\Infrastructure\Repository\StudentRepository;

class AdminConstanciasService
{
    private FirestoreRepository $firestoreRepo;
    private StudentRepository $studentRepo;

    public function __construct(FirestoreRepository $firestoreRepo, StudentRepository $studentRepo)
    {
        $this->firestoreRepo = $firestoreRepo;
        $this->studentRepo   = $studentRepo;
    }

    public function getViewData(): array
    {
        $docs = $this->firestoreRepo->getAll();

        $solicitudes = array_map(function ($doc) {
            $data = FirestoreRepository::parseDocument($doc);
            $data['constanciaId'] = FirestoreRepository::getDocumentId($doc);

            $data['email'] = '';
            if (!empty($data['dniAlumno'])) {
                $info = $this->studentRepo->getStudentInfoByDni($data['dniAlumno']);
                if ($info) {
                    $data['email'] = $info['email'];
                }
            }

            return $data;
        }, $docs);

        usort($solicitudes, function ($a, $b) {
            $statusOrder = ['pendiente' => 1, 'completado' => 2, 'rechazado' => 3];
            $aOrder = $statusOrder[$a['estado']] ?? 99;
            $bOrder = $statusOrder[$b['estado']] ?? 99;

            if ($aOrder !== $bOrder) {
                return $aOrder <=> $bOrder;
            }

            return strtotime($b['fechaPedido'] ?? 0) <=> strtotime($a['fechaPedido'] ?? 0);
        });

        return ['solicitudes' => $solicitudes];
    }
}