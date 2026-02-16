<?php

namespace App\DTOs;

class ClienteDTO
{
    public function __construct(
        public readonly string $nombres,
        public readonly string $apellidos,
        public readonly string $dni,
        public readonly ?string $telefono = null,
        public readonly ?string $email = null,
        public readonly ?string $direccion = null,
        public readonly string $estado = 'activo',
        public readonly array $mascotas = []
    ) {}

    /**
     * Create DTO from request array
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            nombres: $data['nombres'],
            apellidos: $data['apellidos'],
            dni: $data['dni'],
            telefono: $data['telefono'] ?? null,
            email: $data['email'] ?? null,
            direccion: $data['direccion'] ?? null,
            estado: $data['estado'] ?? 'activo',
            mascotas: $data['mascotas'] ?? []
        );
    }

    /**
     * Convert DTO to array for model creation/update
     */
    public function toArray(): array
    {
        return [
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'dni' => $this->dni,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'estado' => $this->estado,
        ];
    }

    /**
     * Get mascotas data
     */
    public function getMascotasData(): array
    {
        return array_map(function ($mascota) {
            return MascotaDTO::fromRequest($mascota);
        }, $this->mascotas);
    }
}

