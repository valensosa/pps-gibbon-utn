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
     * Crea y configura una instancia de AlumnoService con todas sus dependencias.
     * 
     * @return AlumnoService
     */
    public static function createAlumnoService(): AlumnoService
    {
        $repositoryGib = new GibbonAlumnoRepository();
        $repositoryUtn = new AlumnosRepository();
        return new AlumnoService($repositoryGib, $repositoryUtn);
    }

    /**
     * Crea y configura una instancia de ControladorNotas con todas sus dependencias.
     * 
     * @return ControladorNotas
     */
    public static function createControladorNotas(): ControladorNotas
    {
        $alumnoService = self::createAlumnoService();
        $notasService = new NotasService($alumnoService);

        return new ControladorNotas($notasService, $alumnoService);
    }
}
