<?php

namespace App\domain;

use JsonSerializable;

class Alumno implements JsonSerializable
{
    private int $id;
    private string $dni;
    private string $nombre;
    private string $apellido;
    private string $email;

    public function __construct(
        int $id,
        string $dni,
        string $nombre,
        string $apellido,
        string $email
    ) {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDni(): string
    {
        return $this->dni;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDni(string $dni): void
    {
        $this->dni = $dni;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function validarDni(): bool
    {
        // Valida que el DNI contenga solo números y tenga al menos 6 dígitos
        return preg_match('/^\d{6,}$/', $this->dni) === 1;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'dni' => $this->dni,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            // Campo 'display' formateado para el autocompletado del frontend
            'display' => $this->nombre . ' ' . $this->apellido . ' - ' . $this->dni
        ];
    }
}
