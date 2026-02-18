<?php

namespace App\domain;

class Alumno
{
    private string $dni;
    private string $nombre;
    private string $apellido;
    private string $email;
    private string $estado;

    public function __construct(
        string $dni,
        string $nombre,
        string $apellido,
        string $email,
        string $estado
    ) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->estado = $estado;
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

    public function getEstado(): string
    {
        return $this->estado;
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

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function validarDni(): bool
    {
        // Valida que el DNI contenga solo números y tenga al menos 6 dígitos
        return preg_match('/^\d{6,}$/', $this->dni) === 1;
    }
}
