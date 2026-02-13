<?php

namespace App\Services;

use App\DTOs\MascotaDTO;
use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MascotaService
{
    /**
     * Synchronize mascotas for a cliente
     * - Create new mascotas (those without ID)
     * - Update existing mascotas (those with ID)
     * - Delete mascotas not present in the request
     */
    public function syncMascotas(Cliente $cliente, array $mascotasDTOs): void
    {
        $idsToKeep = [];

        foreach ($mascotasDTOs as $mascotaDTO) {
            if ($mascotaDTO->hasId()) {
                // Actualizar mascota existente
                $mascota = Mascota::where('id', $mascotaDTO->id)
                    ->where('cliente_id', $cliente->id)
                    ->firstOrFail();

                $mascota->update($mascotaDTO->toArray());
                $idsToKeep[] = $mascota->id;

                Log::debug('Mascota actualizada', [
                    'mascota_id' => $mascota->id,
                    'cliente_id' => $cliente->id
                ]);
            } else {
                // Crear nueva mascota
                $mascota = $cliente->mascotas()->create($mascotaDTO->toArray());
                $idsToKeep[] = $mascota->id;

                Log::debug('Mascota creada', [
                    'mascota_id' => $mascota->id,
                    'cliente_id' => $cliente->id
                ]);
            }
        }

        // Eliminar mascotas que no están en la lista (soft delete)
        $deletedCount = $cliente->mascotas()
            ->whereNotIn('id', $idsToKeep)
            ->delete();

        if ($deletedCount > 0) {
            Log::debug('Mascotas eliminadas', [
                'count' => $deletedCount,
                'cliente_id' => $cliente->id
            ]);
        }
    }

    /**
     * Create mascota for cliente
     */
    public function createMascota(Cliente $cliente, MascotaDTO $mascotaDTO): Mascota
    {
        return $cliente->mascotas()->create($mascotaDTO->toArray());
    }

    /**
     * Update mascota
     */
    public function updateMascota(int $id, MascotaDTO $mascotaDTO): Mascota
    {
        $mascota = Mascota::findOrFail($id);
        $mascota->update($mascotaDTO->toArray());
        return $mascota;
    }

    /**
     * Delete mascota
     */
    public function deleteMascota(int $id): bool
    {
        $mascota = Mascota::findOrFail($id);
        return $mascota->delete();
    }

    /**
     * Get mascotas by cliente
     */
    public function getMascotasByCliente(int $clienteId): Collection
    {
        return Mascota::where('cliente_id', $clienteId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

