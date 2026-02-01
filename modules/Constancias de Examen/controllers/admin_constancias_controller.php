<?php

require_once __DIR__ . '/../services/admin_constancias_service.php';
require_once __DIR__ . '/../infrastructure/repositories/constancias_repository.php';

class AdminConstanciasController
{
    private AdminConstanciasService $service;
    private $session;
    private $page;
    private $guid;

    public function __construct(PDO $connection, $session, $page, $guid)
    {
        $repo = new ConstanciasRepository($connection);
        $this->service = new AdminConstanciasService($repo);

        $this->session = $session;
        $this->page = $page;
        $this->guid = $guid;
    }

    public function handle(): void
    {
        if (!isActionAccessible(
            $this->guid,
            $GLOBALS['connection2'],
            '/modules/Constancias de Examen/adminView/admin_constancias.php'
        )) {
            $this->page->addError(__('No tiene acceso a esta acción.'));
            return;
        }

        try {
            $data = $this->service->getViewData();

            $solicitudes = $data['solicitudes'];

            require __DIR__ . '/../views/admin_constancias_view.php';

        } catch (RuntimeException $e) {
            $this->page->addError(__($e->getMessage()));
        }
    }
}
