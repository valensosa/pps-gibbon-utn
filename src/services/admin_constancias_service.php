<?php

namespace App\Services;

use App\Infrastructure\Repository\FirestoreRepository;

class AdminConstanciasService
{
    private FirestoreRepository $repo;

    public function __construct(FirestoreRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getViewData(): array
    {
        $docs = $this->repo->getAll();

        $solicitudes = array_map(function ($doc) {
            $data = FirestoreRepository::parseDocument($doc);
            $data['constanciaId'] = FirestoreRepository::getDocumentId($doc);
            return $data;
        }, $docs);

        return ['solicitudes' => $solicitudes];
    }
}