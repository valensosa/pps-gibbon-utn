<?php

namespace App\services;

use App\infrastructure\repository\FirestoreRepository;

class UploadService
{
    private FirestoreRepository $firestoreRepo;

    public function __construct(FirestoreRepository $firestoreRepo)
    {
        $this->firestoreRepo = $firestoreRepo;
    }

    public function handleUpload(string $constanciaId, string $dniAlumno, string $materia, array $file): array
    {
        try {
            if (empty($constanciaId) || empty($dniAlumno) || empty($materia)) {
                throw new \Exception('Faltan datos necesarios');
            }

            if (empty($file['tmp_name'])) {
                throw new \Exception('No se ha seleccionado ningún archivo');
            }

            $url = $this->firestoreRepo->uploadPdf($constanciaId, $dniAlumno, $materia, $file['tmp_name']);

            return [
                'success' => true,
                'url'     => $url,
                'message' => 'Constancia subida correctamente'
            ];

        } catch (\Exception $e) {
            error_log('Error al subir la constancia: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al subir la constancia: ' . $e->getMessage()
            ];
        }
    }
}