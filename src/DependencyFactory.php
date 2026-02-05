<?php

namespace App;

use App\services\AlumnoService;
use App\services\NotasService;
use App\controllers\ControladorNotas;
use App\infrastructure\repository\AlumnosRepository;
use App\infrastructure\repository\GibbonAlumnoRepository;

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
        $repositoryGib = new GibbonAlumnoRepository($connection);
        $repositoryUtn = new AlumnosRepository();
        $alumnoService = new AlumnoService($repositoryGib, $repositoryUtn);
        $notasService = new NotasService($alumnoService);

        return new ControladorNotas($notasService, $alumnoService);
    }
}
