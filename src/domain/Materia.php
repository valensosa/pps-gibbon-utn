<?php

namespace App\domain;

use JsonSerializable;

class Materia implements JsonSerializable
{
    private string $tituloAraucano;
    private string $tituloNombre;
    private string $planVigente;
    private string $actividadNombre;
    private string $actividadCodigo;
    private string $fecha;
    private string $nota;
    private string $resultado;
    private string $promedio;
    private string $formaAprobacion;
    private string $esOptativa;

    public function __construct(
        string $tituloAraucano,
        string $tituloNombre,
        string $planVigente,
        string $actividadNombre,
        string $actividadCodigo,
        string $fecha,
        string $nota,
        string $resultado,
        string $promedio,
        string $formaAprobacion,
        string $esOptativa
    ) {
        $this->tituloAraucano = $tituloAraucano;
        $this->tituloNombre = $tituloNombre;
        $this->planVigente = $planVigente;
        $this->actividadNombre = $actividadNombre;
        $this->actividadCodigo = $actividadCodigo;
        $this->fecha = $fecha;
        $this->nota = $nota;
        $this->resultado = $resultado;
        $this->promedio = $promedio;
        $this->formaAprobacion = $formaAprobacion;
        $this->esOptativa = $esOptativa;
    }

    public function getTituloAraucano(): string
    {
        return $this->tituloAraucano;
    }

    public function getTituloNombre(): string
    {
        return $this->tituloNombre;
    }

    public function getPlanVigente(): string
    {
        return $this->planVigente;
    }

    public function getActividadNombre(): string
    {
        return $this->actividadNombre;
    }

    public function getActividadCodigo(): string
    {
        return $this->actividadCodigo;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function getNota(): string
    {
        return $this->nota;
    }

    public function getResultado(): string
    {
        return $this->resultado;
    }

    public function getPromedio(): string
    {
        return $this->promedio;
    }

    public function getFormaAprobacion(): string
    {
        return $this->formaAprobacion;
    }

    public function getEsOptativa(): string
    {
        return $this->esOptativa;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'titulo_araucano' => $this->tituloAraucano,
            'titulo_nombre' => $this->tituloNombre,
            'plan_vigente' => $this->planVigente,
            'actividad_nombre' => $this->actividadNombre,
            'actividad_codigo' => $this->actividadCodigo,
            'fecha' => $this->fecha,
            'nota' => $this->nota,
            'resultado' => $this->resultado,
            'promedio' => $this->promedio,
            'forma_aprobacion' => $this->formaAprobacion,
            'es_optativa' => $this->esOptativa
        ];
    }
}
