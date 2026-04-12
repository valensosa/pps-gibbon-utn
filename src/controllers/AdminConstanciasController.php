<?php

namespace App\controllers;

use App\services\AdminConstanciasService;
use App\infrastructure\repository\FirestoreRepository;
use App\infrastructure\repository\StudentRepository;

class AdminConstanciasController
{
    private AdminConstanciasService $service;
    private $session;
    private $page;
    private $guid;

    public function __construct($session, $page, $guid, FirestoreRepository $firestoreRepo, StudentRepository $studentRepo)
    {
        $this->service = new AdminConstanciasService($firestoreRepo, $studentRepo);
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
            $data        = $this->service->getViewData();
            $solicitudes = $data['solicitudes'];
            $session     = $this->session;
            require __DIR__ . '/../views/admin_constancias_view.php';

        } catch (\RuntimeException $e) {
            $this->page->addError(__($e->getMessage()));
        }
    }
}