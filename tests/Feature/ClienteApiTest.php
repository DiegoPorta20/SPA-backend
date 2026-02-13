<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all clientes.
     */
    public function test_can_list_all_clientes(): void
    {
        // Arrange
        Cliente::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/clientes');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nombres',
                        'apellidos',
                        'dni',
                        'telefono',
                        'email',
                        'direccion',
                        'estado',
                        'mascotas',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /**
     * Test getting a single cliente.
     */
    public function test_can_get_single_cliente(): void
    {
        // Arrange
        $cliente = Cliente::factory()->create();

        // Act
        $response = $this->getJson("/api/clientes/{$cliente->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nombres',
                    'apellidos',
                    'dni',
                    'nombre_completo',
                    'mascotas',
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $cliente->id,
                    'dni' => $cliente->dni,
                ]
            ]);
    }

    /**
     * Test creating a cliente without mascotas.
     */
    public function test_can_create_cliente_without_mascotas(): void
    {
        // Arrange
        $clienteData = [
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'dni' => '12345678',
            'telefono' => '987654321',
            'email' => 'juan@example.com',
            'direccion' => 'Av. Test 123',
            'estado' => 'activo',
        ];

        // Act
        $response = $this->postJson('/api/clientes', $clienteData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nombres',
                    'apellidos',
                    'dni',
                ]
            ]);

        $this->assertDatabaseHas('clientes', [
            'dni' => '12345678',
            'nombres' => 'Juan',
        ]);
    }

    /**
     * Test creating a cliente with mascotas.
     */
    public function test_can_create_cliente_with_mascotas(): void
    {
        // Arrange
        $clienteData = [
            'nombres' => 'María',
            'apellidos' => 'García',
            'dni' => '87654321',
            'telefono' => '912345678',
            'email' => 'maria@example.com',
            'direccion' => 'Calle Test 456',
            'estado' => 'activo',
            'mascotas' => [
                [
                    'nombre' => 'Max',
                    'especie' => 'Perro',
                    'raza' => 'Labrador',
                    'edad' => 3,
                    'peso' => 25.5,
                    'sexo' => 'macho',
                    'estado' => 'activo',
                ],
                [
                    'nombre' => 'Luna',
                    'especie' => 'Gato',
                    'raza' => 'Siamés',
                    'edad' => 2,
                    'peso' => 4.2,
                    'sexo' => 'hembra',
                    'estado' => 'activo',
                ]
            ]
        ];

        // Act
        $response = $this->postJson('/api/clientes', $clienteData);

        // Assert
        $response->assertStatus(201);

        $this->assertDatabaseHas('clientes', [
            'dni' => '87654321',
        ]);

        $this->assertDatabaseHas('mascotas', [
            'nombre' => 'Max',
            'especie' => 'Perro',
        ]);

        $this->assertDatabaseHas('mascotas', [
            'nombre' => 'Luna',
            'especie' => 'Gato',
        ]);
    }

    /**
     * Test updating a cliente with mascota synchronization.
     */
    public function test_can_update_cliente_and_sync_mascotas(): void
    {
        // Arrange
        $cliente = Cliente::factory()->create([
            'dni' => '11111111',
        ]);

        $mascota1 = Mascota::factory()->create([
            'cliente_id' => $cliente->id,
            'nombre' => 'Original',
        ]);

        $updateData = [
            'nombres' => 'Updated Name',
            'apellidos' => $cliente->apellidos,
            'dni' => '11111111',
            'telefono' => $cliente->telefono,
            'estado' => 'activo',
            'mascotas' => [
                [
                    'id' => $mascota1->id,
                    'nombre' => 'Updated Mascota',
                    'especie' => 'Perro',
                    'estado' => 'activo',
                ],
                [
                    'nombre' => 'New Mascota',
                    'especie' => 'Gato',
                    'estado' => 'activo',
                ]
            ]
        ];

        // Act
        $response = $this->putJson("/api/clientes/{$cliente->id}", $updateData);

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nombres' => 'Updated Name',
        ]);

        $this->assertDatabaseHas('mascotas', [
            'id' => $mascota1->id,
            'nombre' => 'Updated Mascota',
        ]);

        $this->assertDatabaseHas('mascotas', [
            'nombre' => 'New Mascota',
            'cliente_id' => $cliente->id,
        ]);
    }

    /**
     * Test deleting a cliente.
     */
    public function test_can_delete_cliente(): void
    {
        // Arrange
        $cliente = Cliente::factory()->create();
        Mascota::factory()->count(2)->create(['cliente_id' => $cliente->id]);

        // Act
        $response = $this->deleteJson("/api/clientes/{$cliente->id}");

        // Assert
        $response->assertStatus(200);

        $this->assertSoftDeleted('clientes', [
            'id' => $cliente->id,
        ]);

        // Verify mascotas are also soft deleted
        $this->assertEquals(2, Mascota::onlyTrashed()->where('cliente_id', $cliente->id)->count());
    }

    /**
     * Test validation error for duplicate DNI.
     */
    public function test_cannot_create_cliente_with_duplicate_dni(): void
    {
        // Arrange
        Cliente::factory()->create(['dni' => '12345678']);

        $clienteData = [
            'nombres' => 'Test',
            'apellidos' => 'User',
            'dni' => '12345678',
            'estado' => 'activo',
        ];

        // Act
        $response = $this->postJson('/api/clientes', $clienteData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dni']);
    }

    /**
     * Test validation error for missing required fields.
     */
    public function test_cannot_create_cliente_without_required_fields(): void
    {
        // Arrange
        $clienteData = [
            'telefono' => '987654321',
        ];

        // Act
        $response = $this->postJson('/api/clientes', $clienteData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nombres', 'apellidos', 'dni']);
    }

    /**
     * Test 404 response for non-existent cliente.
     */
    public function test_returns_404_for_non_existent_cliente(): void
    {
        // Act
        $response = $this->getJson('/api/clientes/99999');

        // Assert
        $response->assertStatus(404);
    }
}

