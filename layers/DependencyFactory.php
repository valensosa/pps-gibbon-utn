<?php

namespace NotasUTNAPI;

require_once __DIR__ . '/infrastructure/repository/AlumnoRepository.php';
require_once __DIR__ . '/services/AlumnoService.php';
require_once __DIR__ . '/services/NotasService.php';
require_once __DIR__ . '/controllers/ControladorNotas.php';

use NotasUTNAPI\Infrastructure\Repository\AlumnoRepository;
use NotasUTNAPI\Services\AlumnoService;
use NotasUTNAPI\Services\NotasService;
use NotasUTNAPI\Controllers\ControladorNotas;

class DependencyFactory {
    
    /**
     * Crea y configura una instancia de ControladorNotas con todas sus dependencias.
     * 
     * @param mixed $connection La conexión a la base de datos (Gibbon)
     * @return ControladorNotas
     */
    public static function createControladorNotas($connection) {
        $repository = new AlumnoRepository($connection);
        $alumnoService = new AlumnoService($repository);
        $notasService = new NotasService($alumnoService);
        
        return new ControladorNotas($notasService);
    }
}