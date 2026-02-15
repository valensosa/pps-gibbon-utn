<?php

namespace App\domain;

class Materia
{
    private int $id;
    private string $nombre;
    private int $tituloAraucano;
    private string $tituloNombre;
    private string $planVigente;
    private bool $optativa;

    public function __construct(
        int $id,
        string $nombre,
        int $tituloAraucano,
        string $tituloNombre,
        string $planVigente,
        bool $optativa
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tituloAraucano = $tituloAraucano;
        $this->tituloNombre = $tituloNombre;
        $this->planVigente = $planVigente;
        $this->optativa = $optativa;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getTituloAraucano(): int
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

    public function isOptativa(): bool
    {
        return $this->optativa;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setTituloAraucano(int $tituloAraucano): void
    {
        $this->tituloAraucano = $tituloAraucano;
    }

    public function setTituloNombre(string $tituloNombre): void
    {
        $this->tituloNombre = $tituloNombre;
    }

    public function setPlanVigente(string $planVigente): void
    {
        $this->planVigente = $planVigente;
    }

    public function setOptativa(bool $optativa): void
    {
        $this->optativa = $optativa;
    }
}
