<?php

namespace App\controllers;

use App\services\StudentConstanciasService;
use App\infrastructure\repository\StudentRepository;
class StudentConstanciasController
{
    private StudentConstanciasService $service;
    private $session;
    private $page;
    private $guid;

    public function __construct(\PDO $connection, $session, $page, $guid)
    {
        $studentRepo = new StudentRepository($connection);
        $this->service = new StudentConstanciasService($studentRepo);

        $this->session = $session;
        $this->page = $page;
        $this->guid = $guid;
    }

    public function handle(): void
    {
        if (isActionAccessible($this->guid, $GLOBALS['connection2'], '/modules/Constancias de Examen/student_constancias.php') === false) {
            $this->page->addError(__('No tiene acceso a esta acción.'));
            return;
        }

        try {
            $personId = (int) $this->session->get('gibbonPersonID');
            $data = $this->service->getViewData($personId);

            $student = $data['student'];
            $dni = $data['dni'];
            $session  = $this->session;
            require __DIR__ . '/../views/student_view.php';

        } catch (\RuntimeException $e) {
            $this->page->addError(__($e->getMessage()));
        }
    }
}
