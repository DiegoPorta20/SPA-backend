<?php

namespace App\DTOs;

class MascotaDTO
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $nombre = null,
        public readonly ?string $especie = null,
        public readonly ?string $raza = null,
        public readonly ?int $edad = null,
        public readonly ?float $peso = null,
        public readonly ?string $sexo = null,
        public readonly string $estado = 'activo'
    ) {}

    /**
     * Create DTO from request array
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            nombre: $data['nombre'] ?? null,
            especie: $data['especie'] ?? null,
            raza: $data['raza'] ?? null,
            edad: isset($data['edad']) ? (int) $data['edad'] : null,
            peso: isset($data['peso']) ? (float) $data['peso'] : null,
            sexo: $data['sexo'] ?? null,
            estado: $data['estado'] ?? 'activo'
        );
    }

    /**
     * Convert DTO to array for model creation/update
     */
    public function toArray(): array
    {
        return array_filter([
            'nombre' => $this->nombre,
            'especie' => $this->especie,
            'raza' => $this->raza,
            'edad' => $this->edad,
            'peso' => $this->peso,
            'sexo' => $this->sexo,
            'estado' => $this->estado,
        ], fn($value) => $value !== null);
    }

    /**
     * Check if mascota has ID (exists in database)
     */
    public function hasId(): bool
    {
        return $this->id !== null;
    }
}

