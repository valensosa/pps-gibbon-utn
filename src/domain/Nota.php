<?php

namespace App\domain;

class Nota
{
    private int $materiaId;
    private float $nota;
    private string $fecha;
    private int $dniAlumno;

    public function __construct(
        int $materiaId,
        float $nota,
        string $fecha,
        int $dniAlumno
    ) {
        $this->materiaId = $materiaId;
        $this->nota = $nota;
        $this->fecha = $fecha;
        $this->dniAlumno = $dniAlumno;
    }

    public function getMateriaId(): int
    {
        return $this->materiaId;
    }

    public function getNota(): float
    {
        return $this->nota;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function getDniAlumno(): int
    {
        return $this->dniAlumno;
    }

    public function setMateriaId(int $materiaId): void
    {
        $this->materiaId = $materiaId;
    }

    public function setNota(float $nota): void
    {
        $this->nota = $nota;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function setDniAlumno(int $dniAlumno): void
    {
        $this->dniAlumno = $dniAlumno;
    }
}
