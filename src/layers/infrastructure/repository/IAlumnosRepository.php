<?php

namespace App\infrastructure\repository;

interface IAlumnosRepository
{
    /**
     * Busca una persona por DNI en la API externa.
     *
     * @param string $studentDNI
     * @return array|null
     */
    public function getPersonasByDNI($studentDNI);

    /**
     * Obtiene los datos analíticos (historia académica) de una persona por su ID.
     *
     * @param mixed $personaId
     * @return array|null
     */
    public function getDatosAnalitico($personaId);
}
