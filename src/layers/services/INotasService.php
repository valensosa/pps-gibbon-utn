<?php

namespace App\services;

interface INotasService
{
    /**
     * Busca un alumno por su DNI y procesa sus materias.
     *
     * @param string $studentDni
     * @return array|null
     */
    public function buscarAlumnoPorDNI($studentDni);

    /**
     * Ordena materias por fecha (descendente - más reciente primero).
     *
     * @param array $materias
     * @return array
     */
    public function sortMateriasByDateDesc($materias);

    /**
     * Filtra solo materias con actividad_nombre.
     *
     * @param array $materias
     * @return array
     */
    public function filterMateriasWithActividad($materias);

    /**
     * Realiza la paginación de un array de materias.
     *
     * @param array $materias
     * @param int $paginaActual
     * @param int $materiasPorPagina
     * @return array
     */
    public function notasPaginacion($materias, $paginaActual, $materiasPorPagina);
}
