<?php
namespace App\Services
class AdminConstanciasService
{
    private ConstanciasRepository $repo;

    public function __construct(ConstanciasRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getViewData(): array
    {
        $solicitudes = $this->repo->getAllPendingRequests();

        return [
            'solicitudes' => $solicitudes
        ];
    }
}
