<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ClienteDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService
    ) {}

    /**
     * Display a listing of clientes.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $perPage = request('per_page', 15);
            $search = request('search', '');

            $clientes = $this->clienteService->getAllClientes($perPage, $search);

            return ClienteResource::collection($clientes);
        } catch (\Exception $e) {
            Log::error('Error al listar clientes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al obtener la lista de clientes',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Display the specified cliente.
     *
     * @param int $id
     * @return JsonResponse|ClienteResource
     */
    public function show(int $id): JsonResponse|ClienteResource
    {
        try {
            $cliente = $this->clienteService->getClienteById($id);

            if (!$cliente) {
                return response()->json([
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return new ClienteResource($cliente);
        } catch (\Exception $e) {
            Log::error('Error al obtener cliente', [
                'cliente_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al obtener el cliente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Store a newly created cliente with mascotas.
     *
     * @param StoreClienteRequest $request
     * @return JsonResponse
     */
    public function store(StoreClienteRequest $request): JsonResponse
    {
        try {
            $clienteDTO = ClienteDTO::fromRequest($request->validated());
            $cliente = $this->clienteService->createCliente($clienteDTO);

            return (new ClienteResource($cliente))
                ->response()
                ->setStatusCode(201)
                ->header('Location', route('clientes.show', $cliente->id));
        } catch (\Exception $e) {
            Log::error('Error al crear cliente', [
                'data' => $request->validated(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al crear el cliente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Update the specified cliente with mascotas.
     *
     * @param UpdateClienteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateClienteRequest $request, int $id): JsonResponse
    {
        try {
            $clienteDTO = ClienteDTO::fromRequest($request->validated());
            $cliente = $this->clienteService->updateCliente($id, $clienteDTO);

            return (new ClienteResource($cliente))
                ->response()
                ->setStatusCode(200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Cliente no encontrado'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al actualizar cliente', [
                'cliente_id' => $id,
                'data' => $request->validated(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al actualizar el cliente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Remove the specified cliente.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->clienteService->deleteCliente($id);

            return response()->json([
                'message' => 'Cliente eliminado exitosamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Cliente no encontrado'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente', [
                'cliente_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al eliminar el cliente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }
}


