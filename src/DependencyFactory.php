<?php

namespace NotasUTNAPI;

require_once __DIR__ . '/infrastructure/repository/GibbonAlumnoRepository.php';
require_once __DIR__ . '/services/AlumnoService.php';
require_once __DIR__ . '/services/NotasService.php';
require_once __DIR__ . '/controllers/ControladorNotas.php';

use NotasUTNAPI\Services\AlumnoService;
use NotasUTNAPI\Services\NotasService;
use NotasUTNAPI\Controllers\ControladorNotas;
use NotasUTNAPI\Infrastructure\Repository\GibbonAlumnoRepository;

class DependencyFactory
{

    /**
     * Crea y configura una instancia de ControladorNotas con todas sus dependencias.
     * 
     * @param mixed $connection La conexión a la base de datos (Gibbon)
     * @return ControladorNotas
     */
    public static function createControladorNotas($connection)
    {
        $repository = new GibbonAlumnoRepository($connection);
        $alumnoService = new AlumnoService($repository);
        $notasService = new NotasService($alumnoService);

        return new ControladorNotas($notasService, $alumnoService);
    }
}
