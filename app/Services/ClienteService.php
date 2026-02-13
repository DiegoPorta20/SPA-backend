<?php

namespace App\Services;

use App\DTOs\ClienteDTO;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteService
{
    public function __construct(
        private MascotaService $mascotaService
    ) {}

    /**
     * Get all clientes with their mascotas
     */
    public function getAllClientes(): Collection
    {
        return Cliente::with(['mascotas' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get cliente by ID with mascotas
     */
    public function getClienteById(int $id): ?Cliente
    {
        return Cliente::with(['mascotas' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);
    }

    /**
     * Create a new cliente with mascotas
     */
    public function createCliente(ClienteDTO $clienteDTO): Cliente
    {
        return DB::transaction(function () use ($clienteDTO) {
            // Crear el cliente
            $cliente = Cliente::create($clienteDTO->toArray());

            // Crear las mascotas asociadas si existen
            if (!empty($clienteDTO->mascotas)) {
                $this->mascotaService->syncMascotas($cliente, $clienteDTO->getMascotasData());
            }

            // Cargar las mascotas para retornar el objeto completo
            $cliente->load('mascotas');

            Log::info('Cliente creado exitosamente', [
                'cliente_id' => $cliente->id,
                'dni' => $cliente->dni,
                'mascotas_count' => $cliente->mascotas->count()
            ]);

            return $cliente;
        });
    }

    /**
     * Update cliente with mascotas synchronization
     */
    public function updateCliente(int $id, ClienteDTO $clienteDTO): Cliente
    {
        return DB::transaction(function () use ($id, $clienteDTO) {
            $cliente = Cliente::findOrFail($id);

            // Actualizar datos del cliente
            $cliente->update($clienteDTO->toArray());

            // Sincronizar mascotas (crear, actualizar, eliminar)
            if (isset($clienteDTO->mascotas)) {
                $this->mascotaService->syncMascotas($cliente, $clienteDTO->getMascotasData());
            }

            // Recargar las mascotas
            $cliente->refresh();
            $cliente->load('mascotas');

            Log::info('Cliente actualizado exitosamente', [
                'cliente_id' => $cliente->id,
                'dni' => $cliente->dni,
                'mascotas_count' => $cliente->mascotas->count()
            ]);

            return $cliente;
        });
    }

    /**
     * Delete cliente (soft delete)
     */
    public function deleteCliente(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $cliente = Cliente::findOrFail($id);

            // Soft delete de las mascotas asociadas
            $cliente->mascotas()->delete();

            // Soft delete del cliente
            $deleted = $cliente->delete();

            Log::info('Cliente eliminado exitosamente', [
                'cliente_id' => $cliente->id,
                'dni' => $cliente->dni
            ]);

            return $deleted;
        });
    }

    /**
     * Check if DNI exists (excluding specific cliente ID)
     */
    public function dniExists(string $dni, ?int $excludeId = null): bool
    {
        $query = Cliente::where('dni', $dni);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}

