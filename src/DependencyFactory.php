<?php

namespace App;

use App\Services\AlumnoService;
use App\Services\NotasService;
use App\Controllers\ControladorNotas;
use App\Infrastructure\Repository\AlumnosRepository;
use App\Infrastructure\Repository\GibbonAlumnoRepository;

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
