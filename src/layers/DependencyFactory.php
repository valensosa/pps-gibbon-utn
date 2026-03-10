<?php

namespace App;

use App\services\IAlumnoService;
use App\services\INotasService;
use App\controllers\ControladorNotas;
use App\infrastructure\repository\IAlumnosRepository;
use App\infrastructure\repository\IGibbonAlumnoRepository;

class DependencyFactory
{

    /**
     * Crea y configura una instancia de IAlumnoService con todas sus dependencias.
     * 
     * @return IAlumnoService
     */
    public static function createAlumnoService(): IAlumnoService
    {
        $repositoryGib = new IGibbonAlumnoRepository();
        $repositoryUtn = new IAlumnosRepository();
        return new IAlumnoService($repositoryGib, $repositoryUtn);
    }

    /**
     * Crea y configura una instancia de ControladorNotas con todas sus dependencias.
     * 
     * @return ControladorNotas
     */
    public static function createControladorNotas(): ControladorNotas
    {
        $alumnoService = self::createAlumnoService();
        $notasService = new INotasService($alumnoService);

        return new ControladorNotas($notasService, $alumnoService);
    }
}
