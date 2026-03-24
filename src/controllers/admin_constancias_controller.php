<?php

namespace App\Controllers;

use App\Services\AdminConstanciasService;
use App\Infrastructure\Repository\FirestoreRepository;

class AdminConstanciasController
{
    private AdminConstanciasService $service;
    private $session;
    private $page;
    private $guid;

    public function __construct($session, $page, $guid, FirestoreRepository $firestoreRepo)
    {
        $this->service = new AdminConstanciasService($firestoreRepo);
        $this->session = $session;
        $this->page    = $page;
        $this->guid    = $guid;
    }

    public function handle(): void
    {
        if (!isActionAccessible(
            $this->guid,
            $GLOBALS['connection2'],
            '/modules/Constancias de Examen/admin_constancias.php'
        )) {
            $this->page->addError(__('No tiene acceso a esta acción.'));
            return;
        }

        try {
            $data = $this->service->getViewData();
            $solicitudes = $data['solicitudes'];

            require __DIR__ . '/../views/admin_constancias_view.php';

        } catch (\RuntimeException $e) {
            $this->page->addError(__($e->getMessage()));
        }
    }
}